<?php

namespace App\Observers;

use App\Models\Navigation;
use App\Services\NavigationService;

class NavigationObserver
{
    private function resetCache(): void
    {
        NavigationService::clearCache();
    }

    public function saved(Navigation $navigation): void
    {
        $this->resetCache();
    }

    public function deleted(Navigation $navigation): void
    {
        $this->resetCache();
    }

    public function restored(Navigation $navigation): void
    {
        $this->resetCache();
    }
}
