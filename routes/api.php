<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatRoomController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('web')->group(function () {
    Route::get('/user/get-last-active/{id}', [UserController::class, 'get_last_active']);
    Route::post('/user/update-last-active/{id}', [UserController::class, 'update_last_active']);
    Route::get('/chat-room/get-messages/{to_user_id}', [ChatRoomController::class, 'show']);
    Route::post('/chat-room/send-message', [ChatRoomController::class, 'store']);
});
