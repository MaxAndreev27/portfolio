<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

//Route::get('/emaillist', function (Request $request) {
//    return view('mail.contact-form');
//});

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

require __DIR__ . '/settings.php';

