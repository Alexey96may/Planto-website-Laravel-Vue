<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

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

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            throw ValidationException::withMessages(['quantity' => 'Недостаточно товара']);
        }

        if (Auth::check()) {
            $cartItem = CartItem::firstOrNew([
                'user_id' => Auth::id(),
                'product_id' => $productId,
            ]);

            $newQuantity = ($cartItem->quantity ?? 0) + $quantity;

            if ($product->stock < $newQuantity) {
                throw ValidationException::withMessages(['quantity' => 'Недостаточно товара']);
            }

            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            $cart = Session::get('cart', []);
            $currentInCart = $cart[$productId] ?? 0;

            if ($product->stock < ($currentInCart + $quantity)) {
                throw ValidationException::withMessages(['quantity' => 'Недостаточно товара']);
            }

            $cart[$productId] = $currentInCart + $quantity;
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

    public static function getFullCart(): array
    {
        $items = [];
        $totalSum = 0;

        if (Auth::check()) {
            $cartData = \App\Models\CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cartData as $item) {
                if (!$item->product) continue;
                
                $formatted = self::formatCartItem($item->product, $item->quantity, $item->id);
                $items[] = $formatted;
                $totalSum += $formatted['total_price'];
            }
        } else {
            $sessionCart = session()->get('cart', []);
            if (!empty($sessionCart)) {
                $products = \App\Models\Product::whereIn('id', array_keys($sessionCart))->get();
                
                foreach ($products as $product) {
                    $qty = $sessionCart[$product->id];
                    $formatted = self::formatCartItem($product, $qty);
                    $items[] = $formatted;
                    $totalSum += $formatted['total_price'];
                }
            }
        }

        return [
            'items'     => $items,
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

    public static function getValidItemsForOrder(): array
    {
        $fullCart = self::getFullCart();
        
        return array_filter($fullCart['items'], function ($item) {
            return $item['is_available'] === true;
        });
    }

    public static function getIdsWithQuantities()
    {
        $items = self::getItems();
        return collect($items)->pluck('quantity', 'product_id')->toArray();
    }

    protected static function formatCartItem($product, $quantity, $cartItemId = null): array
    {
        $sum = $product->price * $quantity;

        return [
            'id'           => $cartItemId,
            'product_id'   => $product->id,
            'title'        => $product->title,
            'price'        => $product->price,
            'quantity'     => $quantity,
            'total_price'  => $sum,
            'image'        => $product->image_url,
            'stock'        => $product->stock,
            'is_available' => $product->stock >= $quantity,
        ];
    }
}