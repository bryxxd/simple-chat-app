<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PasswordResetLinkController extends Controller
{
    //
    /**
     * Display the password reset link request form.
     */
    public function create() {
        return Inertia::render('auth/ForgotPassword');
    }
}
