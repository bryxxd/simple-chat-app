<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    /*
     * Login routes
     */
    Route::get('/login', [LoginController::class, 'index'])->name('login.index');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    /*
     * Signup routes
     */
    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('signup.index');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('signup.store');

    /*
     * Forgot password reset routes
     */
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot.password');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'show'])->name('forgot.password.store');
});

Route::middleware('auth')->group(function () {
    /*
     * Logout route
     */
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    /*
     * Profile routes
     */

    Route::get('/settings/password', function () {
        return Inertia::render('settings/Password');
    })->name('settings.password');

    Route::get('/settings/appearance', function () {
        return Inertia::render('settings/Appearance');
    })->name('settings.appearance');
});