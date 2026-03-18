<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $comments = $user->comments()->latest()->get();

        return Inertia::render('Dashboard', [
            'myComments' => $comments,
            'seo' => $this->seo(
                title: "Dashboard: {$user->name}",
                description: 'Manage your profile, comments, and personal settings.',
                robots: 'noindex, nofollow'
            )
        ]);
    }
}
