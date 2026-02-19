<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Корзина пуста!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string|min:5',
        ]);

        try {
            DB::transaction(function () use ($request, $cart) {
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
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'customer_name' => $request->name,
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

            return to_route('dashboard')->with('success', 'Заказ успешно оформлен!');

        } catch (\Exception $e) {
            // Если база выдаст ту самую ошибку "Integrity constraint", мы её поймаем
            dd($e->getMessage(), $e->getLine(), $e->getFile());
            return redirect()->back()->with('error', 'Ошибка при создании заказа: ' . $e->getMessage());
        }
    }
}
