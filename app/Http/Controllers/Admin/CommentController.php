<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommentController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Comments/Index', [
            'comments' => Comment::with('user:id,name,email,avatar')
                ->latest()
                ->get(),
            'seo' => $this->seo(
                title: "Admin | Comments",
                description: 'See or update comment status.',
                robots: 'noindex, nofollow'
            )
        ]);
    }

    public function update(Comment $comment)
    {
        $comment->update([
            'is_active' => !$comment->is_active
        ]);

        return back()->with('message', 'Comment status has been changed.');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('message', 'Review has been deleted.');
    }
}
