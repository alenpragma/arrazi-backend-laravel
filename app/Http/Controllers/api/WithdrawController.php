<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Withdraw;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $withdraw = Withdraw::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'data' => $withdraw
        ]);
    }

    public function withdrawRequest(Request $request)
    {
        $user = $request->user();
        $amount = $request->input('amount');
        $number = $request->input('number');
        $method = $request->input('method');

        if($amount > $user->income_wallet){
            return response(['message' => 'Insufficient funds' ,'balance' =>$user->income_wallet], 400);
        }

        $user->income_wallet -= $amount;
        $user->save();
       $data =  Withdraw::create([
            'user_id' => $user->id,
            'method' => $method,
            'amount' => $amount,
            'number' => $number,
            'status' => 0,
        ]);


        return response()->json([
            'status' => true,
            'message' => 'Withdraw Success',
            'data' => $data
        ]);
    }
}
