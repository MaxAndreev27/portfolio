<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Home', [
            'canRegister' => Features::enabled(Features::registration()),
        ]);
    }
}
