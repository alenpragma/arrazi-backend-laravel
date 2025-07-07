<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Notifications\WithdrawStatusNotification;

class WithdrawController extends Controller
{
    public function index()
    {
        $withdraws = Withdraw::with('user')->latest()->paginate(10);

        return view('admin.pages.withdraw.withdraw_history', compact('withdraws'));
    }

    public function updateStatus(Request $request, $id)
    {
        $withdraw = Withdraw::with('user')->findOrFail($id);

        if ($withdraw->status !== 'pending') {
            return redirect()->back()->with('error', 'This request is already processed.');
        }

        $user = $withdraw->user;

        if ($request->status === 'approve') {
            $withdraw->status = 'approved';
        } elseif ($request->status === 'reject') {
            $withdraw->status = 'rejected';
            $user->income_wallet += $withdraw->amount;
            $user->save();
        }

        $withdraw->save();

        return redirect()->back()->with('success', 'Withdraw status updated.');
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
}

