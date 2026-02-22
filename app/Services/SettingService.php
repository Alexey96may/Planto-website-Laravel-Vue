<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    const CACHE_KEY = 'site_settings';

    public static function all()
    {
        return Cache::remember(self::CACHE_KEY, now()->addWeek(), function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $settings = self::all();
        return $settings[$key] ?? $default;
    }

    public static function clearCache()
    {
        Cache::forget(self::CACHE_KEY);
    }
}