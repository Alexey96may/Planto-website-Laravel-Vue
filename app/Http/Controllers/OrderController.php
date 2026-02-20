<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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
        // dd($request->all());
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Корзина пуста!');
        }

        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|min:5',
        ]);

        $user = Auth::user();

        if (!$user && $request->create_account) {
            $user = User::where('email', $request->email)->first();

            if (!$user) {
                $password = Str::random(10);
                
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($password),
                ]);

                Auth::login($user);

                // TODO: Позже здесь будет отправка письма с паролем $password
            }
        }

        try {
            DB::transaction(function () use ($request, $cart, $user) {
                $total = 0;
                $itemsToSave = [];

                // Теперь $id — это реально ID, а $quantity — реально количество
                foreach ($cart as $id => $quantity) {
                    $product = \App\Models\Product::findOrFail($id);
                    
                    $price = $product->price;
                    $total += $price * $quantity;

                    $itemsToSave[] = [
                        'product_id'   => $product->id,
                        'product_name' => $product->title,
                        'price'        => $price,
                        'quantity'     => $quantity,
                    ];
                }

                // Создаем основной заказ
                $order = \App\Models\Order::create([
                    'user_id' => $user ? $user->id : Auth::id(),
                    'total_price' => $total,
                    'customer_name' => $request->name,
                    'customer_email' => $request->email,
                    'customer_phone' => $request->phone,
                    'delivery_address' => $request->address,
                    'comment' => $request->comment,
                    'status' => 'new',
                ]);

                // Массово создаем позиции
                foreach ($itemsToSave as $item) {
                    $order->items()->create($item);
                }

                // Очищаем корзину ТОЛЬКО внутри успешной транзакции
                session()->forget('cart');
            });

        if (Auth::check()) {
            return to_route('dashboard')->with('success', 'Заказ оформлен! Добро пожаловать в личный кабинет.');
        }

        return to_route('home')->with('success', 'Заказ успешно оформлен! Наш менеджер свяжется с вами.');

        } catch (\Exception $e) {
            Log::error('Error during order creation: ' . $e->getMessage(), [
                'user_email' => $request->email,
                'cart' => session()->get('cart'),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Ошибка при создании заказа: ' . $e->getMessage());
        }
    }
}
