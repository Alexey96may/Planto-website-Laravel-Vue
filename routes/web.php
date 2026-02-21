<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\AdminController;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\FeatureController;

//todo 'topPlants' => Product::latest()->take(4)->get(),
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
        'comments' => Comment::with('user:id,name,avatar')
            ->where('is_active', true)
            ->latest()
            ->limit(3) //todo
            ->get(),
    ]);
})->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/shop/plant-{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', function () {
    $cart = session('cart', []);
    
    $ids = array_keys($cart);
    
    $products = Product::whereIn('id', $ids)->get();

    $totalPrice = 0;
    $cartItems = [];

    foreach ($products as $product) {
        $quantity = $cart[$product->id];
        $subtotal = $product->price * $quantity;
        $totalPrice += $subtotal;

        $cartItems[] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->image,
            'quantity' => $quantity,
            'total_item_price' => $subtotal,
        ];
    }

    return Inertia::render('CartPage', [
        'cartItems' => $cartItems, // Теперь это массив объектов с кол-вом
        'total' => $totalPrice
    ]);
})->name('cart.index');


Route::post('/cart/add', function (Request $request) {
    $id = $request->input('id');
    
    if (!$id) {
        return redirect()->back()->with('error', 'Товар не найден');
    }

    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]++;
    } else {
        $cart[$id] = 1;
    }

    session()->put('cart', $cart);

    return redirect()->back()->with('success', 'Товар добавлен в корзину!');
})->name('cart.add');

Route::post('/cart/remove', function (Request $request) {
    $productId = $request->input('product_id');
    
    $cart = session()->get('cart', []);

    if (isset($cart[$productId])) {
        unset($cart[$productId]);
    }

    session()->put('cart', $cart);

    return back()->with('success', 'Товар удален из корзины');
})->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

    Route::resource('products', AdminProductController::class);
        
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.updateRole');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update');

    Route::get('/comments', [AdminCommentController::class, 'index'])->name('comments.index');
    Route::patch('/comments/{comment}', [AdminCommentController::class, 'update'])->name('comments.update');
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');

    Route::resource('features', FeatureController::class);
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.user');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/debug-reset', function() {
    session()->forget('cart');
    return "Корзина полностью очищена. Начнем с чистого листа.";
});

require __DIR__.'/auth.php';
