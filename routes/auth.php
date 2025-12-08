<?php

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Container\Attributes\Auth;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth as AuthFacade;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;

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
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'show'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
    /*
     * Google OAuth routes
     */
    Route::get('/auth/redirect', [App\Http\Controllers\GoogleAuthController::class, 'redirect'])->name('auth.redirect');
    Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'callback'])->name('auth.callback');
});

Route::middleware('auth')->group(function () {
    /*
     * Email Verification route
     */
    Route::get('/verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    /*
     * Logout route
     */
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});
