<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\ProfilePassword;
use App\Http\Controllers\Settings\ProfilePictureController;

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('settings.profile');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

    // Profile picture upload route
    Route::post('/settings/profile/picture/validation', [ProfilePictureController::class, 'validation'])->name('settings.profile.picture.validation');
    Route::post('/settings/profile/picture', [ProfilePictureController::class, 'store'])->name('settings.profile.picture.store');

    /*
     * Password
     */
    Route::get('/settings/password', [ProfilePassword::class, 'index'])->name('settings.password');
    Route::post('/settings/password', [ProfilePassword::class, 'update'])->name('settings.password.update');
  
    /*
     * Appearance
     */
    Route::get('/settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('settings.appearance');
});
