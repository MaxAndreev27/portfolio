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

        // Get old values
        $oldLinks = $homeSettings->getOriginal('footer_social_links') ?? [];
        $newLinks = $homeSettings->footer_social_links ?? [];

        // Get all paths to icons
        $oldIcons = collect($oldLinks)->pluck('icon')->filter()->toArray();
        $newIcons = collect($newLinks)->pluck('icon')->filter()->toArray();

        // Find icons which was deleted
        $iconsToDelete = array_diff($oldIcons, $newIcons);

        foreach ($iconsToDelete as $iconPath) {
            Storage::disk('public')->delete($iconPath);
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

        if (!empty($homeSettings->footer_social_links)) {
            // Collect al paths 'icon' from array
            $icons = collect($homeSettings->footer_social_links)
                ->pluck('icon')
                ->filter() // remove null/empty values
                ->toArray();

            if (count($icons) > 0) {
                Storage::disk('public')->delete($icons);
            }
        }
    }
}
