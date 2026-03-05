<?php

namespace App\Observers;

use App\Models\HomeSettings;
use Illuminate\Support\Facades\Storage;

class HomeSettingsObserver
{
    /**
     * Handle the HomeSettings "updated" event.
     */
    public function updated(HomeSettings $homeSettings): void
    {
        if ($homeSettings->isDirty('hero_image') && $homeSettings->getOriginal('hero_image')) {
            Storage::disk('public')->delete($homeSettings->getOriginal('hero_image'));
        }
    }

    /**
     * Handle the HomeSettings "deleted" event.
     */
    public function deleted(HomeSettings $homeSettings): void
    {
        if ($homeSettings->hero_image) {
            Storage::disk('public')->delete($homeSettings->hero_image);
        }
    }
}
