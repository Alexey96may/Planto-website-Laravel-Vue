<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\FeatureController;
use App\Http\Controllers\Admin\NavigationController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\CategoryController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');

Route::get('/shop/plant-{product}', [ShopController::class, 'show'])->name('shop.show');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.add');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.remove');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');

Route::post('/newsletter', [NewsletterController::class, 'store'])->name('newsletter.store');

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

    Route::resource('navigation', NavigationController::class);
    Route::post('navigation/reorder', [NavigationController::class, 'reorder'])->name('navigation.reorder');

    Route::post('/newsletter/broadcast', [NewsletterController::class, 'sendBroadcast'])
        ->name('newsletter.broadcast');

    Route::patch('products/{product}/update-trending', [AdminProductController::class, 'updateTrending'])
    ->name('products.update-trending');

    Route::resource('categories', CategoryController::class)->except(['show', 'create']);
});

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');

Route::get('/404', function () {
    return inertia('Error', ['status' => 404]);
});

Route::get('/terms', function () {
    return Inertia::render('Terms');
})->name('terms');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-orders', [OrderController::class, 'index'])->name('orders.user');
    Route::post('/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');

Route::get('/contacts', [ContactController::class, 'index'])->name('contacts.index');

require __DIR__.'/auth.php';
