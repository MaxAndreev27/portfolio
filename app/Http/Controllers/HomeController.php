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
            'projects' => $projects
        ]);
    }
}
