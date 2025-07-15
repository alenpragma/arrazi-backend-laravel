<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index()
    {
        $deposits = Deposit::with(['user', 'paymentMethod'])->latest()->paginate(10);

        return view('admin.pages.deposits.deposit_history', compact('deposits'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        $deposit = Deposit::findOrFail($id);

        if ($request->status === 'approved' && $deposit->status !== 'approved') {
            $user = $deposit->user;

            if ($user) {
                $user->increment('shopping_wallet', $deposit->amount);
            }
        }

        $deposit->status = $request->status;
        $deposit->save();

        return back()->with('success', 'Deposit status updated successfully.');
    }

    public function pendingDeposits()
    {
        $pendingDeposits = Deposit::with(['user', 'paymentMethod'])
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.pages.deposits.pending_deposit', compact('pendingDeposits'));
    }

    public function rejectDeposits()
    {
        $rejectedDeposits = Deposit::with(['user', 'paymentMethod'])
            ->where('status', 'rejected')
            ->latest()
            ->paginate(10);

        return view('admin.pages.deposits.rejected_deposit', compact('rejectedDeposits'));
    }
}
