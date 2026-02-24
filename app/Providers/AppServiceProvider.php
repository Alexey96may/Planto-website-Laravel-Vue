<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\SyncCartOnLogin;

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
        Event::listen(
            Login::class,
            SyncCartOnLogin::class
        );

        Vite::prefetch(concurrency: 3);
        
        \App\Models\Setting::observe(\App\Observers\SettingObserver::class);
        \App\Models\Navigation::observe(\App\Observers\NavigationObserver::class);
        \App\Models\Category::observe(\App\Observers\CategoryObserver::class);
        \App\Models\OrderItem::observe(\App\Observers\OrderItemObserver::class);

    }
}
