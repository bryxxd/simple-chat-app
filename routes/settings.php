<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\AvatarController;
use App\Http\Controllers\Settings\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('settings.profile');
    Route::patch('/settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');

    // Avatar upload route
    Route::post('/settings/profile/avatar/validation', [AvatarController::class, 'validation'])->name('settings.profile.avatar.validation');
});
