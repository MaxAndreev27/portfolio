<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->striped()
            ->deferLoading()
            ->paginated([10, 25, 50, 100, 'all'])
            ->extremePaginationLinks()
            ->defaultPaginationPageOption(25)
            ->defaultSort('id', direction: 'desc')
            ->searchPlaceholder('Search by ID, Title')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                ImageColumn::make('image'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('excerpt')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('url')
                    ->searchable()
                    ->icon(Heroicon::Link)
                    ->openUrlInNewTab(),
                TextColumn::make('github_url')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->openUrlInNewTab(),
                TextColumn::make('completed_at')
                    ->date()
                    ->sortable(),
                ToggleColumn::make('is_featured'),
                TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('status')
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'warning',
                        'published' => 'success',
                        'archived' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->hiddenLabel(),
                    EditAction::make()
                        ->hiddenLabel(),
                    DeleteAction::make()
                        ->hiddenLabel(),
                ])->buttonGroup(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
