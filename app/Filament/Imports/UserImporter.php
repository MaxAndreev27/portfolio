<?php

namespace App\Filament\Imports;

use App\Models\User;
use App\Models\Role;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\Hash;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required']),

            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email']),

            ImportColumn::make('roles_list')
                ->label('Roles')
                ->fillRecordUsing(function (User $record, $state) {
                    // Ми нічого не робимо з $record тут.
                    // Просто залишаємо поле порожнім для основної моделі.
                }),
        ];
    }

    public function resolveRecord(): ?User
    {
        $user = User::firstOrNew([
            'email' => $this->data['email'],
        ]);

        if (! $user->exists) {
            $user->password = Hash::make('password123');
        }

        return $user;
        // return new User();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and ' . Number::format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }

    protected function afterSave(): void
    {
        // $rolesString = $this->data['roles_list'] ?? null;
        $rolesString = $this->originalData['Roles'] ?? null;

        if ($rolesString) {
            $roleNames = collect(explode(',', $rolesString))
                ->map(fn($name) => trim($name))
                ->filter()
                ->toArray();

            $roleIds = Role::whereIn('name', $roleNames)->pluck('id')->toArray();

            if (!empty($roleIds)) {
                $this->record->roles()->sync($roleIds);
            }
        }
    }
}
