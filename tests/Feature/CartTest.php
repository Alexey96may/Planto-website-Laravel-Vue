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