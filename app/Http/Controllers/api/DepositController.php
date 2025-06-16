<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function depositRequest(Request $request): JsonResponse{

        try {
            $request->validate([
                'amount' => 'required|numeric|min:1|not_in:0',
                'method_id' => 'required',
                'transaction_id' => 'required|string|unique:deposits,transaction_id',
                'number' => 'required|string|max:15|min:10',
            ]);

            $payment = PaymentMethod::where('id', $request->input('method_id'))->first();

            if ($payment == null ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment method not found',
                ]);
            }

            $user = $request->user();

        $deposit =   Deposit::create([
                'user_id' => $user->id,
                'payment_method' => $payment->method_name,
                'transaction_id' => $request->input('transaction_id'),
                'number' => $request->input('number'),
                'amount' => $request->input('amount'),
                'type' => 'deposit',
                'currency' => 'BDT',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Deposit request submitted successfully please waiting for approval',
                'deposit' => $deposit,
            ]);
        }catch (\Exception $exception){
            return response()->json([
                'status' => false,
                'message' => $exception->getMessage(),
            ]);
        }
    }


    public function depositHistory(Request $request): JsonResponse
    {
        $user = $request->user();

        $depositsPaginated = Deposit::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        $totalAmount = Deposit::where('user_id', $user->id)->sum('amount');


        $pending = Deposit::where('user_id', $user->id)
            ->where('type', 'pending')
            ->count();


        $latest = Deposit::where('user_id', $user->id)
            ->latest()
            ->first();

        return response()->json([
            'status' => true,
            'totalAmount' => $totalAmount,
            'pending' => $pending,
            'latest' => $latest,
            'data' => $depositsPaginated->items(),
            'total' => $depositsPaginated->total(),
            'last_page' => $depositsPaginated->lastPage(),
            'from' => $depositsPaginated->firstItem(),
        ]);
    }

}
