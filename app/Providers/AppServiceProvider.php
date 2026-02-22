<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\Navigation::observe(\App\Observers\NavigationObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
    }
}
