<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\CartService;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{

    public function index()
    {
        $validItems = CartService::getValidItemsForOrder();

        if (empty($validItems)) {
            return redirect()->route('cart.index')
                ->with('error', 'There are no items available for order in your cart');
        }
        
        $totalSum = array_sum(array_column($validItems, 'total_price'));

        return Inertia::render('Checkout/Index', [
            'cartItems' => $validItems,
            'total' => $totalSum,
            'seo' => $this->seo(
                title: 'Checkout | Secure Payment',
                description: 'Complete your purchase securely at Planto Store.',
                robots: 'noindex, nofollow'
            )
        ]);
    }
}
