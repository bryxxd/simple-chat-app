<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // Redirect authenticated users to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user already exists
        $existingUser = User::where('provider', 'google')
            ->where('provider_id', $user->id)
            ->first();

        if ($existingUser) {
            Auth::login($existingUser);
            return redirect()->route('dashboard');
        } else {
            $nameParts = explode(' ', $user->name ?? 'User', 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $newUser = User::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'avatar' => $user->avatar,
                'provider' => 'google',
                'provider_id' => $user->id,
                'password' => bcrypt(bin2hex(random_bytes(16))), 
                'email_verified_at' => now(),
            ]);

            Auth::login($newUser);
            return redirect()->route('dashboard');
        }
    }
}
