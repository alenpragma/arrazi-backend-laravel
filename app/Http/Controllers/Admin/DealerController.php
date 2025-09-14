<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DealerBonusHistory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DealerController extends Controller
{
    public function create()
    {
        return view('admin.pages.users.create_dealer');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'phone' => 'nullable|string',
            'refer_by' => 'nullable|string',
            'password' => 'nullable|string|min:6',
            'position' => 'nullable|string|in:Left,Right',
        ]);

        $dealer = User::where('email', $request->email)->first();

        if ($dealer) {
            if ($dealer->role !== 'dealer') {
                $dealer->update([
                    'role' => 'dealer',
                    'dealer_id' => User::generateDealerId(),
                ]);
            }

            return redirect()->route('admin.dealers.list')
                ->with('success', 'dealer assigned successfully.');
        } else {
            $dealer = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'refer_code' => $request->refer_code,
                'position' => $request->option,
                'role' => 'dealer',
                'dealer_id' => User::generateDealerId(),
                'password' => Hash::make($request->password ?? 'password123'),
            ]);

            return redirect()->route('admin.dealers.list')
                ->with('success', 'New dealer created successfully.');
        }
    }

    public function bonusHistory(Request $request)
    {
        $query = DealerBonusHistory::with('dealer', 'order')->latest();

        if ($request->has('email') && !empty($request->email)) {
            $query->whereHas('dealer', function($q) use ($request) {
                $q->where('email', 'like', '%' . $request->email . '%');
            });
        }

        $histories = $query->paginate(15)->withQueryString();

        return view('admin.pages.users.dealers_bonus_history', compact('histories'));
    }

}
