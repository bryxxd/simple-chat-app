<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatRoomController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('web')->group(function () {
    Route::get('/chat-room/{to_user_id}', [ChatRoomController::class, 'show']);
    Route::post('/chat/messages', [ChatRoomController::class, 'store'])->name('messages.store');
    Route::get('/get-last-active/{id}', [UserController::class, 'get_last_active']);
    Route::post('/update-last-active/{id}', [UserController::class, 'update_last_active']);
    Route::post('/send-message', [ChatRoomController::class, 'store']);
    Route::get('/interacted-user', [ChatRoomController::class, 'index']);
});
