<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatRoomController;

Route::middleware(['auth'])->group(function () {
    Route::post('/chat/messages', [ChatRoomController::class, 'store'])->name('messages.store');
});