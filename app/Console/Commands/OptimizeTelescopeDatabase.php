<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OptimizeTelescopeDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:optimize-telescope';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize Telescope tables depending on database driver';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $connection = DB::connection();
        $driver = $connection->getDriverName();

        $this->info("Database driver: {$driver}");

        try {
            if ($driver === 'sqlite') {
                $this->info('Running VACUUM...');
                $connection->statement('VACUUM');
            } elseif ($driver === 'mysql') {
                $this->info('Running OPTIMIZE TABLE...');
                $connection->statement(
                    'OPTIMIZE TABLE telescope_entries, telescope_entries_tags, telescope_monitoring'
                );
            } elseif ($driver === 'pgsql') {
                $this->info('Running VACUUM ANALYZE (PostgreSQL)...');
                $connection->statement('VACUUM ANALYZE telescope_entries');
                $connection->statement('VACUUM ANALYZE telescope_entries_tags');
                $connection->statement('VACUUM ANALYZE telescope_monitoring');
            } else {
                $this->warn("Driver '{$driver}' is not supported.");
                return self::FAILURE;
            }

            $this->info('Optimization completed successfully.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Optimization failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
