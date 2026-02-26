<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

/** @var Tests\TestCase $this */

beforeEach(function () {
    session()->forget('cart');
});

it('shows only in-stock products by default', function () {
    Product::factory()->create(['title' => 'Available Plant', 'stock' => 5]);
    Product::factory()->create(['title' => 'Sold Out Plant', 'stock' => 0]);

    $response = $this->get(route('shop'));

    $response->assertInertia(fn ($page) => $page
        ->has('products.data', 1)
        ->where('products.data.0.title', 'Available Plant')
    );
});

it('shows all products when in_stock filter is disabled', function () {
    Product::factory()->create(['stock' => 5]);
    Product::factory()->create(['stock' => 0]);

    $response = $this->get(route('shop', ['in_stock' => '0'])); 

    $response->assertInertia(fn ($page) => $page
        ->has('products.data', 2)
    );
});

it('prevents selling more than stock during concurrent requests', function () {
    $product = Product::factory()->create(['stock' => 1]);
    
    DB::beginTransaction();
    $p1 = Product::where('id', $product->id)->lockForUpdate()->first();
    $p1->decrement('stock', 1);
    DB::commit();
    
    session(['cart' => [
            $product->id => 1
    ]]);
    
    $response = $this->post(route('checkout.store'), [
        'name' => 'User B',
        'email' => 'userb@example.com',
        'phone' => '987654321',
        'address' => 'Street 2',
    ]);

    $response->assertSessionHasErrors(['quantity']);
    $this->assertDatabaseCount('orders', 0);
});