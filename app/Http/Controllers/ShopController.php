<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\CartService;
use App\Services\SettingService;

class ShopController extends Controller
{
    public function index(Request $request, SettingService $settings)
    {
        $perPage = $settings->get('products_per_page', 6);

        $validated = $request->validate([
            'search'    => 'nullable|string|max:100',
            'category'  => 'nullable|string|exists:categories,slug',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
            'sort'      => 'nullable|string|in:popular,new,price_asc,price_desc',
            'in_stock'  => 'nullable',
        ]);

        $inStockOnly = $request->boolean('in_stock', true);

        $products = Product::query()
            ->with('category')
            ->when($validated['category'] ?? null, function ($q, $slug) {
                if ($slug === 'none') {
                    $q->whereNull('category_id');
                } else {
                    $q->whereHas('category', fn($cat) => $cat->where('slug', $slug));
                }
            })
            ->when($validated['search'] ?? null, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->when($validated['min_price'] ?? null, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($validated['max_price'] ?? null, fn($q, $p) => $q->where('price', '<=', $p))
            ->when($inStockOnly, function ($q) {
                $q->where('stock', '>', 0);
            })
            ->appliedSort($validated['sort'] ?? 'popular')
            ->paginate($perPage)
            ->withQueryString();

            dd(array_merge($request->only(['category', 'search', 'min_price', 'max_price', 'sort']), [
                'in_stock' => $inStockOnly ? 'true' : 'false'
            ]));
        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => CategoryService::getAll(),
            'currentCategory' => $validated['category'] ?? null,
            'filters' => array_merge($request->only(['category', 'search', 'min_price', 'max_price', 'sort']), [
                'in_stock' => $inStockOnly ? 'true' : 'false'
            ]),
        ]);
    }

    public function show($id) 
    {
        $product = Product::with('category')->findOrFail($id);

        return Inertia::render('Product/PlantPage', [
            'product' => $product,
            'cart_items' => CartService::getIdsWithQuantities(),
            'backUrl' => route('shop', ['page' => request('page', 1)])
        ]);
    }
}
