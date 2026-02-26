<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->with('items')->latest()->get();

        return Inertia::render('Orders/UserOrders', [
            'orders' => $orders
        ]);
    }

    public function store(Request $request)
    {
        $cartData = \App\Services\CartService::getFullCart();
        $items = $cartData['items'];

        if (empty($items)) {
            return redirect()->back()->with('error', 'Корзина пуста!');
        }

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|min:5',
        ]);

        $user = Auth::user();

        try {
            $order = DB::transaction(function () use ($request, $items, $user) {
                $total = 0;
                $purchasedItems = [];
                $purchasedProductIds = [];

                foreach ($items as $item) {
                    $product = \App\Models\Product::lockForUpdate()->find($item['product_id']);

                    if (!$product || $product->stock <= 0) {
                        continue;
                    }

                    $quantityToBuy = min($item['quantity'], $product->stock);
                    $sum = $product->price * $quantityToBuy;

                    $purchasedItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->title,
                        'price'        => $product->price,
                        'quantity'     => $quantityToBuy,
                    ];

                    $total += $sum;
                    $purchasedProductIds[] = $product->id;

                    $product->decrement('stock', $quantityToBuy);
                }

                if (empty($purchasedItems)) {
                    throw new \Exception('Извините, все товары из вашей корзины только что закончились.');
                }

                $order = \App\Models\Order::create([
                    'user_id' => $user?->id ?? Auth::id(),
                    'total_price' => $total,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'delivery_address' => $request->address,
                    'comment' => $request->comment,
                    'status' => 'new',
                ]);

                $order->items()->createMany($purchasedItems);

                if (Auth::check()) {
                    \App\Models\CartItem::where('user_id', Auth::id())
                        ->whereIn('product_id', $purchasedProductIds)
                        ->delete();
                } else {
                    $sessionCart = session()->get('cart', []);
                    foreach ($purchasedProductIds as $id) {
                        unset($sessionCart[$id]);
                    }
                    session()->put('cart', $sessionCart);
                }

                return $order;
            });

            return to_route('home')->with('success', 'Заказ №' . $order->id . ' оформлен!');

        } catch (\Exception $e) {
            Log::error("Checkout error: " . $e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
