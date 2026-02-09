<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Enums\FiltersLayout;

class UsersTable
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
            ->searchPlaceholder('Search by ID, Name, Email')
            ->persistSortInSession()
            ->persistSearchInSession()
            ->reorderableColumns()
            ->deferColumnManager(false)
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email (click to copy)')
                    ->icon(Heroicon::Envelope)
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email address copied'),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('roles.name')
                    ->label('Ролі')
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
                    })
                    ->separator(',')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('two_factor_confirmed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->stackedOnMobile()
            ->filters([
                SelectFilter::make('roles')
                    ->label('Filter by role')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                TernaryFilter::make('email_verified_at')
                    ->nullable(),
                TernaryFilter::make('two_factor_confirmed_at')
                    ->nullable()
            ], layout: FiltersLayout::AboveContent)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
