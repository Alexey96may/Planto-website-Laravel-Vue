<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Mews\Purifier\Facades\Purifier;

class FeatureController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Features/Index', [
            'features' => Feature::orderBy('order')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|string',
            'order'       => 'integer',
            'image'       => 'nullable|image|max:10240',
        ]);

        if ($request->filled('description')) {
            $validated['description'] = Purifier::clean($request->description);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('features', 'public');
        }

        Feature::create($validated);

        return redirect()->route('admin.features.index')->with('message', 'Карточка успешно создана!');
    }

    public function update(Request $request, Feature $feature)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'link'        => 'nullable|string',
            'order'       => 'integer',
            'image'       => 'nullable|image|max:10240',
        ]);

        if ($request->filled('description')) {
            $validated['description'] = Purifier::clean($request->description);
        }

        if ($request->hasFile('image')) {
            if ($feature->image) {
                Storage::disk('public')->delete($feature->image);
            }
            $validated['image'] = $request->file('image')->store('features', 'public');
        }

        $feature->update($validated);

        return back()->with('message', 'Карточка обновлена');
    }

    public function destroy(Feature $feature)
    {
        if ($feature->image) {
            Storage::disk('public')->delete($feature->image);
        }
        
        $feature->delete();

        return back()->with('message', 'Карточка удалена');
    }

    public function create()
    {
        return Inertia::render('Admin/Features/Create');
    }

    public function edit(Feature $feature)
    {
        return Inertia::render('Admin/Features/Edit', [
            'feature' => $feature
        ]);
    }
}
