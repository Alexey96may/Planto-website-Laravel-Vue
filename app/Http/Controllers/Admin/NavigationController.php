<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class NavigationController extends Controller
{
    public function index()
    {

        return Inertia::render('Admin/Navigation/Index', [
            'menuItems' => Navigation::with('children')
                ->whereNull('parent_id') 
                ->orderBy('location')
                ->orderBy('order')
                ->get(),
            'seo' => $this->seo(
                title: "Admin | Navigation",
                description: 'See and change the navigation system.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Navigation/Create', [
            'parentOptions' => Navigation::whereNull('parent_id')
                ->orderBy('title')
                ->get(['id', 'title', 'location']),
            'categories' => Category::all(['id', 'title']),
            'seo' => $this->seo(
                title: "Create Navigation item",
                description: 'Create navigation details.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'link'      => 'required_if:type,link|nullable|string',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'location'  => 'required|in:header,footer',
            'parent_id' => 'nullable|exists:navigations,id',
            'order'     => 'integer',
            'is_active' => 'boolean',
            'type'      => 'required|in:link,category',
        ]);

        Navigation::create($validated);

        return redirect()->route('admin.navigation.index')
                         ->with('message', 'Menu item has been created.');
    }
    
    public function edit(Navigation $navigation)
    {
        return Inertia::render('Admin/Navigation/Edit', [
            'item' => $navigation,
            'parentOptions' => Navigation::whereNull('parent_id')
                ->where('id', '!=', $navigation->id) 
                ->orderBy('title')
                ->get(['id', 'title', 'location']),
            'categories' => Category::all(['id', 'title']),
            'seo' => $this->seo(
                title: "Editing: {$navigation->title}",
                description: 'Update title details.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function update(Request $request, Navigation $navigation)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'link'      => 'required_if:type,link|nullable|string',
            'category_id' => 'required_if:type,category|nullable|exists:categories,id',
            'type'      => 'required|in:link,category',
            'location'  => 'required|in:header,footer',
            // Additional security: parent_id cannot be equal to the id of the element being edited!
            'parent_id' => [
                'nullable',
                'exists:navigations,id',
                function ($attribute, $value, $fail) use ($navigation) {
                    if ($value == $navigation->id) {
                        $fail('A menu item cannot be its own parent.');
                    }
                },
            ],
            'order'     => 'integer',
            'is_active' => 'boolean'
        ]);

        $navigation->update($validated);

        return redirect()->route('admin.navigation.index')
                        ->with('message', 'The menu item has been updated!');
    }

    public function destroy(Navigation $navigation)
    {
        $navigation->delete();

        return redirect()->route('admin.navigation.index')
                        ->with('message', 'The menu item and its sub-items have been removed.');
    }

    public function reorder(Request $request)
    {
        $orders = $request->input('orders');

        DB::transaction(function () use ($orders) {
            foreach ($orders as $item) {
                \App\Models\Navigation::where('id', $item['id'])
                    ->update(['order' => $item['position']]);
            }
        });

        return back()->with('message', 'The order has been updated.');
    }
}
