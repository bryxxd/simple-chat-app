<?php
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('auth/Login');
});

Route::get('/signup', function () {
    return Inertia::render('auth/SignUp');
});