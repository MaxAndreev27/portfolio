<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Vite;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->where('status', 'published')
            ->where('is_featured', true)
            ->orderBy('order')
            ->get()
            ->map(fn($project) => [
                'id' => $project->id,
                'title' => $project->title,
                'slug' => $project->slug,
                // 'description' => $project->description,
                'excerpt' => $project->excerpt,
                'image' => $project->image ?
                    asset('storage/' . $project->image) :
                    Vite::asset('resources/js/assets/images/default-image.webp'),
                // 'tags' => $project->tags,
                // 'link' => $project->link,
                'url' => $project->url,
                'github_url' => $project->github_url,
                'completed_at' => $project->completed_at?->format('Y-m-d'),
            ]);

        return Inertia::render('Home', [
            'canRegister' => Features::enabled(Features::registration()),
            'projects' => $projects
        ]);
    }
}
