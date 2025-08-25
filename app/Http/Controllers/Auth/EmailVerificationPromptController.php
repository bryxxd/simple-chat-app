<?php

namespace App\Http\Controllers\Auth;
use Inertia\Response as InertiaResponse;
use Inertia\Inertia;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View|InertiaResponse
    {
        return $request->user()->hasVerifiedEmail()
            ? redirect()->intended(route('dashboard', absolute: false))
            : Inertia::render('auth/VerifyEmailNotice');
    }
}
