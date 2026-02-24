<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Categories/Index', [
            'categories' => CategoryService::getAll()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:categories'
        ]);

        Category::create($validated);

        return back()->with('success', 'Категория создана');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:categories,title,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $category->update($validated);

        return back()->with('success', 'Категория обновлена');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Категория удалена. Связанные товары теперь Без категории.');
    }
}
