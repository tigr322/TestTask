<?php

use App\Http\Controllers\ShortLinkController;
use App\Http\Controllers\ShortLinkRedirectController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::get('/links', [ShortLinkController::class, 'index'])->name('links.index');
    Route::post('/links', [ShortLinkController::class, 'store'])->name('links.store');
    Route::get('/links/{shortLink}', [ShortLinkController::class, 'show'])->name('links.show');
    Route::delete('/links/{shortLink}', [ShortLinkController::class, 'destroy'])->name('links.destroy');
});

require __DIR__.'/settings.php';

Route::get('/{shortCode}', ShortLinkRedirectController::class)
    ->where('shortCode', '[a-zA-Z0-9]{6,8}')
    ->name('redirect');
