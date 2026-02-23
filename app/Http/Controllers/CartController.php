<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Cart/Index', [
            'cart' => CartService::getFullCart()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        CartService::add(
            $validated['product_id'], 
            $validated['quantity'] ?? 1
        );

        return back()->with('success', 'Товар добавлен в корзину');
    }
}
