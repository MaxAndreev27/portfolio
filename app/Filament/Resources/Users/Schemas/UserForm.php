<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class UserForm
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
                                TextInput::make('name')
                                    ->placeholder('John Doe')
                                    ->belowContent('This is the user\'s full name.')
                                    ->required()
                                    ->minLength(2)
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->label('Email address')
                                    ->email()
                                    ->unique()
                                    ->suffixIcon(Heroicon::Envelope)
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->required(fn(string $operation): bool => $operation === 'create')
                                    ->dehydrated(fn($state) => filled($state))
                                    ->minLength(5)
                                    ->maxLength(255)
                                    ->confirmed(),
                                TextInput::make('password_confirmation')
                                    ->password()
                                    ->revealable(),
                            ]),
                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                Select::make('roles')
                                    ->relationship('roles', 'name')
                                    ->suffixIcon(Heroicon::UserGroup)
                                    ->multiple()
                                    ->preload()
                                    ->searchable()
                                    ->label('Role'),
                                DateTimePicker::make('email_verified_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),
                                Textarea::make('two_factor_secret'),
                                Textarea::make('two_factor_recovery_codes'),
                                DateTimePicker::make('two_factor_confirmed_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),
                            ]),
                    ]),
            ]);
    }
}
