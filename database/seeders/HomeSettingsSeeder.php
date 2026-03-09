<?php

namespace Database\Seeders;

use App\Models\HomeSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HomeSettings::updateOrCreate(
            ['id' => 1],
            [
                //hero section
                'hero_is_featured' => true,
                'hero_title' => 'Hero title',
                'hero_description' => 'Hero description',
                'hero_button_about' => 'About me',
                'hero_button_contact' => 'Contact me',
                //menu settings
                'about_is_featured' => true,
                'projects_is_featured' => true,
                'technology_is_featured' => true,
                'contact_is_featured' => true,
            ]
        );
    }
}
