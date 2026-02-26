<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
use App\Services\SettingService;
use App\Services\CommentService;
use App\Services\FeatureService;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(ProductService $products, SettingService $settings): Response
    {
        return Inertia::render('Welcome', [
            'products'     => Product::all(),
            'topPlants'    => $products->getTopProducts(
                $settings->get('top_plants_limit', 4), 
                $settings->get('top_days_interval', 0)
            ),
            'trendyPlants' => $products->getTrendingProducts(
                $settings->get('trendy_limit', 2)
            ),
            'heroPlants'   => Product::with('category')
                ->where('stock', '>', 0)
                ->latest()
                ->take($settings->get('hero_plants_limit', 3))
                ->get(),
            
            'comments'     => CommentService::getLatestActive(),
            'features'     => FeatureService::getActive(),

            'canLogin'       => Route::has('login'),
            'canRegister'    => Route::has('register'),
            'storeName'      => 'Planto',
            'status'         => 'Сегодня работаем до 22:00', //todo
            'laravelVersion' => Application::VERSION,
            'phpVersion'     => PHP_VERSION,
        ]);
    }
}
