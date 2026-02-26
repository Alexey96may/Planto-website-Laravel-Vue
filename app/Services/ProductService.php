<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Get trending products with an optional limit
     */
    public function getTrendingProducts(?int $limit = null)
    {
        return Product::with('category')
                    ->trending()
                    ->take($limit)
                    ->get();
    }
    
    /**
     * Get Top products with an optional limit
     */
    public function getTopProducts(?int $limit = null, ?int $days = null)
    {
        $query = Product::top($days)
            ->where('stock', '>', 0)
            ->with('category');

        if ($limit) {
            $query->take($limit);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return Product::where('stock', '>', 0)
                ->with('category')
                ->latest()
                ->take($limit ?? 4)
                ->get();
        }

        return $products;
    }
}