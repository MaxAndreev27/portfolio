<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Vite;

class HomeSettings extends Model
{
    /** @use HasFactory<\Database\Factories\HomeSettingsFactory> */
    use HasFactory;

    protected $fillable = [
        'id',
        //hero section
        'hero_is_featured',
        'hero_title',
        'hero_description',
        'hero_image',
        'hero_button_about',
        'hero_button_contact'
    ];

    protected $casts = [
        'hero_is_featured' => 'boolean',
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
