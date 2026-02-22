<?php

namespace App\Services;

use App\Models\Feature;

class FeatureService
{
    public static function getActive()
    {
        $limit = SettingService::get('features_limit', 4);

        return Feature::where('is_active', true)
            ->orderBy('order')
            ->take($limit)
            ->get();
    }
}