<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;

Route::middleware(['auth'])->group(function () {
    Route::get('/messages', [MessagesController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store');
});