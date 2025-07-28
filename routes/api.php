<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\BalanceTransferController;
use App\Http\Controllers\api\CronController;
use App\Http\Controllers\api\DepositController;
use App\Http\Controllers\api\NetworkController;
use App\Http\Controllers\api\PaymentMethodController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\StockController;
use App\Http\Controllers\api\WithdrawController;
use Illuminate\Support\Facades\Route;


//user auth api route
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
   Route::get('profile', [AuthController::class, 'profile']);
   Route::post('deposit-request', [DepositController::class, 'depositRequest']);
   //products
    Route::get('product', [ProductController::class, 'index']);
    Route::get('product/{slug}', [ProductController::class, 'index']);
    Route::post('buy-product', [ProductController::class, 'buyProducts']);

    //paymentMethod
    Route::get('payment-method', [PaymentMethodController::class, 'index']);

    //history
    Route::get('deposit-history', [DepositController::class, 'depositHistory']);
    Route::get('order-history', [ProductController::class, 'orderHistory']);


    //network
    Route::get('network', [NetworkController::class, 'index']);


    Route::post('transfer-balance', [BalanceTransferController::class, 'transfer']);
    Route::get('transfer-history', [BalanceTransferController::class, 'history']);


    //Stock
    Route::get('buy-stock', [StockController::class,'buyStocks']);
    Route::get('buy-stock/history', [StockController::class,'index']);

    //withdraw
    Route::post('withdraw-request', [WithdrawController::class, 'withdrawRequest']);
    Route::post('withdraw-history', [WithdrawController::class, 'index']);

});

//cron
Route::get('cron', [CronController::class, 'cron']);

Route::post('forget-password-send-mail',[AuthController::class, 'ForgotPasswordSendEmail']);
Route::post('reset-password',[AuthController::class, 'ResetPassword']);
