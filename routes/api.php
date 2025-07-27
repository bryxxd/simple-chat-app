<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessagesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('web')->group(function () {
    Route::get('/messages/{user_id}', [MessagesController::class, 'show']);
});
