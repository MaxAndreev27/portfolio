<?php

namespace App\Filament\Exports;

use App\Models\Project;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class ProjectExporter extends Exporter
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('title'),
            ExportColumn::make('slug'),
            ExportColumn::make('description'),
            ExportColumn::make('excerpt'),
            ExportColumn::make('image'),
            ExportColumn::make('url'),
            ExportColumn::make('github_url'),
            ExportColumn::make('completed_at'),
            ExportColumn::make('is_featured'),
            ExportColumn::make('order'),
            ExportColumn::make('status'),
            ExportColumn::make('tags')
                ->formatStateUsing(fn($state) => is_array($state) ? implode(', ', $state) : $state),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your project export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getFileDisk(): string
    {
        return 'local';
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Xlsx,
            ExportFormat::Csv,
        ];
    }

    public static function getCsvDelimiter(): string
    {
        return ';';
    }

    public function getFileName(Export $export): string
    {
        return "export-projects-{$export->getKey()}-" . now()->format('Y-m-d_H-i-s');
    }
}
