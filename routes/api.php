<?php

use App\Http\Controllers\api\auth\AuthController;
use App\Http\Controllers\DepositController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//user auth api route
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
});

Route::prefix('user')->middleware('auth:sanctum')->group(function () {

   Route::get('profile', [AuthController::class, 'profile']);

   Route::post('deposit-request', [DepositController::class, 'depositRequest']);

});
