<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;
use App\Enums\ProjectStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Vite;

class Project extends Model
{
    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory;

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

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
        'tags',
    ];

    protected $casts = [
        'completed_at' => 'date',
        'is_featured' => 'boolean',
        'status' => ProjectStatus::class,
    ];

    protected function title(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Str::ucfirst($value),
        );
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image
                ? asset('storage/' . $this->image)
                : Vite::asset('resources/js/assets/images/default-image.webp')
        );
    }

    protected function tags(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value || $value === '""') return [];

                if (is_array($value)) return $value;

                $decoded = json_decode($value, true);
                if (is_array($decoded)) return $decoded;

                return array_filter(array_map('trim', explode(',', $value)));
            },

            set: function ($value) {
                if (empty($value)) return null;

                return is_array($value) ? json_encode($value) : $value;
            },
        );
    }
}
