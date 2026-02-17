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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Enums\FiltersLayout;

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
            ->searchPlaceholder('Search by ID, Title, Slug')
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
                    ->limit(30)
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('excerpt')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('url')
                    ->searchable()
                    ->icon(Heroicon::GlobeAlt)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->openUrlInNewTab(),
                TextColumn::make('github_url')
                    ->searchable()
                    ->icon(Heroicon::Link)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->openUrlInNewTab(),

                TextColumn::make('completed_at')
                    ->date()
                    ->sortable(),
                ToggleColumn::make('is_featured')
                    ->sortable()
                    ->alignCenter()
                    ->onColor('success')
                    ->offColor('danger'),

                TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->alignCenter(),
                IconColumn::make('status')
                    ->sortable()
                    ->alignCenter(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->stackedOnMobile()
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter by status'),
                TernaryFilter::make('is_featured')
                    ->nullable(),
                TernaryFilter::make('completed_at')
                    ->nullable()
            ], layout: FiltersLayout::AboveContent)
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
