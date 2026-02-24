<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    public function index()
    {
        $perPage = Setting::where('key', 'products_per_page')->value('value') ?? 6;

        $products = Product::with('category')
            ->latest()
            ->paginate($perPage);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_trending' => 'boolean',
            'trending_order' => 'integer|min:0',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            
            $validated['image'] = '/storage/' . $path;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('message', 'Product is succesfully created!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // dd($request->all(), $request->file('image'));

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_trending' => 'boolean', 
            'trending_order' => 'integer|min:0',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'image'       => $request->hasFile('image') ? 'image|mimes:jpg,jpeg,png|max:2048' : 'nullable',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldPath = str_replace('/storage/', '', $product->image);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = '/storage/' . $path;
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('message', 'Product is succesfully updated!');
    }

    public function updateTrending(Request $request, Product $product)
    {
        $validated = $request->validate([
            'is_trending' => 'sometimes|boolean',
            'trending_order' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return back();
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('message', 'Product is deleted!');
    }
}
