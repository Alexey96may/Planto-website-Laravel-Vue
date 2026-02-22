<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryService;

class CategoryObserver
{    
    private function resetCache(): void
    {
        CategoryService::clearCache();
    }

    public function saved(Category $category)
    {
        $this->resetCache();
    }
    public function deleted(Category $category): void
    {
        $this->resetCache();
    }
    public function restored(Category $category): void
    {
        $this->resetCache();
    }
}
