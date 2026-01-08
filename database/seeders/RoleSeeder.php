<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Адміністратор',
                'description' => 'Повний доступ до всіх функцій системи',
            ],
            [
                'name' => 'editor',
                'display_name' => 'Редактор',
                'description' => 'Може редагувати та публікувати контент',
            ],
            [
                'name' => 'user',
                'display_name' => 'Користувач',
                'description' => 'Звичайний зареєстрований відвідувач',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['name' => $role['name']], $role);
        }
    }
}
