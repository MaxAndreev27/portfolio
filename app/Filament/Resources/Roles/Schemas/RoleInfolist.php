<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;

class RoleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Main information')
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('name')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                                TextEntry::make('display_name')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                                TextEntry::make('description')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                            ]),
                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->icon(Heroicon::CalendarDays),
                                TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->icon(Heroicon::CalendarDays),
                            ]),
                    ]),
            ]);
    }
}
