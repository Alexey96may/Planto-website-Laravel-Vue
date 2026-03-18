<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function seo(string $title, string $description, ?string $image = null, string $robots = 'index, follow', string $keywords = 'plants, cyber store, garden', ?string $canonical = null)
    {
        return [
            'title' => $title,
            'description' => $description,
            'image' => $image ?? asset('images/og-default.png'),
            'robots' => $robots,
            'keywords' => $keywords,
            'canonical' => $canonical ?? url()->current(),
        ];
    }
}
