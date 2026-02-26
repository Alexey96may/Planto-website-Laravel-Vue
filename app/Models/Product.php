<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'price', 'stock', 'image', 'category_id', 'is_trending', 'trending_order', 'sales_count'];

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
                    ->where('stock', '>', 0)
                    ->orderByRaw('CASE WHEN trending_order IS NULL THEN 1 ELSE 0 END, trending_order ASC')
                    ->orderByDesc('created_at');
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
        if ($days && $days > 0) {
            return $query->withSum(['orderItems as total_sold' => function ($q) use ($days) {
                    $q->where('created_at', '>=', now()->subDays($days));
                }], 'quantity')
                ->orderByRaw('COALESCE(total_sold, 0) DESC') 
                ->orderByDesc('created_at');
        }

        return $query->orderByDesc('sales_count')
                    ->orderByDesc('created_at');
    }
}
