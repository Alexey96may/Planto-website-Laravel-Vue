<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Services\SettingService;
use App\Services\NavigationService;
use App\Services\CartService;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            
            'cart_count' => fn() => CartService::getCount(),
            'cart_ids' => fn() => CartService::getIds(),

            'settings' => fn() => Schema::hasTable('settings') ? SettingService::all() : [],
            'navigation' => Schema::hasTable('navigations') ? NavigationService::get() : [],

            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error'   => fn() => $request->session()->get('error'),
                'message' => fn() => $request->session()->get('message'),
            ],
        ];
    }
}
