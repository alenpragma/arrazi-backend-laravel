<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use App\Models\Stocks;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use App\Http\Controllers\Controller;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $stock = Stocks::where('user_id', $user->id)->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'status' => true,
            'total' => Stocks::where('user_id', $user->id)->count(),
            'totalPaid' => Stocks::where('user_id', $user->id)->sum('amount'),
            'matured' => Stocks::where('user_id', $user->id)->where('status', 'inactive')->count(),
            'data' => $stock,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function buyStocks(Request $request)
    {
        $user = $request->user();
        if($user->shopping_wallet < 210){
            return response()->json([
                'status' => false,
                'message' => 'you dont have enough money to buy this stock',
            ],400);
        }

        $settings = GeneralSetting::first();
        $stockLimit = $settings->max_stock_per_user ?? 20;

        $userStockCount = Stocks::where('user_id', $user->id)->count();
        if ($userStockCount >= $stockLimit) {
            return response()->json([
                'status' => false,
                'message' => "You have reached the maximum stock purchase limit of $stockLimit.",
            ], 403);
        }

        $user->shopping_wallet -= 210;
        $referer = $user->referer()->first();
        $referer->shopping_wallet += 100;
        $referer->save();
        $user->save();

        $stockUser = Stocks::where('status','active')->first();

        if($stockUser){
            if ($stockUser->totalNewMember == 4) {
                $stockUser->status = 'inactive';
                $stocksComplteUser = User::where('id', $stockUser->user_id)->first();
                $stocksComplteUser->shopping_wallet += 500;
                $stocksComplteUser->save();
                $stockUser->save();
            }else{
                $stockUser->totalNewMember +=1;
                $stockUser->save();
            }
        }
        $created = Stocks::create([
            'user_id' => $user->id,
            'amount' => 210,
            'totalNewMember'=> 0,
        ]);
        return response()->json([
            'status' => true,
            'message' => 'Successfully buy a Stock',
            'data' => $created,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
