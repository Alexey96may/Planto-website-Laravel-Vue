<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Orders/Index', [
            'orders' => Order::with('user')->latest()->get()
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return Inertia::render('Admin/Orders/Show', [
            'order' => $order
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);
        return back()->with('success', 'Status has been changed!');
    }
}
