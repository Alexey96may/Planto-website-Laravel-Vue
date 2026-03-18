<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta inertia name="robots" content="index, follow">
        <meta name="author" content="Planto">

        <meta inertia property="og:type" content="website">
        <meta property="og:site_name" content="Cyber Plant Store">
        <meta property="og:image" content="{{ asset('images/og-default.png') }}">

        <meta name="twitter:card" content="summary_large_image">

        <title inertia>{{ config('app.name', 'Planto') }}</title>
        <meta inertia name="description" content="Welcome to Planto Cyber Store">

        @routes
        @vite(["resources/scss/app.scss", 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
