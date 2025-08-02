<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfilePictureController;
use App\Http\Controllers\Settings\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('settings.profile');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

    // Profile picture upload route
    Route::post('/settings/profile/picture/validation', [ProfilePictureController::class, 'validation'])->name('settings.profile.picture.validation');
    Route::post('/settings/profile/picture', [ProfilePictureController::class, 'store'])->name('settings.profile.picture.store');
});
