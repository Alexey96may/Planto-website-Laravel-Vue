<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/shop/plant-{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', function () {
    $cartIds = session('cart', []);
    $cartProducts = Product::whereIn('id', $cartIds)->get();
    $totalPrice = $cartProducts->sum('price');

    return Inertia::render('CartPage', [
        'cartItems' => $cartProducts,
        'total' => $totalPrice
    ]);
})->name('cart.index');


Route::post('/cart/add', function (Request $request) {
    $productId = $request->input('product_id');

    $cart = session()->get('cart', []);

    if (!in_array($productId, $cart)) {
        $cart[] = $productId;
        session()->put('cart', $cart);
    }

    return back();
})->name('cart.add');

Route::post('/cart/remove', function (Request $request) {
    $productId = $request->input('product_id');
    $cart = session()->get('cart', []);

    if (($key = array_search($productId, $cart)) !== false) {
        unset($cart[$key]);
    }

    session()->put('cart', array_values($cart));

    return back();
})->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('index');

    Route::resource('products', AdminProductController::class);
});

require __DIR__.'/auth.php';
