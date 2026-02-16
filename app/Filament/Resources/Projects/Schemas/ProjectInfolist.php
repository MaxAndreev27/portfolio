<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProjectInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('description')
                    ->columnSpanFull(),
                TextEntry::make('excerpt')
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->placeholder('-'),
                TextEntry::make('url')
                    ->placeholder('-'),
                TextEntry::make('github_url')
                    ->placeholder('-'),
                TextEntry::make('completed_at')
                    ->date()
                    ->placeholder('-'),
                IconEntry::make('is_featured')
                    ->boolean(),
                TextEntry::make('order')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
