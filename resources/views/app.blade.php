<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <title inertia>{{ config('app.name', 'Planto') }}</title>
        <meta name="description" content="Welcome to Planto Shop" head-key="description">

        <meta name="author" content="Planto" head-key="author">

        @routes
        @vite(["resources/scss/app.scss", 'resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead

        <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon-main.svg') }}" head-key="favicon">
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
