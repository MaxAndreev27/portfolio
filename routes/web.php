<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Home', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Fallback route for 404 errors
Route::fallback(function () {
    return Inertia::render('Errors/Error', [
        'status' => 404,
        'message' => 'Сторінку не знайдено',
    ])->toResponse(\Illuminate\Support\Facades\Request::create(request()->getRequestUri()))
      ->setStatusCode(404);
})->name('fallback');

require __DIR__.'/settings.php';

