<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServerTimeWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    // Оновлювати віджет кожну секунду (опціонально)
    protected ?string $pollingInterval = '1s';

    protected function getStats(): array
    {
        return [
            Stat::make('Server Time', Carbon::now()->format('H:i:s'))
                ->description('Current date: '.Carbon::now()->format('d.m.Y'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }
}
