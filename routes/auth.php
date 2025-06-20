<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;

Route::middleware('guest')->group(function () {
    /*
     * Login routes
     */
    Route::get('/', [LoginController::class, 'index'])->name('login');

    /*
     * Signup routes
     */
    Route::get('/signup', [RegisteredUserController::class, 'create'])->name('signup');

    /*
     * Forgot password reset routes
     */
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('forgot.password');
});