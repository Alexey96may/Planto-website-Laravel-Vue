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
            'body' => 'required|string|min:5',
            'rating' => 'required|numeric|min:0.5|max:5',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $validated['body'],
            'rating' => $validated['rating'],
            'is_active' => false,
        ]);

        return response()->json(['message' => 'Отзыв отправлен на модерацию!']);
    }
}
