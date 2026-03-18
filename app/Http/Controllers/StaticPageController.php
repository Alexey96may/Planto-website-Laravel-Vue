<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class StaticPageController extends Controller
{
    /* Render the 404 Page*/
    public function notFound(): Response
    {
        return Inertia::render('Error', [
            'status' => 404,
            'seo' => $this->seo(
                title: '404 - Page Not Found',
                description: 'Oops! The plant you are looking for has been moved or doesn\'t exist.',
                robots: 'noindex, nofollow',
                canonical: null,
            )
        ]);
    }

    /* Render the Terms Page*/
    public function terms(): \Inertia\Response
    {
        return \Inertia\Inertia::render('Terms', [
            'seo' => $this->seo(
                title: 'Terms of Service & Privacy Policy',
                description: 'Read our terms of service, privacy policy, and shipping conditions.',
            )
        ]);
    }
}