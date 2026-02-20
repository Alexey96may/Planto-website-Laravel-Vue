<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusChanged;
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
        
        $oldStatus = $order->status;
        $order->update($request->only('status'));

        if ($oldStatus !== $order->status) {
            if ($order->user) {
                $order->user->notify(new OrderStatusChanged($order));
            } else {
                \Illuminate\Support\Facades\Notification::route('mail', $order->customer_email)
                    ->notify(new OrderStatusChanged($order));
            }
        }

        return back()->with('success', 'The status has been changed!');
    }
}
