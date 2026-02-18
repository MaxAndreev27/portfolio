<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use App\Enums\ProjectStatus;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'excerpt',
        'image',
        'url',
        'github_url',
        'completed_at',
        'is_featured',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => ProjectStatus::class
    ];

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::ucfirst($value),
        );
    }
}
