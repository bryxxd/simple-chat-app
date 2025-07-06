<?php

namespace App\Http\Controllers\Auth;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;

class LoginController extends Controller
{
    /**
     * Display the login view.
     */
    public function index()
    {
        return Inertia::render('auth/Login');
    }

    /**
     * Handle the login request.
     */
    public function store(LoginRequest $request)
    {
        // Attempt to log the user in
        $request->authenticate();

        // Regenerate the session to prevent session fixation attacks
        $request->session()->regenerate();

        // Redirect to the intended route or a default route
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
