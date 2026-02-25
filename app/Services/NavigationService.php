<?php

namespace App\Services;

use App\Models\Navigation;
use Illuminate\Support\Facades\Cache;

class NavigationService
{
    const CACHE_KEY = 'main_navigation';

    public static function get(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addWeek(), function () {
            return [
                'header' => Navigation::with('children')
                    ->with('category')
                    ->where('location', 'header')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get()
                    ->toArray(),

                'footer' => Navigation::with('category')
                    ->where('location', 'footer')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get()
                    ->toArray(),
            ];
        });
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}