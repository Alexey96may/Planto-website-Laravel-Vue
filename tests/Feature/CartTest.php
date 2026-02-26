<?php

use App\Models\Product;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

/** @var Tests\TestCase $this */

beforeEach(function () {
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
    
    $this->actingAs($user)->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);
    $this->patch(route('cart.update', $product->id), ['quantity' => 5]);

    $this->assertDatabaseHas('cart_items', [
        'product_id' => $product->id,
        'quantity' => 5
    ]);
});

it("removes a product from the user's cart", function () {
    $user = \App\Models\User::factory()->create();
    $product = \App\Models\Product::factory()->create();
    
    \App\Models\CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $this->assertDatabaseCount('cart_items', 1);

    $response = $this->actingAs($user)
        ->from(route('cart.index'))
        ->delete(route('cart.remove', $product));

    $response->assertRedirect(route('cart.index'));
    
    $this->assertDatabaseCount('cart_items', 0);
    
    $response->assertSessionHas('success');
});

it("removes a product from the guest's cart session", function () {
    $product = \App\Models\Product::factory()->create();

    session(['cart' => [$product->id => 1]]);

    $this->delete(route('cart.remove', $product));

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

it('fails to create an order if all products in cart are out of stock', function () {
    $product = Product::factory()->create(['stock' => 0, 'title' => 'Missing Plant']);
    
    session(['cart' => [$product->id => 1]]);

    $response = $this->post(route('checkout.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address 123',
    ]);

    $response->assertSessionHasErrors(['quantity']);
    
    $this->assertDatabaseCount('orders', 0);
});

it('fails to checkout if any item in cart is out of stock', function () {
    $availableProduct = Product::factory()->create(['stock' => 5]);
    $outOfStockProduct = Product::factory()->create(['stock' => 0]);

    session(['cart' => [
        $availableProduct->id => 2,
        $outOfStockProduct->id => 1
    ]]);

    $response = $this->post(route('checkout.store'), [
        'name' => 'Test User', 'email' => 'test@example.com',
        'phone' => '123456789', 'address' => 'Test Address 123',
    ]);

    $response->assertSessionHasErrors(['quantity']);
    
    $this->assertDatabaseCount('orders', 0);
    
    expect(session('cart'))->toHaveKey($availableProduct->id);
    expect(session('cart'))->toHaveKey($outOfStockProduct->id);
});

it('decrements product stock after successful order', function () {
    $product = Product::factory()->create(['stock' => 10]);
    session(['cart' => [$product->id => 4]]);

    $this->post(route('checkout.store'), [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address',
    ]);

    expect($product->refresh()->stock)->toBe(6);
});

it('uses current product price from database during checkout', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['price' => 1000, 'stock' => 10]);

    $this->actingAs($user)->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

    $product->update(['price' => 1500]);

    $this->post(route('checkout.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address 123',
    ]);

    $this->assertDatabaseHas('order_items', [
        'product_id' => $product->id,
        'price' => 1500
    ]);
});

it('fails to checkout and notifies user if quantity exceeds stock', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create(['stock' => 3]);

    \App\Models\CartItem::create([
        'user_id' => $user->id,
        'product_id' => $product->id,
        'quantity' => 10,
    ]);

    $response = $this->actingAs($user)->post(route('checkout.store'), [
        'name' => 'John Doe',
        'email' => 'test@example.com',
        'phone' => '123456789',
        'address' => 'Test Address 123',
    ]);

    $response->assertSessionHasErrors(['quantity']);
    $this->assertDatabaseCount('orders', 0);
});

it('ignores or removes products from cart that no longer exist in database', function () {
    $user = User::factory()->create();
    $product = Product::factory()->create();

    $this->actingAs($user)->post(route('cart.add'), ['product_id' => $product->id, 'quantity' => 1]);

    $product->delete();

    $response = $this->get(route('cart.index'));

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page->has('cart.items', 0));
});

it('prevents overselling using pessimistic locking', function () {
    $product = Product::factory()->create(['stock' => 1]);

    DB::beginTransaction();
    $lockedProduct = Product::where('id', $product->id)->lockForUpdate()->first();

    $lockedProduct->decrement('stock', 1);
    DB::commit();

    $this->assertEquals(0, $product->refresh()->stock);
});