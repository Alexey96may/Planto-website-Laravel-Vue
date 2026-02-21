<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Navigation;

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
            'cart_count' => count(session('cart', [])),
            'cart_ids' => array_keys(session()->get('cart', [])),

            'settings' => \App\Models\Setting::all()->pluck('value', 'key'),

            'flash' => [
                'success' => $request->session()->get('success'),
                'error' => $request->session()->get('error'),
                'message' => $request->session()->get('message'),
            ],
            'navigation' => [
                'header' => Navigation::with('children')
                    ->where('location', 'header')
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get(),
                'footer' => Navigation::where('location', 'footer')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get(),
            ],
        ];
    }
}
