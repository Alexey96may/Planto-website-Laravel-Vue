<?php

namespace App\Observers;

use App\Models\Setting;
use App\Services\SettingService;

class SettingObserver
{
    private function resetCache(): void
    {
        SettingService::clearCache();
    }

    public function saved(Setting $setting)
    {
        $this->resetCache();
    }
    public function deleted(Setting $setting): void
    {
        $this->resetCache();
    }
    public function restored(Setting $setting): void
    {
        $this->resetCache();
    }
}
