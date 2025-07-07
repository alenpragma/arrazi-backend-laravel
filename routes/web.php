<?php

use App\Models\Withdraw;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\WithdrawController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepositController;
use App\Http\Controllers\Admin\GeneralSettingsController;

Route::get('/', function () {
    return redirect('/signin');
});

// Login/Logout Routes
Route::get('signin', [AuthController::class, 'index'])->name('admin.login');
Route::post('signin', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->middleware(['auth:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');


        // Users
        Route::resource('users', UserController::class)->names([
            'index' => 'admin.users.index',
            'show' => 'admin.users.show',

        ]);

     // Products
     Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy'
    ]);

        // Orders
    Route::resource('orders', OrderController::class)->names([
        'index' => 'admin.orders.index',
         'show' => 'admin.orders.show',
         'edit' => 'admin.orders.edit',
         'update' => 'admin.orders.update',
    ]);
    Route::put('orders/{id}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Withdraw
    Route::get('/withdraws', [WithdrawController::class, 'index'])->name('admin.withdraw');
    Route::post('/withdraws/{id}/status', [WithdrawController::class, 'updateStatus'])->name('admin.withdraws.updateStatus');
    Route::get('/withdraws/pending', [WithdrawController::class, 'pendingWithdraw'])->name('admin.withdraws.pending');
    Route::get('/withdraws/rejected', [WithdrawController::class, 'rejectWithdraw'])->name('admin.withdraws.rejected');


 // Deposits
 Route::get('/deposits', [DepositController::class, 'index'])->name('admin.deposit');
 Route::post('/deposits/{id}/status', [DepositController::class, 'updateStatus'])->name('admin.deposits.updateStatus');
 Route::get('/deposits/pending', [DepositController::class, 'pendingDeposits'])->name('admin.deposits.pending');
 Route::get('/deposits/rejected', [DepositController::class, 'rejectDeposits'])->name('admin.deposits.rejected');


    // For profile route
    Route::get('/profile', [AuthController::class, 'profileEdit'])->name('admin.profile');
    Route::post('/profile', [AuthController::class, 'profileUpdate'])->name('admin.profile.update');

    // General Settings
    Route::get('general-settings', [GeneralSettingsController::class, 'index'])->name('admin.general.settings');
    Route::post('general-settings', [GeneralSettingsController::class, 'update'])->name('admin.general.settings.update');

});
