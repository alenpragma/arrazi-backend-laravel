<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

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

              $product->image_url = $product->images ? url('storage/' . $product->images) : null;

            return response()->json([
                'status' => true,
                'shopping_wallet' => $user->shopping_wallet,
                'message' => 'Product details retrieved successfully.',
                'data' => $product,
            ]);
        }

        $products = Product::where('stock', '>', 0)->select('title','slug','images','points','sale_price','id','regular_price')->paginate(10);
        $products->getCollection()->transform(function ($product) {
        $product->image_url = $product->images ? url('storage/' . $product->images) : null;
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




    public function buyProducts(Request $request)
    {
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $user = $request->user();
            $product = Product::findOrFail($validated['product_id']);
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

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'product_id' => $validated['product_id'],
                'quantity' => $quantity,
                'pv' => $totalPoints,
                'amount' => $totalCost,
                'status' => 'Pending',
            ]);

            // Update user wallet and points
            $user->shopping_wallet -= $totalCost;
            $user->points += $totalPoints;
            if($user->is_active == 0){
                $user->is_active = 1;
                $user->shopping_wallet += $totalPoints*3;
            }
            $user->save();

            //$this->distributeLeftChainPoints($user, $totalPoints);
            $this->giveReferralBonus($user, $totalPoints);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Product purchased successfully.',
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
}
