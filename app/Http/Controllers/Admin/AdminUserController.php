<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminUserController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Users/Index', [
            'users' => User::select('id', 'name', 'email', 'role', 'created_at', 'avatar')
                           ->latest()
                           ->get(),
            'seo' => $this->seo(
                title: "Admin | Users",
                description: 'See store users.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin'
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('message', "Users role {$user->name} is changed.");
    }
}
