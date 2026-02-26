<?php

use App\Models\Product;

/** @var Tests\TestCase $this */

it('shows only in-stock products by default', function () {
    // Создаем один товар в наличии и один "распроданный"
    Product::factory()->create(['name' => 'Available Plant', 'stock' => 5]);
    Product::factory()->create(['name' => 'Sold Out Plant', 'stock' => 0]);

    $response = $this->get(route('shop')); // По умолчанию in_stock = 1

    $response->assertInertia(fn ($page) => $page
        ->has('products', 1)
        ->where('products.0.name', 'Available Plant')
    );
});

it('shows all products when in_stock filter is disabled', function () {
    Product::factory()->create(['stock' => 5]);
    Product::factory()->create(['stock' => 0]);

    $response = $this->get(route('shop', ['in_stock' => '0']));

    $response->assertInertia(fn ($page) => $page->has('products', 2));
});