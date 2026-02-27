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
                ->with('error', 'В вашей корзине нет доступных для заказа товаров.');
        }
        
        $totalSum = array_sum(array_column($validItems, 'total_price'));

        return Inertia::render('Checkout/Index', [
            'cartItems' => $validItems,
            'total' => $totalSum,
        ]);
    }
}
