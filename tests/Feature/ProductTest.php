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

it('adjusts quantity if user tries to buy more than available in stock', function () {
    $product = Product::factory()->create(['stock' => 3, 'price' => 100]);
    
    // Имитируем корзину, где товара больше, чем на складе
    session(['cart' => [$product->id => 10]]);

    $response = $this->post(route('order.store'), [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '123456789',
        'address' => 'Test Street 1',
    ]);

    // Проверяем, что заказ создался
    $this->assertDatabaseCount('orders', 1);
    
    // Проверяем, что купили только 3 шт (весь остаток), а не 10
    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'quantity' => 3,
    ]);

    // Проверяем, что на складе теперь 0
    expect($product->refresh()->stock)->toBe(0);
});

it('prevents selling more than stock during concurrent requests', function () {
    $product = Product::factory()->create(['stock' => 1]);
    
    // Юзер А заходит в транзакцию
    DB::beginTransaction();
    
    // Блокируем товар (имитируем первый процесс)
    $p1 = Product::where('id', $product->id)->lockForUpdate()->first();
    
    // Пытаемся запустить второй процесс (эмуляция другого юзера)
    // В реальной жизни второй процесс будет ЖДАТЬ, пока первый не сделает commit или rollback
    // Здесь мы просто проверяем, что если остаток стал 0, второй не пролезет
    
    $p1->decrement('stock', 1);
    DB::commit();
    
    // Теперь имитируем запрос второго юзера, который думал, что товар еще есть
    session(['cart' => [$product->id => 1]]);
    
    $response = $this->post(route('order.store'), [
        'name' => 'User B',
        'email' => 'userb@example.com',
        'phone' => '987654321',
        'address' => 'Street 2',
    ]);

    // Второй юзер должен получить ошибку, так как сток уже 0
    $response->assertSessionHas('error');
    // Или, если у нас логика "пропускать", заказ не должен содержать этот товар
});