<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Enums\Fit;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = ['title', 'description', 'price', 'stock', 'image', 'category_id', 'is_trending', 'trending_order', 'sales_count'];

    protected $appends = ['optimized_images', 'thumb_url'];

    protected $casts = [
        'is_trending' => 'boolean',
        'trending_order' => 'integer',
        'price' => 'decimal:2',
    ];

    public function getThumbUrlAttribute(): string
    {
        return $this->getFirstMediaUrl('gallery', 'thumb') ?: asset('images/no-image.png');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')
            ->useDisk(env('MEDIA_DISK', 'public'))
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->width(600) 
            ->nonQueued(); 

        $this->addMediaConversion('avif')
            ->format('avif')
            ->width(600)
            ->nonQueued();

        $this->addMediaConversion('thumb')
            ->fit(Fit::Contain, 150, 150) 
            ->nonQueued();
    }

    protected function getOptimizedImagesAttribute(): ?array
    {
        $media = $this->getFirstMedia('gallery');

        if (!$media) {
            return [
                ['src' => $this->image ? Storage::url($this->image) : asset('images/no-image.png'), 'format' => 'png']
            ];
        }

        return [
            ['src' => $media->getUrl(), 'format' => $media->extension],
        ];
    }
    
    protected function getImageUrlAttribute(): string
    {
        $media = $this->getFirstMedia('gallery');
        return $media ? $media->getUrl() : ($this->image ? Storage::url($this->image) : asset('images/no-image.png'));
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(function () {
            if (!$this->image) {
                return asset('images/no-image.png');
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

    public function getSeoData()
    {
        return [
            'title' => $this->title,
            'description' => Str::limit(strip_tags($this->description), 150),
            'keywords' => "plants, buy {$this->title}, Planto Shop",
            'image' => $this->image_url ?? asset('images/og-default.png'),
        ];
    }
}
