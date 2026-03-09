<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [
            ['url' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
        ];

        // For Projects
        // foreach (Project::all() as $project) {
        //     $urls[] = [
        //         'url' => route('projects.show', $project->slug),
        //         'priority' => '0.8',
        //         'changefreq' => 'weekly'
        //     ];
        // }

        $content = view('sitemap', compact('urls'))->render();

        return response($content)->header('Content-Type', 'text/xml');
    }
}
