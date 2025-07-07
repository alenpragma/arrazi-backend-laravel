<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
{
    $query = Order::with('product', 'user')->latest();

    if ($request->has('status') && in_array($request->status, ['pending', 'processing', 'completed', 'cancelled'])) {
        $query->where('status', $request->status);
    }

    $orders = $query->paginate(10);

    return view('admin.pages.orders.index', compact('orders'));
}


    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->status === 'completed') {
            return redirect()->back()->with('error', 'Completed orders cannot be updated.');
        }

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}
