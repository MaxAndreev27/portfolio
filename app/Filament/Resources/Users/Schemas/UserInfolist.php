<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\TextSize;

class UserInfolist
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
                                TextEntry::make('email')
                                    ->label('Email address')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->copyable()
                                    ->copyMessage('Copied!')
                                    ->icon(Heroicon::Envelope),
                            ]),
                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                TextEntry::make('roles.name')
                                    ->label('Roles')
                                    ->badge()
                                    ->size(TextSize::Medium)
                                    ->icon(
                                        fn(string $state): Heroicon => match ($state) {
                                            'admin' => Heroicon::BuildingLibrary,
                                            'editor' => Heroicon::PencilSquare,
                                            'user' => Heroicon::User,
                                            default => Heroicon::User,
                                        }
                                    )
                                    ->color(fn(string $state): string => match ($state) {
                                        'admin' => 'success',
                                        'editor' => 'warning',
                                        'user' => 'gray',
                                        default => 'info',
                                    }),
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
                                TextEntry::make('email_verified_at')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->icon(Heroicon::CalendarDays),
                                TextEntry::make('two_factor_secret')
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                                TextEntry::make('two_factor_recovery_codes')
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                                TextEntry::make('two_factor_confirmed_at')
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
