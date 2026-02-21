<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Navigation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NavigationController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Navigation/Index', [
            'menuItems' => Navigation::with('children')
                ->whereNull('parent_id') 
                ->orderBy('location')
                ->orderBy('order')
                ->get()
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Navigation/Create', [
            'parentOptions' => Navigation::whereNull('parent_id')
                ->orderBy('title')
                ->get(['id', 'title', 'location'])
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'link'      => 'nullable|string',
            'location'  => 'required|in:header,footer',
            'parent_id' => 'nullable|exists:navigations,id',
            'order'     => 'integer',
            'is_active' => 'boolean'
        ]);

        Navigation::create($validated);

        return redirect()->route('admin.navigation.index')
                         ->with('message', 'Пункт меню создан');
    }
    
    public function edit(Navigation $navigation)
    {
        return Inertia::render('Admin/Navigation/Edit', [
            'item' => $navigation,
            'parentOptions' => Navigation::whereNull('parent_id')
                ->where('id', '!=', $navigation->id) 
                ->orderBy('title')
                ->get(['id', 'title', 'location'])
        ]);
    }

    public function update(Request $request, Navigation $navigation)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'link'      => 'nullable|string',
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
                        ->with('message', 'Пункт меню и его подпункты удалены');
    }
}
