<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function index()
    {
    // 1. Берем корзину из сессии [ID => Количество]
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        foreach ($cart as $id => $quantity) {
            $product = \App\Models\Product::find($id);
            
            if ($product) {
                $sum = $product->price * $quantity;
                $total += $sum;

                $cartItems[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $quantity,
                    'image' => $product->image,
                    'total_item_price' => $sum,
                ];
            }
        }

        // 3. Отдаем на фронтенд уже готовый массив с ценами и именами
        return Inertia::render('Checkout/Index', [
            'cartItems' => $cartItems,
            'total' => $total,
            'cart_ids' => array_keys($cart)
        ]);
    }
}
