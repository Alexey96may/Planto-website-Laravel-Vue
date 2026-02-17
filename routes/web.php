<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'products'=> Product::all(),
        'canLogin' => Route::has('login'),
        'topPlants' => Product::latest()->take(4)->get(),
        'canRegister' => Route::has('register'),
        'storeName' => 'Planto',
        'status' => 'Сегодня работаем до 22:00',
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/shop/plant-{product}', function (Product $product) {
    return Inertia::render('Product/PlantPage', [
        'plant' => $product,
    ]);
});

Route::post('/cart/add', function (Request $request) {
    $productId = $request->input('product_id');

    $cart = session()->get('cart', []);

    if (!in_array($productId, $cart)) {
        $cart[] = $productId;
        session()->put('cart', $cart);
    }

    return back();
})->name('cart.add');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
