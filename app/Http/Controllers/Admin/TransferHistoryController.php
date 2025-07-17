<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TransferHistory;
use App\Models\User;
use Illuminate\Http\Request;

class TransferHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = TransferHistory::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }

        $transfers = TransferHistory::with(['user'])->latest()->paginate(10);

        return view('admin.pages.transfers.index', compact('transfers'));
    }
}
