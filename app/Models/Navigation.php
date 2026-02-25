<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    protected $fillable = [
        'title',
        'type', 
        'category_id', 
        'link', 
        'location', 
        'parent_id', 
        'order', 
        'is_active'
    ];

    /**
     * Get child menu items (drop-down list)
     */
    public function children()
    {
        return $this->hasMany(Navigation::class, 'parent_id')
                    ->where('is_active', true)
                    ->orderBy('order');
    }

    /**
     * Get the parent item (if it exists)
     */
    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
