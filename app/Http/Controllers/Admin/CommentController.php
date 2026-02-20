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
            'comments' => Comment::with('user:id,name,email')
                ->latest()
                ->get()
        ]);
    }

    public function update(Comment $comment)
    {
        $comment->update([
            'is_active' => !$comment->is_active
        ]);

        return back()->with('message', 'Статус комментария изменен');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();

        return back()->with('message', 'Отзыв удален');
    }
}
