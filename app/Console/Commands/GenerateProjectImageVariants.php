<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Support\ProjectImageVariants;
use Illuminate\Console\Command;

class GenerateProjectImageVariants extends Command
{
    protected $signature = 'projects:generate-image-variants';

    protected $description = 'Generate responsive WebP variants for existing project images';

    public function handle(): int
    {
        $query = Project::query()->whereNotNull('image');
        $progress = $this->output->createProgressBar((clone $query)->count());
        $progress->start();

        $query->chunkById(50, function ($projects) use ($progress): void {
            foreach ($projects as $project) {
                if ($project->image) {
                    ProjectImageVariants::generate($project->image);
                }

                $progress->advance();
            }
        });

        $progress->finish();
        $this->newLine();
        $this->info('Generated responsive image variants for existing project images.');

        return self::SUCCESS;
    }
}
