<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Services\SettingService;
use Inertia\Inertia;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $limit = SettingService::get('reviews_limit', 10);
        $sort = $request->input('sort', 'newest');

        $query = Comment::with('user:id,name,avatar')
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
            ],
            
            'seo' => $this->seo(
                title: 'Customer Reviews | Planto Community',
                description: 'See what our customers say about their cyber-plants. Read real stories about our quality and worldwide delivery.',
                keywords: 'planto reviews, customer feedback, plant store quality'
            )
        ]);
    }
}
