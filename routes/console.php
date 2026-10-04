<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('filament:cleanup-import-export --days=30')->daily();

if (App::isLocal()) {
    Schedule::command('telescope:prune')->hourly();
    Schedule::command('db:optimize-telescope')->hourly();
}
