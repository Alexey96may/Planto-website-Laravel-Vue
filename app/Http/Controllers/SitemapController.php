<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];
        $now = now()->toAtomString();

        $staticPages = [
            ['loc' => '/',         'priority' => '1.0'],
            ['loc' => '/shop',     'priority' => '0.9'],
            ['loc' => '/contacts', 'priority' => '0.5'],
            ['loc' => '/terms',    'priority' => '0.3'],
            ['loc' => '/cart',     'priority' => '0.1'], 
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc'      => url($page['loc']),
                'priority' => $page['priority'],
                'lastmod'  => $now,
            ];
        }
        
        $products = Product::where('stock', '>', 0)->get();

        foreach ($products as $product) {
            $urls[] = [
                'loc' => url("/shop/plant-{$product->id}"),
                'priority' => '0.8',
                'lastmod' => $product->updated_at->toAtomString(),
            ];
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'text/xml');
    }
}