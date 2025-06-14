<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\api\DepositController;
use App\Http\Controllers\api\PaymentMethodController;
use App\Http\Controllers\api\ProductController;
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

});
