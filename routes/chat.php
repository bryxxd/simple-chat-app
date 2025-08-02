<?php

use App\Http\Controllers\ChatRoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::post('/chat/messages', [ChatRoomController::class, 'store'])->name('messages.store');
});