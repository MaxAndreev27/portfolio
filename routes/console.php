<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

if (App::isLocal()) {
    Schedule::command('telescope:prune')->hourly();

    Schedule::call(function () {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        if ($driver === 'sqlite') {
            $connection->statement('VACUUM');
        } elseif ($driver === 'mysql') {
            $connection->statement('OPTIMIZE TABLE telescope_entries, telescope_entries_tags, telescope_monitoring');
        }
    })->hourly();
}
