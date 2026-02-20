<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $comments = $request->user()->comments()->latest()->get();

        return Inertia::render('Dashboard', [
            'myComments' => $comments
        ]);
    }
}
