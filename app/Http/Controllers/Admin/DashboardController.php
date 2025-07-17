<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Models\Order;
use App\Models\Stocks;
use App\Models\Wallet;
use App\Models\Deposit;
use App\Models\Product;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Models\WalletHistory;
use App\Models\TransferHistory;
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
        $userCount = User::where('role', '!=', 'admin')->count();
        $activeUserCount = User::where('is_active', 1)->where('role', '!=', 'admin')->count();
        $inactiveUserCount = User::where('is_active', 0)->where('role', '!=', 'admin')->count();
        $dealerCount = User::where('role', 'dealer')->count();
        $depositCount = Deposit::where('status', 'approved')->sum('amount');
        $withdrawCount = Withdraw::where('status', 'approved')->sum('amount');
        $pendingOrderCount = Order::where('status', 'pending')->count();
        $productCount = Product::count();
        $totalStocks = Stocks::count();

        $newUsers = User::where('role', '!=', 'admin')->latest()->take(5)->get();

        $deposits = Deposit::where('status', 'approved')->latest()->take(10)->get()->map(function ($item) {
            return [
                'user' => $item->user,
                'type' => 'Deposit',
                'amount' => $item->amount,
                'description' => 'Deposit to system',
                'created_at' => $item->created_at,
            ];
        });

        $withdraws = Withdraw::where('status', 'approved')->latest()->take(10)->get()->map(function ($item) {
            return [
                'user' => $item->user,
                'type' => 'Withdraw',
                'amount' => $item->amount,
                'description' => 'Withdraw from system',
                'created_at' => $item->created_at,
            ];
        });

        $transfers = TransferHistory::latest()->take(10)->get()->map(function ($item) {
            return [
                'user' => $item->user ?? null,
                'type' => 'Transfer',
                'amount' => $item->amount,
                'description' => "Transfer from {$item->frm} to {$item->to}",
                'created_at' => $item->created_at,
            ];
        });

        $recentTransactions = collect($deposits)
            ->merge($withdraws)
            ->merge($transfers)
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        $data = [
            'userCount' => $userCount,
            'activeUserCount' => $activeUserCount,
            'inactiveUserCount' => $inactiveUserCount,
            'depositCount' =>  $depositCount,
            'withdrawCount' => $withdrawCount,
            'pendingOrderCount' => $pendingOrderCount,
            'productCount' => $productCount,
            'totalStocks' => $totalStocks,
            'totalDealer' => $dealerCount,
            'newUsers' => $newUsers,
            'recentTransactions' => $recentTransactions,
        ];



        return view('admin.dashboard', compact('data'));

    }


}
