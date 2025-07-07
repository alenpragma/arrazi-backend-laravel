<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'admin')->latest()->paginate(15);
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
}