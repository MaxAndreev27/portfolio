<?php

namespace App\Observers;

use App\Models\Project;
use App\Support\ProjectImageVariants;
use Illuminate\Support\Facades\Storage;

class ProjectObserver
{
    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        if ($project->image) {
            ProjectImageVariants::generate($project->image);
        }
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        if (! $project->wasChanged('image')) {
            return;
        }

        $oldImage = $project->getOriginal('image');

        if ($project->image) {
            ProjectImageVariants::generate($project->image);
        }

        if ($oldImage) {
            ProjectImageVariants::delete($oldImage);
            Storage::disk('public')->delete($oldImage);
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        if ($project->image) {
            ProjectImageVariants::delete($project->image);
            Storage::disk('public')->delete($project->image);
        }
    }
}
