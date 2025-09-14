<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fund;
use App\Models\User;
use App\Models\Order;
use App\Models\ClubSetting;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Models\DealerBonusHistory;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $query = Order::with('product', 'user')->latest();

    if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'])) {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10);

    return view('admin.pages.orders.index', compact('orders'));
}


    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'completed') {
            return redirect()->back()->with('error', 'Completed orders cannot be updated.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        if ($request->status === 'completed') {
            $order->completeOrder();

            $dealer = $order->dealer;
            if ($dealer) {
                $bonusSettings = GeneralSetting::first();
                $dealerPvValue = $bonusSettings->dealer_pv_value ?? 4;
                $bonusAmount = $order->pv * $dealerPvValue;

                $dealer->income_wallet += $bonusAmount;
                $dealer->save();
                DealerBonusHistory::create([
                    'dealer_id' => $dealer->id,
                    'order_id' => $order->id,
                    'amount' => $bonusAmount,
                    'description' => 'Dealer bonus added to income_wallet for order #Arrazi-' . $order->id
                ]);
            }
        } else {
            $order->status = $request->status;
            $order->save();
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }


    private function checkClubEligibility(User $user)
    {
        if ($user->is_club) return;

        $requiredPV = GeneralSetting::first()->required_pv;

        $directReferrals = $user->referrals()->pluck('id');

        if ($directReferrals->isEmpty()) return;

        $totalPV = Order::whereIn('user_id', $directReferrals)
                        ->where('status', 'completed')
                        ->sum('pv');

        if ($totalPV >= $requiredPV) {
            $user->update([
                'is_club' => 1
            ]);
        }
    }

}
