<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Services\SettingService;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function inфdex()
    {
        $limit = SettingService::get('reviews_limit', 10);

        return Inertia::render('Reviews/Index', [
            'reviews' => Comment::with('user:id,name')
                ->where('is_active', true)
                ->latest()
                ->paginate($limit),
            'averageRating' => Comment::where('is_active', true)->avg('rating')
        ]);
    }

    public function index(Request $request)
    {
        $limit = SettingService::get('reviews_limit', 10);
        $sort = $request->input('sort', 'newest');

        $query = Comment::with('user:id,name')
            ->where('is_active', true);

        $query = match ($sort) {
            'highest' => $query->orderByDesc('rating'),
            'lowest'  => $query->orderBy('rating'),
            default   => $query->latest(),
        };

        return Inertia::render('Reviews/Index', [
            'reviews' => $query->paginate($limit)
                ->withQueryString(),
            
            'averageRating' => (float) Comment::where('is_active', true)->avg('rating'),
            
            'filters' => [
                'sort' => $sort
            ]
        ]);
    }
}
