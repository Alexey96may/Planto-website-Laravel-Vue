<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPaidNotification;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Stripe Webhook Signature Error: ' . $e->getMessage());
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::find($orderId);

                if ($order && $order->status !== 'completed') {
                    $order->update(['status' => 'completed']);

                    try {
                        Mail::to($order->customer_email)->send(new OrderPaidNotification($order));
                        Log::info("Order {$orderId} paid and email sent.");
                    } catch (\Exception $mailEx) {
                        Log::error("Mail error for order {$orderId}: " . $mailEx->getMessage());
                    }
                }
            }
        }

        if ($event->type === 'checkout.session.expired') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id ?? null;

            if ($orderId) {
                $order = Order::with('items')->find($orderId);
                
                if ($order && $order->status === 'pending') {
                    foreach ($order->items as $item) {
                        \App\Models\Product::where('id', $item->product_id)
                            ->increment('stock', $item->quantity);
                    }

                    $order->update(['status' => 'cancelled']);
                    Log::info("Order {$orderId} expired. Stock restored.");
                }
            }
        }
 
        return response()->json(['status' => 'success']);
    }
}
