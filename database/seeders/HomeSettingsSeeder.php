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
                /*
                |--------------------------------------------------------------------------
                | MENU
                |--------------------------------------------------------------------------
                */
                'hero_menu_item' => [
                    'en' => 'Home',
                    'uk' => 'Головна',
                ],
                'about_menu_item' => [
                    'en' => 'About',
                    'uk' => 'Про мене',
                ],
                'projects_menu_item' => [
                    'en' => 'Projects',
                    'uk' => 'Проєкти',
                ],
                'technology_menu_item' => [
                    'en' => 'Technology',
                    'uk' => 'Технології',
                ],
                'contact_menu_item' => [
                    'en' => 'Contact',
                    'uk' => 'Контакти',
                ],
                /*
                |--------------------------------------------------------------------------
                | HERO
                |--------------------------------------------------------------------------
                */
                'hero_is_featured' => true,
                'hero_title' => [
                    'en' => 'Name Surname',
                    'uk' => "Ім'я Прізвище",
                ],
                'hero_description' => [
                    'en' => 'Fullstack developer',
                    'uk' => 'Fullstack Розробник',
                ],
                'hero_image' => null,
                'hero_button_about' => [
                    'en' => 'About me',
                    'uk' => 'Про мене',
                ],
                'hero_button_contact' => [
                    'en' => 'Contact me',
                    'uk' => "Зв'язатись",
                ],
                /*
                |--------------------------------------------------------------------------
                | ABOUT
                |--------------------------------------------------------------------------
                */
                'about_is_featured' => true,
                'about_title' => [
                    'en' => 'About me',
                    'uk' => 'Про мене',
                ],
                'about_timeline' => [],
                'about_skills' => [],
                /*
                |--------------------------------------------------------------------------
                | PROJECTS
                |--------------------------------------------------------------------------
                */
                'projects_is_featured' => true,
                'projects_title' => [
                    'en' => 'My Projects',
                    'uk' => 'Мої проекти',
                ],
                /*
                |--------------------------------------------------------------------------
                | TECHNOLOGY
                |--------------------------------------------------------------------------
                */
                'technology_is_featured' => true,
                /*
                |--------------------------------------------------------------------------
                | CONTACT
                |--------------------------------------------------------------------------
                */
                'contact_is_featured' => true,
                'contact_title' => [
                    'en' => 'Contact me',
                    'uk' => "Зв'яжіться",
                ],
                /*
                |--------------------------------------------------------------------------
                | FOOTER
                |--------------------------------------------------------------------------
                */
                'footer_social_links' => [],
                'footer_copyright' => [
                    'en' => 'All rights reserved',
                    'uk' => 'Всі права захищені',
                ],
                'footer_powered' => [
                    'en' => 'Powered by Laravel and Vue',
                    'uk' => 'Працює на Laravel та Vue',
                ],
            ]
        );
    }
}
