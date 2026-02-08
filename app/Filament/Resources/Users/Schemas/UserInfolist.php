<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('roles.name')
                    ->label('Roles')
                    ->badge()
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
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('two_factor_secret')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('two_factor_recovery_codes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('two_factor_confirmed_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
