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
        $query = Product::trending();

        return $limit ? $query->take($limit)->get() : $query->get();
    }

    
    /**
     * Get Top products with an optional limit
     */
    public function getTopProducts(?int $limit = null, ?int $days = null)
    {
        $query = Product::top($days);

        return $limit ? $query->take($limit)->get() : $query->get();
    }
}