<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('role', '!=', 'admin')->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })->latest()->paginate(15);
        return view('admin.pages.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['left', 'right', 'upline', 'referer', 'referrals'])->findOrFail($id);
        $network = $user->loadNetwork();
        $totalLeftPoints = $user->getTotalLeftPoints();
        $totalRightPoints = $user->getTotalRightPoints();
        $downlines = $user->getDownlineUsersLimited();

        return view('admin.pages.users.show', compact(
            'user', 'network', 'totalLeftPoints', 'totalRightPoints', 'downlines'
        ));
    }
      public function dealerList(Request $request)
    {
        $search = $request->input('search');

        $dealers = User::where('role', 'dealer')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);

        return view('admin.pages.users.dealer_list', compact('dealers', 'search'));
    }

    public function showDealer($id)
    {
        $user = User::with(['referer', 'upline', 'left', 'right'])->findOrFail($id);

        $totalLeftPoints = $user->getTotalLeftPoints();
        $totalRightPoints = $user->getTotalRightPoints();
        $downlines = $user->getDownlineUsersLimited();

        return view('admin.pages.users.dealer_show', compact(
            'user',
            'totalLeftPoints',
            'totalRightPoints',
            'downlines'
        ));
    }
}