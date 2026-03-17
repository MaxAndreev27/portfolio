<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('language/{locale}', function (string $locale) {
    if (! array_key_exists($locale, config('locales.supported'))) {
        abort(400);
    }
    session()->put('locale', $locale);
    session()->save();

    return back();
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

//Route::get('/emaillist', function (Request $request) {
//    return view('mail.contact-form');
//});

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

//Routes for Broadcasting ChatWidget
Route::post('/chat/send', [ChatController::class, 'sendMessage'])->middleware(['auth', 'verified'])->name('chat.send');

Route::get('/chat/messages/{userId}', [ChatController::class, 'getMessages'])->middleware(['auth'])->name('chat.get');

// Fallback route for 404 errors
Route::fallback(function () {
    return Inertia::render('Errors/Error', [
        'status' => 404,
        'message' => 'Page not found',
    ])->toResponse(Request::create(request()->getRequestUri()))
        ->setStatusCode(404);
})->name('fallback');

require __DIR__ . '/settings.php';
