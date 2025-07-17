<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdraws = Withdraw::with('user')->latest()->paginate(10);

        return view('admin.pages.withdraw.withdraw_history', compact('withdraws'));
    }

    public function pendingWithdraw()
    {
        $pendingWithdraws = Withdraw::with('user')->where('status', 'pending')->latest()->paginate(10);
        $pendingCount = Withdraw::where('status', 'pending')->count();

        return view('admin.pages.withdraw.pending_withdraw', compact('pendingWithdraws', 'pendingCount'));
    }

    public function rejectWithdraw()
    {
        $rejectWithdraws = Withdraw::with('user')->where('status', 'rejected')->latest()->paginate(10);

        return view('admin.pages.withdraw.reject_withdraw', compact('rejectWithdraws'));
    }

    public function updateStatus(Request $request, $id)
    {
        $withdraw = Withdraw::with('user')->findOrFail($id);

        if ($withdraw->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already processed.');
        }

        $user = $withdraw->user;
        $settings = GeneralSetting::first();
        $percent = $settings->withdraw_shopping_wallet_percentage ?? 0;
        $chargePercent = $settings->withdraw_charge ?? 0;

        if ($request->status === 'approve') {
            $charge = ($withdraw->amount * $chargePercent) / 100;
            $shoppingAmount = ($withdraw->amount * $percent) / 100;
            $netAmount = $withdraw->amount - $charge - $shoppingAmount;

            $user->shopping_wallet += $shoppingAmount;
            $user->save();

            $withdraw->charge = $charge;
            $withdraw->shopping_amount = $shoppingAmount;
            $withdraw->net_amount = $netAmount;
            $withdraw->status = 'approved';

        } elseif ($request->status === 'reject') {

            $user->income_wallet += $withdraw->amount;
            $user->save();

            $withdraw->status = 'rejected';
        }

        $withdraw->save();

        return redirect()->back()->with('success', 'Withdraw status updated.');
    }
}
