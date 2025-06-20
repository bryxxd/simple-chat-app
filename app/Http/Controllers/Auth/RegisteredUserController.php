<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create()
    {
        return Inertia::render('auth/SignUp');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request)
    {
        // 
    }
}
