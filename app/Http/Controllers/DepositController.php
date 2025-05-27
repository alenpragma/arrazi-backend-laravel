<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function depositRequest(Request $request): JsonResponse{
        $request->validate([
            'amount' => 'required|numeric|min:1|not_in:0',
            'payment_method' => 'required|string|max:100',
            'transaction_id' => 'required|string|max:45|min:8',
            'number' => 'required|string|max:15|min:10',
        ]);

        try {
            $user = $request->user();

            Deposit::create([
                'user_id' => $user->id,
                'payment_method' => $request->input('payment_method'),
                'transaction_id' => $request->input('transaction_id'),
                'number' => $request->input('number'),
                'amount' => $request->input('amount'),
                'type' => 'deposit',
                'currency' => 'BDT',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Deposit request submitted successfully please waiting for approval',
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }
}
