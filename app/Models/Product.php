<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['title', 'description', 'price', 'image', 'category_id'];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(fn () => 
            $this->image ? Storage::url($this->image) : asset('images/default-placeholder.png')
        );
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }
}
