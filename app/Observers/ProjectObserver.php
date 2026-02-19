<?php

namespace App\Observers;

use App\Models\Project;
use Illuminate\Support\Facades\Storage;

class ProjectObserver
{
    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        if ($project->isDirty('image') && $project->getOriginal('image')) {
            Storage::disk('public')->delete($project->getOriginal('image'));
        }
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        if ($project->image) {
            Storage::disk('public')->delete($project->image);
        }
    }
}
