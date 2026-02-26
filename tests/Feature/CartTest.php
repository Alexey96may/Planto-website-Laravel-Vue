<?php

use App\Models\Product;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/** @var Tests\TestCase $this */

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