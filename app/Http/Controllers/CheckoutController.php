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
        $cartData = \App\Services\CartService::getFullCart();

        return Inertia::render('Checkout/Index', [
            'cartItems' => $cartData['items'],
            'total' => $cartData['total_sum'],
            'cart_ids' => array_keys($cartData['items'])
        ]);
    }
}
