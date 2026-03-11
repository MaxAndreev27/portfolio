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
            'menuSettings' => $homeSettings ? [
                'hero_is_featured' => (bool) $homeSettings->hero_is_featured,
                'about_is_featured' => (bool) $homeSettings->about_is_featured,
                'projects_is_featured' => (bool) $homeSettings->projects_is_featured,
                'technology_is_featured' => (bool) $homeSettings->technology_is_featured,
                'contact_is_featured' => (bool) $homeSettings->contact_is_featured,
                'hero_menu_item' => $homeSettings->hero_menu_item,
                'about_menu_item' => $homeSettings->about_menu_item,
                'projects_menu_item' => $homeSettings->projects_menu_item,
                'technology_menu_item' => $homeSettings->technology_menu_item,
                'contact_menu_item' => $homeSettings->contact_menu_item,
            ] : null,
            'heroSettings' => $homeSettings ? [
                'hero_is_featured' => (bool) $homeSettings->hero_is_featured,
                'hero_title' => $homeSettings->hero_title,
                'hero_description' => $homeSettings->hero_description,
                'hero_image' => $homeSettings->image_url,
                'hero_button_about' => $homeSettings->hero_button_about,
                'hero_button_contact' => $homeSettings->hero_button_contact,
            ] : null,
            'aboutSettings' => $homeSettings ? [
                'about_is_featured' => (bool) $homeSettings->about_is_featured,
                'about_title' => $homeSettings->about_title,
                'about_timeline' => $homeSettings->about_timeline,
                'about_skills' => $homeSettings->about_skills,
            ] : null,
            'projectsSettings' => $homeSettings ? [
                'projects_is_featured' => (bool) $homeSettings->projects_is_featured,
                'projects_title' => $homeSettings->projects_title,
            ] : null,
            'technologySettings' => $homeSettings ? [
                'technology_is_featured' => (bool) $homeSettings->technology_is_featured,
            ] : null,
            'contactSettings' => $homeSettings ? [
                'contact_is_featured' => (bool) $homeSettings->contact_is_featured,
                'contact_title' => $homeSettings->contact_title,
            ] : null,
            'footerSettings' => $homeSettings ? [
                'footer_social_links' => collect($homeSettings->footer_social_links)->map(fn($item) => [
                    'label' => $item['label'] ?? '',
                    'url' => $item['url'] ?? '#',
                    'icon' => !empty($item['icon']) ? asset('storage/' . $item['icon']) : null,
                ]),
                'footer_copyright' => $homeSettings->footer_copyright,
                'footer_powered' => $homeSettings->footer_powered,
            ] : null,
            'seoSettings' => $homeSettings ? [
                'seo_title' => $homeSettings->seo_title,
                'seo_description' => $homeSettings->seo_description,
            ] : null,
        ]);
    }
}
