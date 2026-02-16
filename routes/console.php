<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Facades\Storage;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $hours = config('filament-actions.prune_exports_older_than_hours', 24);

    $oldExports = Export::where('created_at', '<=', now()->subHours($hours))->get();

    foreach ($oldExports as $export) {
        $folder = "filament_exports";
        $path = "{$folder}/{$export->getKey()}/{$export->file_name}";

        if (Storage::disk($export->file_disk)->exists($path)) {
            Storage::disk($export->file_disk)->delete($path);
            Storage::disk($export->file_disk)->deleteDirectory("{$folder}/{$export->getKey()}");
        }

        $export->delete();
    }
})->everyMinute();

if (App::isLocal()) {
    Schedule::command('telescope:prune')->hourly();
    Schedule::command('db:optimize-telescope')->hourly();
}
