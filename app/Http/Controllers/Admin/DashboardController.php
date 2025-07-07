<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    // protected $withdrawController;

    // public function __construct(WithdrawController $withdrawController)
    // {
    //     $this->withdrawController = $withdrawController;
    // }

    public function index()
    {
        // $users = User::latest()->take(10)->get();

        // $totalUsers = User::count();
        // $activeUsers = User::where('is_active', true)->count();
        // $inactiveUsers = User::where('is_active', false)->count();

        // $totalDeposits = WalletHistory::where('transaction_type', 'deposit')->sum('amount');

        // $totalWithdrawals = $this->withdrawController->totalWithdrawals();

        // $totalBonusPoints = Wallet::sum('reward_point');

        // $recentTransactions = WalletHistory::latest()->limit(10)->get();


        // $dashboardData = [
        //     'totalUsers' => $totalUsers,
        //     'activeUsers' => $activeUsers,
        //     'inactiveUsers' => $inactiveUsers,
        //     'users' => $users,
        //     'recentTransactions' => $recentTransactions,
        //     'totalDeposits' => $totalDeposits,
        //     'totalWithdrawals' => $totalWithdrawals,
        //     'totalBonusPoints' => $totalBonusPoints,
        // ];

        return view('admin.dashboard' );
    }
    public function dashboard()
{

}
}
