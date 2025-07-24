<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;

Route::middleware(['auth'])->group(function () {
    Route::post('/messages', [MessagesController::class, 'store'])->name('messages.store');
});