<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\CartItem;

class SyncCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $sessionCart = Session::get('cart', []);
        $user = $event->user;

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $quantity) {
            $item = CartItem::firstOrNew([
                'user_id' => $user->id,
                'product_id' => $productId
            ]);
            $item->quantity += $quantity;
            $item->save();
        }

        Session::forget('cart');
    }
}
