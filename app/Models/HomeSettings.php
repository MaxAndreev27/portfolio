<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Vite;
use Spatie\Translatable\HasTranslations;

class HomeSettings extends Model
{
    /** @use HasFactory<\Database\Factories\HomeSettingsFactory> */
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'id',
        //menu
        'hero_menu_item',
        'about_menu_item',
        'projects_menu_item',
        'technology_menu_item',
        'contact_menu_item',
        //hero section
        'hero_is_featured',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_button_about',
        'hero_button_contact',
        //about
        'about_is_featured',
        'about_title',
        'about_timeline',
        'about_skills',
        //projects
        'projects_is_featured',
        'projects_title',
        //technology
        'technology_is_featured',
        //contact
        'contact_is_featured',
        'contact_title',
        //footer
        'footer_social_links',
        'footer_copyright',
        'footer_powered',
        //SEO
        'seo_title',
        'seo_description',
    ];

    public $translatable = [
        'hero_menu_item',
        'about_menu_item',
        'projects_menu_item',
        'technology_menu_item',
        'contact_menu_item',
        'hero_title',
        'hero_description',
        'hero_button_about',
        'hero_button_contact',
        'about_title',
        'about_timeline',
        'about_skills',
        'projects_title',
        'contact_title',
        'footer_copyright',
        'footer_powered',
        'seo_title',
        'seo_description'
    ];

    protected $casts = [
        'hero_is_featured' => 'boolean',
        'about_is_featured' => 'boolean',
        'projects_is_featured' => 'boolean',
        'technology_is_featured' => 'boolean',
        'contact_is_featured' => 'boolean',
        'footer_social_links' => 'array',
    ];

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->hero_image
                ? asset('storage/' . $this->hero_image)
                : Vite::asset('resources/js/assets/images/default-avatar.webp')
        );
    }
}
