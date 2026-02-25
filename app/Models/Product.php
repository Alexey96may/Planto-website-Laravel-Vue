<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Product extends Model
{
    protected $fillable = ['title', 'description', 'price', 'image', 'category_id', 'is_trending', 'trending_order', 'sales_count'];

    protected $appends = ['image_url'];

    protected $casts = [
        'is_trending' => 'boolean',
        'trending_order' => 'integer',
        'price' => 'decimal:2',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->image) {
                return asset('images/no-image.jpg');
            }

            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }

            return Storage::url($this->image);
        });
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true)
                    ->orderBy('trending_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    public function scopeAppliedSort($query, $type)
    {
        return match ($type) {
            'popular'    => $query->orderByDesc('sales_count'),
            'new'        => $query->latest(),
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->orderByDesc('sales_count'),
        };
    }

    public function scopeTop($query, ?int $days = null)
    {
        if ($days) {
            return $query->withSum(['orderItems as total_sold' => function ($q) use ($days) {
                    $q->where('created_at', '>=', now()->subDays($days));
                }], 'quantity')
                ->orderByRaw('total_sold DESC NULLS LAST') 
                ->orderByDesc('created_at');
        }

        return $query->orderByDesc('sales_count')
                    ->orderByDesc('created_at');
    }
}
