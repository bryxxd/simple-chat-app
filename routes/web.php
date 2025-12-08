<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->name('dashboard')->middleware(['auth', 'verified']);


require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
