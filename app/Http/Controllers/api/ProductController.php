<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\FundBonusHistory;
use Illuminate\Http\JsonResponse;
use App\Models\DealerBonusHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function index(Request $request): JsonResponse
    {
        if ($request->slug) {
            $user = $request->user();
            $product = Product::where('slug', $request->slug)->first();

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found.',
                ], 404);
            }

              $product->images = $product->images ? url('storage/' . $product->images) : null;

            return response()->json([
                'status' => true,
                'shopping_wallet' => $user->shopping_wallet,
                'message' => 'Product details retrieved successfully.',
                'data' => $product,
            ]);
        }

        $products = Product::where('stock', '>', 0)->select('title','slug','images','points','sale_price','id','regular_price')->paginate(10);
        $products->getCollection()->transform(function ($product) {
        $product->images = $product->images ? url('storage/' . $product->images) : null;
        return $product;
        });

        return response()->json([
            'status' => true,
            'message' => 'Product list retrieved successfully.',
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    }


    public function searchDealer(Request $request)
    {
        $query = $request->input('query');

        $dealers = User::where('role', 'dealer')
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                ->orWhere('email', 'like', "%$query%")
                ->orWhere('dealer_id', 'like', "%$query%");
            })
            ->select('id', 'name', 'email', 'dealer_id')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => true,
            'dealers' => $dealers
        ]);
    }

    public function buyProducts(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'dealer_id' => 'required|exists:users,id',
            ]);

            $user = $request->user();
            $product = Product::findOrFail($validated['product_id']);
            $dealer = User::where('id', $validated['dealer_id'])->where('role', 'dealer')->firstOrFail();

            $quantity = $validated['quantity'];
            $totalPoints = $product->points * $quantity;
            $totalCost = $product->sale_price * $quantity;

            if ($user->shopping_wallet < $totalCost) {
                return response()->json([
                    'status' => false,
                    'message' => 'Insufficient balance in shopping wallet.',
                ], 422);
            }

            DB::beginTransaction();


            $order = Order::create([
                'user_id' => $user->id,
                'dealer_id' => $dealer->id,
                'product_id' => $validated['product_id'],
                'quantity' => $quantity,
                'pv' => $totalPoints,
                'amount' => $totalCost,
                'status' => 'Pending',
            ]);

            $user->shopping_wallet -= $totalCost;
            $user->points += $totalPoints;

            if ($user->is_active == 0) {
                $user->is_active = 1;
                $user->shopping_wallet += $totalPoints * 3;
            }
            $user->save();

            $this->giveReferralBonus($user, $totalPoints);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Product purchased successfully.',
                'order' => $order
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function orderHistory(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->with('product')->paginate(10);
        $totalOrder = Order::where('user_id', $user->id)->count();
        $totalPoints = Order::where('user_id', $user->id)->sum('pv');
        $totalCost = Order::where('user_id',$user->id)->sum('amount');


        $directReferralIds = User::where('refer_by', $user->id)->pluck('id');
        $directReferralPV = Order::whereIn('user_id', $directReferralIds)->sum('pv');

        return response()->json([
            'status' => true,
            'message' => 'Order list retrieved successfully.',
            'data' => $orders->items(),
            'total' => $orders->total(),
            'current_page' => $orders->currentPage(),
            'per_page' => $orders->perPage(),
            'last_page' => $orders->lastPage(),
            'totalOrder' => $totalOrder,
            'totalPoints' => $totalPoints,
            'totalCost' => $totalCost,
            'points' => $user->points ?? 0,
            'total_referral_pv'  => $directReferralPV,
        ]);

    }

//    private function distributeLeftChainPoints(User $user, int $points): void
//    {
//        $current = $user;
//
//        while ($current && $current->upline_id) {
//            $upline = User::find($current->upline_id);
//
//            if ($upline && $upline->left_user_id === $current->id) {
//                $upline->points = ($upline->points ?? 0) + $points;
//                $upline->save();
//            } else {
//                break;
//            }
//
//            $current = $upline;
//        }
//    }

    private function giveReferralBonus(User $user, int $totalPoints): void
    {
        if (!$user->refer_by) return;

        $referrer = User::find($user->refer_by);

        if ($referrer) {
            $bonus = ($totalPoints * 5);
            $referrer->income_wallet = ($referrer->income_wallet ?? 0) + $bonus;
            $referrer->save();
        }
    }


    public function fundBonusHistory(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $perPage = $request->get('per_page', 10);

        $history = FundBonusHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        $data = $history->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'fund_name' => $item->fund_name,
                'amount' => $item->amount,
                'description' => $item->description,
                'date' => $item->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => true,
            'data' => $data,
            'pagination' => [
                'current_page' => $history->currentPage(),
                'last_page' => $history->lastPage(),
                'per_page' => $history->perPage(),
                'total' => $history->total(),
            ]
        ]);
    }

        // dealers
    public function dealerOrders(Request $request)
    {
        $dealer = $request->user();

        if ($dealer->role !== 'dealer') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Only dealers can access this route.'
            ], 403);
        }

        $orders = Order::where('dealer_id', $dealer->id)
            ->with(['product:id,title,slug,images,sale_price,points', 'user:id,name,email'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $data = $orders->getCollection()->map(function ($order) {
            return [
                'id' => $order->id,
                'product' => [
                    'id' => $order->product->id,
                    'title' => $order->product->title,
                    'slug' => $order->product->slug,
                    'images' => $order->product->images ? url('storage/' . $order->product->images) : null,
                    'sale_price' => $order->product->sale_price,
                    'points' => $order->product->points,
                ],
                'user' => [
                    'id' => $order->user->id,
                    'name' => $order->user->name,
                    'email' => $order->user->email,
                ],
                'quantity' => $order->quantity,
                'amount' => $order->amount,
                'pv' => $order->pv,
                'status' => $order->status,
                'created_at' => $order->created_at->format('Y-m-d H:i:s')
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Dealer orders retrieved successfully.',
            'orders' => $data,
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ]
        ]);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $dealer = $request->user();

        if ($dealer->role !== 'dealer') {
            return response()->json(['status' => false, 'message' => 'Unauthorized.'], 403);
        }

        $request->validate([
            'status' => 'required|in:Pending,Processing,Completed'
        ]);

        $order = Order::where('id', $id)->where('dealer_id', $dealer->id)->firstOrFail();

        $previousStatus = $order->status;

        if ($previousStatus === 'Completed' && $request->status !== 'Completed') {
            return response()->json([
                'status' => false,
                'message' => 'Completed order cannot be changed to another status.'
            ], 400);
        }

        if ($request->status === 'Completed' && $previousStatus !== 'Completed') {
            $order->completeOrder();

            $bonusSettings = GeneralSetting::first();
            $dealerPvValue = $bonusSettings->dealer_pv_value;
            $bonusAmount = $order->pv * $dealerPvValue;
            $dealer->income_wallet += $bonusAmount;
            $dealer->save();

            DealerBonusHistory::create([
                'dealer_id' => $dealer->id,
                'order_id' => $order->id,
                'amount' => $bonusAmount,
                'description' => 'Dealer bonus added to income_wallet for order #Arrazi-' . $order->id
            ]);
        } else {
            $order->status = $request->status;
            $order->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Order status updated successfully.',
            'order' => $order
        ]);
    }

    public function dealerBonusHistory(Request $request)
    {
        $dealer = $request->user();

        if ($dealer->role !== 'dealer') {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized. Only dealers can view bonus history.'
            ], 403);
        }

        $history = DealerBonusHistory::where('dealer_id', $dealer->id)
                    ->with('order:id,status')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json([
            'status' => true,
            'data' => $history
        ]);
    }

}
