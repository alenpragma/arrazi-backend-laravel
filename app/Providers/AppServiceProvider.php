<?php

namespace App\Providers;

use App\Models\Deposit;
use App\Models\Withdraw;
use App\Models\GeneralSetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (Schema::hasTable('general_settings')) {
            $generalSettings = GeneralSetting::first();
            View::share('generalSettings', $generalSettings);
        }

        View::composer('*', function ($view) {
            if (Schema::hasTable('withdraws')) {
                $pendingWithdrawCount = Withdraw::where('status', 'pending')->count();
                $view->with('pendingWithdrawCount', $pendingWithdrawCount);
            }
        });
        View::composer('*', function ($view) {
            if (Schema::hasTable('deposits')) {
                $pendingDepositCount = Deposit::where('status', 'pending')->count();
                $view->with('pendingDepositCount', $pendingDepositCount);
            }
        });


    }
}
