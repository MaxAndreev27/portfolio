<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\TextSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Vite;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->columnSpanFull()
                    ->schema([
                        Section::make('Main information')
                            ->columns(2)
                            ->columnSpan(2)
                            ->schema([
                                TextEntry::make('title')
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),

                                TextEntry::make('slug')
                                    ->placeholder('-')
                                    ->size(TextSize::Medium)
                                    ->color('primary'),

                                TextEntry::make('description')
                                    ->label('Description')
                                    ->placeholder('-')
                                    ->html()
                                    ->columnSpanFull()
                                    ->prose()
                                    ->size(TextSize::Medium)
                                    ->color('primary'),

                                TextEntry::make('excerpt')
                                    ->columnSpanFull()
                                    ->placeholder('-')
                                    ->icon(Heroicon::Sparkles)
                                    ->size(TextSize::Medium)
                                    ->color('primary'),
                            ]),
                        Section::make('Addition')
                            ->columnSpan(1)
                            ->schema([
                                ImageEntry::make('image')
                                    ->label('Project cover')
                                    ->disk('public')
                                    ->visibility('public')
                                    ->defaultImageUrl(Vite::asset('resources/js/assets/images/default-image.webp'))
                                    ->extraImgAttributes([
                                        'alt' => 'Project cover',
                                        'style' => 'width: 100%; height: auto; object-fit: cover;',
                                    ]),

                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(fn($state) => $state->getLabel())
                                    ->size(TextSize::Medium)
                                    ->color(fn($state) => $state->getColor())
                                    ->icon(fn($state) => $state->getIcon()),

                                TextEntry::make('url')
                                    ->placeholder('-')
                                    ->url(fn($state) => $state)
                                    ->openUrlInNewTab()
                                    ->size(TextSize::Medium)
                                    ->icon(Heroicon::Link)
                                    ->color('primary'),

                                TextEntry::make('github_url')
                                    ->placeholder('-')
                                    ->url(fn($state) => $state)
                                    ->openUrlInNewTab()
                                    ->size(TextSize::Medium)
                                    ->icon(Heroicon::ServerStack)
                                    ->color('primary'),

                                TextEntry::make('completed_at')
                                    ->placeholder('-')
                                    ->date()
                                    ->color('primary')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->icon(Heroicon::CalendarDays)
                                    ->since(),

                                IconEntry::make('is_featured')
                                    ->boolean(),

                                TextEntry::make('order')
                                    ->numeric()
                                    ->label('Order position')
                                    ->size(TextSize::Medium)
                                    ->color('primary')
                                    ->icon(Heroicon::ChartBar)
                                    ->iconColor('gray'),

                                TextEntry::make('created_at')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->color('primary')
                                    ->size(TextSize::Medium)
                                    ->icon(Heroicon::CalendarDays),

                                TextEntry::make('updated_at')
                                    ->dateTime()
                                    ->placeholder('-')
                                    ->color('primary')
                                    ->size(TextSize::Medium)
                                    ->icon(Heroicon::CalendarDays),
                            ]),
                    ]),
            ]);
    }
}
