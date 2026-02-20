<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index()
    {
        return Inertia::render('Reviews/Index', [
            'reviews' => Comment::with('user:id,name')
                ->where('is_active', true)
                ->latest()
                ->paginate(10), //todo
            'averageRating' => Comment::where('is_active', true)->avg('rating')
        ]);
    }
}
