<?php

namespace App\Filament\Resources\Projects\Tables;

use App\Enums\ProjectStatus;
use App\Filament\Exports\ProjectExporter;
use App\Filament\Imports\ProjectImporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\ImportAction;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Vite;

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
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->visibility('public')
                    ->defaultImageUrl(Vite::asset('resources/js/assets/images/default-image.webp'))
                    ->imageHeight(50),

                TextColumn::make('title')
                    ->limit(30)
                    ->searchable(),

                TextColumn::make('tags')
                    ->badge()
                    ->separator(',')
                    ->color('primary')
                    ->size(TextSize::Medium)
                    ->icon(Heroicon::Tag)
                    ->searchable(),

                TextColumn::make('slug')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->limit(50)
                    ->searchable()
                    ->html()
                    ->formatStateUsing(fn(string $state): string => strip_tags($state))
                    ->tooltip(fn(string $state): string => strip_tags($state))
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('excerpt')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn(string $state): string => $state)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('url')
                    ->limit(50)
                    ->searchable()
                    ->icon(Heroicon::Link)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->openUrlInNewTab(),
                TextColumn::make('github_url')
                    ->limit(50)
                    ->searchable()
                    ->icon(Heroicon::ServerStack)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->openUrlInNewTab(),

                TextColumn::make('completed_at')
                    ->date()
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->label('Filter by status')
                    ->options(ProjectStatus::class)
                    ->multiple()
                    ->native(false),

                TernaryFilter::make('is_featured')
                    ->label('Recommended')
                    ->placeholder('All projects')
                    ->trueLabel('Only Recommended')
                    ->falseLabel('Without Recommendation')
                    ->native(false),

                TernaryFilter::make('image')
                    ->label('Availability of cover')
                    ->placeholder('All projects')
                    ->trueLabel('With cover')
                    ->falseLabel('Without cover')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('image'),
                        false: fn(Builder $query) => $query->whereNull('image'),
                    )
                    ->native(false),
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->headerActions([
                ImportAction::make()
                    ->importer(ProjectImporter::class)
                    ->chunkSize(100)
                    ->csvDelimiter(';'),
                ExportAction::make()
                    ->exporter(ProjectExporter::class)
                    ->chunkSize(100),
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
                ExportBulkAction::make()
                    ->exporter(ProjectExporter::class)
                    ->chunkSize(100),
            ]);
    }
}
