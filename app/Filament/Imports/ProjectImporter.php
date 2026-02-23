<?php

namespace App\Filament\Imports;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Str;

class ProjectImporter extends Importer
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('title')
                ->helperText('Min 5, Max 255')
                ->requiredMapping()
                ->rules(['required', 'min:5', 'max:255']),

            ImportColumn::make('slug')
                ->helperText('Alpha_dash, Max 255')
                ->requiredMapping()
                ->rules(['required', 'max:255', 'alpha_dash']),

            ImportColumn::make('description')
                ->helperText('Max 5000')
                ->rules(['nullable', 'max:5000']),

            ImportColumn::make('excerpt')
                ->helperText('Max 160')
                ->rules(['nullable', 'max:160']),

            ImportColumn::make('status')
                ->helperText('Available values: ' . implode(', ', array_column(ProjectStatus::cases(), 'value'))),

            ImportColumn::make('link')
                ->rules(['nullable', 'url', 'max:255']),

            ImportColumn::make('github_link')
                ->rules(['nullable', 'url', 'max:255']),

            ImportColumn::make('completed_at')
                ->rules(['nullable', 'date']),

            ImportColumn::make('is_featured')
                ->boolean()
                ->rules(['boolean']),

            ImportColumn::make('order')
                ->helperText('Numeric values: min0, max100')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0', 'max:100']),
        ];
    }

    public function resolveRecord(): ?Project
    {
        if (!empty($this->data['slug'])) {
            $this->data['slug'] = Str::slug($this->data['slug']);
        } elseif (!empty($this->data['title'])) {
            $this->data['slug'] = Str::slug($this->data['title']);
        }

        $project = Project::firstOrNew([
            'slug' => $this->data['slug'],
        ]);
        return $project;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your project import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
