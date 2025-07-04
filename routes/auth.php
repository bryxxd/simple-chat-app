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
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    /*
     * Signup routes
     */
    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('signup');
    Route::post('/signup', [RegisteredUserController::class, 'store'])->name('signup.store');

    /*
     * Profile routes
     */
    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('profile');

    /*
     * Forgot password reset routes
     */
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot.password');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'show'])->name('forgot.password.store');
});