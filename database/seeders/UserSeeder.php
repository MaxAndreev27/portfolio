<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User::updateOrCreate([
        //     'email' => 'admin@gmail.com',
        //     'name' => 'admin',
        //     'password' => 'admin',
        // ]);

        // $admin = User::factory()->create([
        //     'name' => 'admin',
        //     'email' => 'admin@example.com',
        //     'password' => bcrypt('admin'), // або Hash::make('password')
        // ]);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'), // або Hash::make('password')
        ]);

        $adminRole = Role::where('name', 'admin')->first();
        $admin->roles()->attach($adminRole);

        // $userRole = Role::where('name', 'user')->first();
        // User::factory(10)->create()->each(function ($user) use ($userRole) {
        //     $user->roles()->attach($userRole);
        // });
    }
}
