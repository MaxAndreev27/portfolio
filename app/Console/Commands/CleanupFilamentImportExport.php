<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Filament\Actions\Exports\Models\Export;
use Filament\Actions\Imports\Models\Import;

class CleanupFilamentImportExport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'filament:cleanup-import-export {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old Filament exports and imports from DB and disk';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $date = now()->subDays($days);

        $this->info("Cleaning exports & imports older than {$days} days...");

        $deletedExports = 0;
        $deletedImports = 0;

        Export::query()
            ->whereNotNull('completed_at')
            ->where('created_at', '<', $date)
            ->chunkById(100, function ($exports) use (&$deletedExports) {
                foreach ($exports as $export) {
                    if ($export->file_name) {
                        Storage::disk($export->file_disk)
                            ->delete($export->file_name);
                    }
                    $export->delete();
                    $deletedExports++;
                }
            });

        Import::query()
            ->whereNotNull('completed_at')
            ->where('created_at', '<', $date)
            ->chunkById(100, function ($imports) use (&$deletedImports) {
                foreach ($imports as $import) {
                    if ($import->file_path) {
                        Storage::delete($import->file_path);
                    }
                    $import->delete();
                    $deletedImports++;
                }
            });

        $this->info("Deleted {$deletedExports} exports.");
        $this->info("Deleted {$deletedImports} imports.");

        return self::SUCCESS;
    }
}
