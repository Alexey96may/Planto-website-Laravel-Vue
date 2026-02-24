<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    const CACHE_KEY = 'shop_categories';

    public static function getAll(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addWeek(), function () {
            return Category::orderBy('title')
                            ->get()
                            ->toArray();
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}