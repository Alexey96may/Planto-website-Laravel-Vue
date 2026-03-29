<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Checkout\Session;

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

                foreach ($items as $item) {
                    $product = \App\Models\Product::lockForUpdate()->find($item['product_id']);

                    if (!$product) {
                        throw new \Exception("Product '{$item['title']}' no longer exists.");
                    }

                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Not enough stock for '{$product->title}'. Available: {$product->stock}.");
                    }

                    $purchasedItems[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->title,
                        'price'        => $product->price,
                        'quantity'     => $item['quantity'],
                    ];

                    $total += $product->price * $item['quantity'];
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
                    'status' => 'pending',
                ]);

                $order->items()->createMany($purchasedItems);
                return $order;

            });

            
            // --- STRIPE LOGIC ---
            $checkoutUrl = route('checkout.success') . '?session_id=local_test_' . uniqid();

            if (config('app.env') !== 'local') {
                \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

                $lineItems = [];
                foreach ($items as $item) {
                    $lineItems[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => ['name' => $item['title']],
                            'unit_amount' => (int) max(50, round($item['price'] * 100)),
                        ],
                        'quantity' => (int) ($item['quantity'] ?? 1),
                    ];
                }

                $lineItems = array_values($lineItems);

                $checkoutSession = \Stripe\Checkout\Session::create([
                    'line_items' => $lineItems,

                    'mode' => 'payment',
                    'success_url' => route('checkout.success', [], true),
                    'cancel_url' => route('checkout.cancel', [], true),
                    'metadata' => [
                        'order_id' => (string) $order->id, 
                    ],
                ]);

                $checkoutUrl = $checkoutSession->url;
            } else {
                $order->update(['status' => 'processing']);
                session()->forget('cart'); 
                Log::info("LOCAL TEST: Order {$order->id} auto-paid.");
            }

            return Inertia::location($checkoutUrl);

        } catch (\Exception $e) {
            Log::error("Checkout error: " . $e->getMessage());
            return redirect()->route('cart.index')
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
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
