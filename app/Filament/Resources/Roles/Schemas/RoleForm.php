<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DateTimePicker;
use Filament\Support\Icons\Heroicon;

class RoleForm
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
                                    ->unique()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('display_name')
                                    ->maxLength(255),
                                TextInput::make('description')
                                    ->maxLength(255),
                            ]),
                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                DateTimePicker::make('created_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),
                                DateTimePicker::make('updated_at')
                                    ->suffixIcon(Heroicon::CalendarDays)
                                    ->readOnly(),
                            ]),
                    ]),
            ]);
    }
}
