<?php

namespace App\Http\Controllers\Admin;

use App\Models\Fund;
use App\Models\Order;
use App\Models\ClubSetting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use App\Models\User;

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

        $order->status = $request->status;
        $order->save();

        if ($order->status === 'completed') {
            $this->updateFunds($order->pv);
        }

        if ($order->user->referer) {
            $this->checkClubEligibility($order->user->referer);
        }

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }

    private function updateFunds($pv)
    {
        $settings = GeneralSetting::first();
        $pvValue = $settings->pv_value;
        $amount = $pv * $pvValue;
        
        Fund::whereIn('name', [
        'Club Fund',
        'Insurance Fund',
        'Poor Fund',
        'Rank Fund'
        ])
        ->where('status', 1)
        ->increment('amount', $amount);
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
