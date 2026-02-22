<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Services\SettingService;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
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
}
