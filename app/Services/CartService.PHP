<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    public static function getItems()
    {
        if (Auth::check()) {
            return CartItem::where('user_id', Auth::id())->with('product')->get();
        }
        return collect(Session::get('cart', []));
    }

    public static function add(int $productId, int $quantity = 1): void
    {
        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);

            $cartItem->quantity = ($cartItem->quantity ?? 0) + $quantity;
            $cartItem->save();
        } else {
            $cart = Session::get('cart', []);
            $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
            Session::put('cart', $cart);
        }
    }

    public static function getCount(): int
    {
        if (Auth::check()) {
            return (int) CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        $sessionCart = session()->get('cart', []);
        return (int) array_sum($sessionCart);
    }

    public static function getIds(): array
    {
        if (Auth::check()) {
            return \App\Models\CartItem::where('user_id', Auth::id())
                ->pluck('product_id')
                ->toArray();
        }

        return array_keys(session()->get('cart', []));
    }
    
    // app/Services/CartService.php

    public static function getFullCart(): array
    {
        $items = [];
        $totalSum = 0;

        if (Auth::check()) {
            // Берем из БД со связью 'product'
            $cartData = \App\Models\CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cartData as $item) {
                if (!$item->product) continue; // На случай если товар удален
                
                $sum = $item->product->price * $item->quantity;
                $items[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'title' => $item->product->title,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'total_price' => $sum,
                    'image' => $item->product->image_url, // Твое поле с картинкой
                ];
                $totalSum += $sum;
            }
        } else {
            // Берем из сессии
            $sessionCart = session()->get('cart', []); // [id => qty, id => qty]
            if (!empty($sessionCart)) {
                $products = \App\Models\Product::whereIn('id', array_keys($sessionCart))->get();
                
                foreach ($products as $product) {
                    $qty = $sessionCart[$product->id];
                    $sum = $product->price * $qty;
                    $items[] = [
                        'id' => null, // Для сессии ID записи нет
                        'product_id' => $product->id,
                        'title' => $product->title,
                        'price' => $product->price,
                        'quantity' => $qty,
                        'total_price' => $sum,
                        'image' => $product->image_url,
                    ];
                    $totalSum += $sum;
                }
            }
        }

        return [
            'items' => $items,
            'total_sum' => $totalSum
        ];
    }

    public static function remove(int $productId): void
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            $cart = Session::get('cart', []);

            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                Session::put('cart', $cart);
            }
        }
    }

    public static function updateQuantity(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            \App\Models\CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->update(['quantity' => $quantity]);
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                $cart[$productId] = $quantity;
                session()->put('cart', $cart);
            }
        }
    }

    public static function getIdsWithQuantities()
    {
        $items = self::getItems();
        return collect($items)->pluck('quantity', 'product_id')->toArray();
    }
}