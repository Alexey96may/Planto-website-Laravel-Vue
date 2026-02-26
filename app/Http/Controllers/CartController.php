<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;
use App\Models\Product;

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

    public function destroy(Product $product) {
        CartService::remove($product->id);

        return redirect()->back()->with('success', 'Товар удален');
    }

    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        CartService::updateQuantity((int)$productId, $request->quantity);

        return back();
    }
}
