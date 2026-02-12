<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentTimezone;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return $user->hasRole('admin');
        });

        FilamentTimezone::set('Europe/Kyiv');

        TextColumn::configureUsing(function (TextColumn $column): void {
            if (str_ends_with($column->getName(), '_at')) {
                $column->dateTime('d.m.Y H:i');
            }
        });

        TextEntry::configureUsing(function (TextEntry $entry): void {
            if (str_ends_with($entry->getName(), '_at')) {
                $entry->dateTime('d.m.Y H:i');
            }
        });

        DateTimePicker::configureUsing(function (DateTimePicker $component): void {
            $component
                ->weekStartsOnMonday()
                ->closeOnDateSelection()
                ->displayFormat('d.m.Y H:i')
                ->native(false);
        });
    }
}
