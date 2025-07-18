<?php

namespace App\Http\Controllers\api;

use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;

class WithdrawController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $withdrawals = Withdraw::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        $settings = GeneralSetting::first();
        $percent = $settings->withdraw_shopping_wallet_percentage ?? 0;
        $chargePercent = $settings->withdraw_charge ?? 0;

        $formattedWithdrawals = $withdrawals->map(function ($withdrawal) use ($percent, $chargePercent) {
            if ($withdrawal->status === 'pending' &&
                (is_null($withdrawal->net_amount) || is_null($withdrawal->shopping_amount) || is_null($withdrawal->charge))) {
                $charge = ($withdrawal->amount * $chargePercent) / 100;
                // $remainingAmount = $withdrawal->amount - $charge;
                $shoppingAmount = ($withdrawal->amount * $percent) / 100;
                $netAmount = $withdrawal->amount - $charge - $shoppingAmount;
            } else {
                $charge = $withdrawal->charge;
                $shoppingAmount = $withdrawal->shopping_amount;
                $netAmount = $withdrawal->net_amount;
            }

            return [
                'id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'charge' => round($charge, 2),
                'net_amount' => round($netAmount, 2),
                'shopping_amount' => round($shoppingAmount, 2),
                'method' => $withdrawal->method,
                'number' => $withdrawal->number,
                'status' => $withdrawal->status,
                'created_at' => $withdrawal->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $withdrawal->updated_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'status' => true,
            'data' => [
                'withdrawals' => $formattedWithdrawals,
                'pagination' => [
                    'current_page' => $withdrawals->currentPage(),
                    'per_page' => $withdrawals->perPage(),
                    'total' => $withdrawals->total(),
                    'last_page' => $withdrawals->lastPage(),
                ]
            ]
        ]);
    }

    public function withdrawRequest(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'number' => 'required',
            'method' => 'required',
        ]);

        $user = $request->user();
        $amount = $request->input('amount');

        if ($amount > $user->income_wallet) {
            return response()->json([
                'status' => false,
                'message' => 'Insufficient funds',
                'balance' => $user->income_wallet
            ], 400);
        }

        $settings = GeneralSetting::first();
        $percent = $settings->withdraw_shopping_wallet_percentage ?? 0;
        $chargePercent = $settings->withdraw_charge ?? 0;

        $charge = ($amount * $chargePercent) / 100;
        $shoppingAmount = ($amount * $percent) / 100;
        $netAmount = $amount - $charge - $shoppingAmount;

        $user->income_wallet -= $amount;
        $user->save();

        $withdrawal = Withdraw::create([
            'user_id' => $user->id,
            'method' => $request->method,
            'amount' => $amount,
            'number' => $request->number,
            'status' => 'pending',
            'charge' => $charge,
            'shopping_amount' => $shoppingAmount,
            'net_amount' => $netAmount,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Withdrawal request submitted successfully',
            'data' => [
                'id' => $withdrawal->id,
                'amount' => $withdrawal->amount,
                'charge' => round($charge, 2),
                'net_amount' => round($netAmount, 2),
                'shopping_amount' => round($shoppingAmount, 2),
                'method' => $withdrawal->method,
                'number' => $withdrawal->number,
                'status' => $withdrawal->status,
                'created_at' => $withdrawal->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}