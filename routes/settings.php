<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\ProfileController;

Route::middleware('auth')->group(function () {
    Route::get('/settings/profile', [ProfileController::class, 'index'])->name('settings.profile');
});