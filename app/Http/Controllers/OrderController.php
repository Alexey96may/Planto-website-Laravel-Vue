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
        $user = Auth::user();
        $orders = $user->orders()->with('items.product')->latest()->get();

        return Inertia::render('Orders/UserOrders', [
            'orders' => $orders,
            'seo' => $this->seo(
                title: 'Order History',
                description: 'Track your plant deliveries and view past purchases.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function store(Request $request)
    {
        $cartData = \App\Services\CartService::getFullCart();
        $items = $cartData['items'];

        if (empty($items)) {
            return redirect()->back()->withErrors(['cart' => 'Your cart is empty!']);
        }

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|min:5',
        ]);

        try {
            $order = DB::transaction(function () use ($request, $items) {
                $total = 0;
                $purchasedItems = [];
                $purchasedProductIds = [];

                foreach ($items as $item) {
                    $product = \App\Models\Product::lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new \Exception("The item in your cart no longer exists.");
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Not enough product '{$product->title}' in stock (5 pieces {$product->stock} in stock).");
                    }

                    $sum = $product->price * $item['quantity'];

                    $purchasedItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->title,
                        'price'        => $product->price,
                        'quantity'     => $item['quantity'],
                    ];

                    $total += $sum;
                    $purchasedProductIds[] = $product->id;

                    $product->decrement('stock', $item['quantity']);
                }

                $order = \App\Models\Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'delivery_address' => $request->address,
                    'comment' => $request->comment,
                    'status' => 'new',
                ]);

                $order->items()->createMany($purchasedItems);

                $this->clearCart($purchasedProductIds);

                return $order;
            });

            return to_route('home')->with('success', 'The order has been placed!');

        } catch (\Exception $e) {
            Log::error("Checkout error: " . $e->getMessage());
            return to_route('cart.index')->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }

    protected function clearCart(array $productIds)
    {
        if (Auth::check()) {
            \App\Models\CartItem::where('user_id', Auth::id())
                ->whereIn('product_id', $productIds)
                ->delete();
        } else {
            $sessionCart = session()->get('cart', []);
            foreach ($productIds as $id) {
                unset($sessionCart[$id]);
            }
            session()->put('cart', $sessionCart);
        }
    }
}
