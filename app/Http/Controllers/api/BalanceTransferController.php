<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\TransferHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceTransferController extends Controller
{
    public function transfer(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:1',
            'receiver_email' => 'required|email|exists:users,email',
        ]);

        $sender = $request->user();
        $receiver = User::where('email', $validated['receiver_email'])->first();
        $amount = $validated['amount'];

        if ($sender->id === $receiver->id) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot transfer balance to yourself.',
            ], 422);
        }

        if ($sender->shopping_wallet < $amount) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient funds.',
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Deduct from sender
            $sender->shopping_wallet -= $amount;
            $sender->save();

            // Credit to receiver
            $receiver->shopping_wallet += $amount;
            $receiver->save();

            TransferHistory::create([
                'user_id' => $sender->id,
                'amount' => $amount,
                'from' => $sender->email,
                'to' => $receiver->email,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Transfer successful.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Transfer failed. Please try again.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $user = $request->user();
        $transfers = TransferHistory::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $transfers->Items(),
            'total' => $transfers->total(),
            'per_page' => $transfers->perPage(),
            'current_page' => $transfers->currentPage(),
            'last_page' => $transfers->lastPage(),
        ]);
    }
}
