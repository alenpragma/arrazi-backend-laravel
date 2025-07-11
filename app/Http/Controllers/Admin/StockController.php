<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stocks;
use App\Models\User;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $query = Stocks::with('user')->latest();

        if ($request->search) {
            $search = $request->search;

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })->orWhere('status', 'like', "%$search%");
        }

        $stocks = $query->paginate(20)->appends(['search' => $request->search]);

        return view('admin.pages.stocks.index', compact('stocks'));
    }
}
