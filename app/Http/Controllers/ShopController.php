<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search'    => 'nullable|string|max:100',
            'category'  => 'nullable|string|exists:categories,slug',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
        ]);

        $products = Product::query()
            ->with('category')
            ->when($validated['category'] ?? null, function ($q, $slug) {
                $q->whereHas('category', fn($cat) => $cat->where('slug', $slug));
            })
            ->when($validated['search'] ?? null, function ($q, $search) {
                $q->where('title', 'like', "%{$search}%");
            })
            ->when($validated['min_price'] ?? null, fn($q, $p) => $q->where('price', '>=', $p))
            ->when($validated['max_price'] ?? null, fn($q, $p) => $q->where('price', '<=', $p))
            ->get();

        return Inertia::render('Shop/Index', [
            'products' => $products,
            'categories' => Category::all(),
            'filters' => $request->only(['category', 'search', 'min_price', 'max_price']),
        ]);
    }

    public function show($id) 
    {
        $product = Product::with('category')->findOrFail($id);

        return Inertia::render('Product/PlantPage', [
            'product' => $product
        ]);
    }
}
