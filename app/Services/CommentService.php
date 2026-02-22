<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
    public static function getLatestActive()
    {
        $limit = SettingService::get('comments_limit', 3);

        return Comment::with('user:id,name,avatar')
            ->where('is_active', true)
            ->latest()
            ->take($limit)
            ->get();
    }
}