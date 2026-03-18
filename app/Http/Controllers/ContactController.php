<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Contacts/Index', [
            'share_url' => url('/'),
            'seo' => $this->seo(
                title: 'Contact Us | Get in Touch with Planto',
                description: 'Have questions about rare plants or your order? Contact our support team via email or follow us on social media.',
                keywords: 'contact planto, customer support, help desk'
            )
        ]);
    }
}
