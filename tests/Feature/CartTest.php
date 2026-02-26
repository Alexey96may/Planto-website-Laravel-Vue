<?php

use App\Models\Product;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/** @var Tests\TestCase $this */

beforeEach(function () {
    // Очищаем сессию перед каждым тестом
    session()->forget('cart');
});

it('adds the item to the authorized users cart (in the db) and checks the total amount.', function () {
    $this->withoutExceptionHandling();
    $user = User::factory()->create();
    $this->actingAs($user);

    $product1 = Product::factory()->create(['price' => 1000]);
    $product2 = Product::factory()->create(['price' => 2500]);

    $this->post(route('cart.add'), ['product_id' => $product1->id]);
    $this->post(route('cart.add'), ['product_id' => $product2->id]);

    $response = $this->get(route('cart.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
            ->component('Cart/Index')
            ->where('cart.total_sum', 3500)
            ->has('cart.items', 2)
        );
    
    $this->assertDatabaseHas('cart_items', [
        'user_id' => $user->id,
        'product_id' => $product1->id,
        'quantity' => 1,
    ]);
});

it('adds a product to the GUESTs cart (in the session)', function () {
    $product = Product::factory()->create();

    $this->post(route('cart.add'), ['product_id' => $product->id]);

    expect(session('cart'))->toHaveCount(1);
    expect(session('cart')[$product->id])->toBe(1);
});

it('updates the product quantity in the cart', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 1000]);
    
    $this->actingAs($user)->post(route('cart.add'), ['product_id' => $product.id, 'quantity' => 1]);
    $this->patch(route('cart.update', $product), ['quantity' => 5]);

    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 5
    ]);
});

it("removes a product from the user's cart", function () {
    // 1. Подготовка: Создаем юзера и товар в его корзине
    $user = \App\Models\User::factory()->create();
    $product = \App\Models\Product::factory()->create();
    
    // Напрямую создаем запись в таблице корзины (имитируем, что товар уже там)
    \App\Models\CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    // Проверяем, что в базе сейчас 1 запись
    $this->assertDatabaseCount('cart_items', 1);

    // 2. Действие: Вызываем роут удаления
    // Убедись, что имя роута 'cart.destroy' совпадает с твоим в web.php
    $response = $this->actingAs($user)
        ->delete(route('cart.remove', $product));

    // 3. Проверки
    // Обычно после удаления идет редирект обратно в корзину
    $response->assertRedirect(route('cart.index'));
    
    // В базе должно стать 0 записей
    $this->assertDatabaseCount('cart_items', 0);
    
    // Опционально: проверить, что в сессии появилось сообщение об успехе
    $response->assertSessionHas('success');
});

it("removes a product from the guest's cart session", function () {
    $product = \App\Models\Product::factory()->create();

    // Имитируем корзину в сессии
    session(['cart' => [$product->id => 1]]);

    // Удаляем
    $this->delete(route('cart.remove', $product));

    // Проверяем, что в ключе 'cart' больше нет этого ID
    expect(session('cart'))->not->toHaveKey($product->id);
});

it('prevents adding products out of stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);

    $response = $this->actingAs($user)
        ->post(route('cart.add'), [
            'product_id' => $product->id,
            'quantity' => 10
        ]);

    $response->assertSessionHasErrors(['quantity' => 'Недостаточно товара']);
    $this->assertDatabaseCount('cart_items', 0);
});

/**
 * Тест 1: Пустая корзина после фильтрации по наличию
 */
it('fails to create an order if all products in cart are out of stock', function () {
    // Создаем товар, которого нет на складе
    $product = Product::factory()->create(['stock' => 0, 'title' => 'Missing Plant']);
    
    // Кладем его в сессию
    session(['cart' => [$product->id => 1]]);

    $response = $this->post(route('order.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address 123',
    ]);

    // Проверяем, что нас вернули назад с ошибкой
    $response->assertSessionHas('error', 'Извините, все товары из вашей корзины только что закончились.');
    
    // Проверяем, что заказ в базе НЕ появился
    $this->assertDatabaseCount('orders', 0);
});

/**
 * Тест 2: Очистка сессии (частичная)
 */
it('clears only purchased items from session and keeps out-of-stock items', function () {
    // 1. Товар в наличии
    $availableProduct = Product::factory()->create(['stock' => 5, 'title' => 'In Stock']);
    // 2. Товар закончился
    $outOfStockProduct = Product::factory()->create(['stock' => 0, 'title' => 'Out of Stock']);

    // Кладем оба в корзину
    session(['cart' => [
        $availableProduct->id => 2,
        $outOfStockProduct->id => 1
    ]]);

    $response = $this->post(route('order.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address 123',
    ]);

    // Проверяем успех
    $response->assertSessionHas('success');

    // ПРОВЕРКА СЕССИИ:
    $currentCart = session('cart');

    // Купленного товара НЕ должно быть в сессии
    expect($currentCart)->not->toHaveKey($availableProduct->id);
    
    // Товар, который не купили (был out of stock), ДОЛЖЕН остаться
    expect($currentCart)->toHaveKey($outOfStockProduct->id);
    expect($currentCart[$outOfStockProduct->id])->toBe(1);

    // Проверяем, что в заказе только 1 позиция
    $this->assertDatabaseCount('order_items', 1);
    $this->assertDatabaseHas('order_items', [
        'product_id' => $availableProduct->id,
        'quantity' => 2
    ]);
});

/**
 * Тест 3: Проверка списания остатков (Decrement)
 */
it('decrements product stock after successful order', function () {
    $product = Product::factory()->create(['stock' => 10]);
    session(['cart' => [$product->id => 4]]);

    $this->post(route('order.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address',
    ]);

    // Проверяем, что из 10 осталось 6
    expect($product->refresh()->stock)->toBe(6);
});