<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    /**
     * Show all withdrawal history.
     */
    public function index()
    {
        $withdraws = Withdraw::with('user')->latest()->paginate(10);
        return view('admin.pages.withdraw.withdraw_history', compact('withdraws'));
    }

    /**
     * Show pending withdrawal requests.
     */
    public function pendingWithdraw()
    {
        $pendingWithdraws = Withdraw::with('user')->where('status', 'pending')->latest()->paginate(10);
        $pendingCount = Withdraw::where('status', 'pending')->count();

        return view('admin.pages.withdraw.pending_withdraw', compact('pendingWithdraws', 'pendingCount'));
    }

    /**
     * Show rejected withdrawal requests.
     */
    public function rejectWithdraw()
    {
        $rejectWithdraws = Withdraw::with('user')->where('status', 'rejected')->latest()->paginate(10);
        return view('admin.pages.withdraw.reject_withdraw', compact('rejectWithdraws'));
    }

    /**
     * Approve or reject a withdrawal request.
     */
    public function updateStatus(Request $request, $id)
    {
        $withdraw = Withdraw::with('user')->findOrFail($id);

        // Already processed?
        if ($withdraw->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already processed.');
        }

        $user = $withdraw->user;

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $settings = GeneralSetting::first();
        $chargePercent = $settings->withdraw_charge ?? 0;
        $shoppingPercent = $settings->withdraw_shopping_wallet_percentage ?? 0;

        if ($request->status === 'approve') {
            $charge = ($withdraw->amount * $chargePercent) / 100;
            $shoppingAmount = ($withdraw->amount * $shoppingPercent) / 100;
            $netAmount = $withdraw->amount - $charge - $shoppingAmount;

            $withdraw->update([
                'charge' => $charge,
                'shopping_amount' => $shoppingAmount,
                'net_amount' => $netAmount,
                'status' => 'approved',
            ]);

            // Add shopping amount to user shopping wallet
            $user->shopping_wallet += $shoppingAmount;
            $user->save();

        } elseif ($request->status === 'reject') {
            // Return amount to user
            $user->income_wallet += $withdraw->amount;
            $user->save();

            $withdraw->update([
                'status' => 'rejected',
            ]);
        }

        return redirect()->back()->with('success', 'Withdraw status updated.');
    }
}
