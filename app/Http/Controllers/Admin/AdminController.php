<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Comment;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'products_count' => Product::count(),
                'users_count' => User::count(),
            ]
        ]);
    }

}
