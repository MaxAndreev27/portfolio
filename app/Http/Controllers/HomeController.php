<?php

namespace App\Http\Controllers;

use App\Models\HomeSettings;
use App\Models\Project;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index()
    {
        $homeSettings = HomeSettings::first();

        // dd($homeSettings);

        $projects = Project::query()
            ->select(['id', 'title', 'slug', 'excerpt', 'image', 'url', 'github_url', 'completed_at', 'tags'])
            ->published()
            ->featured()
            ->orderBy('order')
            ->get()
            ->map(fn($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'slug' => $project->slug,
                'excerpt' => $project->excerpt,
                'image' => $project->image_url,
                'url' => $project->url,
                'github_url' => $project->github_url,
                'completed_at' => $project->completed_at?->format('Y-m-d'),
                'tags' => $project->tags,
            ]);

        return Inertia::render('Home', [
            'canRegister' => Features::enabled(Features::registration()),
            'projects' => $projects,
            'homeSettings' => $homeSettings ? [
                'hero_is_featured' => (bool) $homeSettings->hero_is_featured,
                'hero_title' => $homeSettings->hero_title,
                'hero_description' => $homeSettings->hero_description,
                'hero_image' => $homeSettings->image_url,
                'hero_button_about' => $homeSettings->hero_button_about,
                'hero_button_contact' => $homeSettings->hero_button_contact,
            ] : null
        ]);
    }
}
