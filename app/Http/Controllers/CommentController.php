<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'body' => 'required|string|min:5|max:5000',
            'rating' => 'required|numeric|min:0.5|max:5',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
            'rating' => $validated['rating'],
            'is_active' => false,
        ]);

        return back()->with('success', 'Your review has been sent for moderation!');
    }

    public function update(Request $request, Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403, "This is not your review!");
        }

        if ($comment->is_active) {
            return back()->withErrors(['body' => 'Approved comments cannot be edited.']);
        }

        $validated = $request->validate([
            'body' => 'required|string|max:1000',
            'rating' => 'required|numeric|min:0.5|max:5',
        ]);

        $comment->update($validated);

        return back()->with('message', 'Comment has been changed.');
    }

    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id) {
            abort(403, "This is not your review!");
        }   

        $comment->delete();

        return back()->with('message', 'Your rewiew has been deleted.');
    }

}
