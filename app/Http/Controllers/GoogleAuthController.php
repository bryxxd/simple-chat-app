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
    public function redirect() {
        // Redirect authenticated users to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return Socialite::driver('google')->redirect();
    }

    public function callback () {
        try {
            $user = Socialite::driver('google')->user();
            
            // Validate required data from Google
            if (!$user->email) {
                return redirect()->route('login.index')
                    ->with('error', 'Unable to get email from Google. Please try again.');
            }

            $existingUser = User::where('email', $user->email)->first();

            if($existingUser) {
                // Update existing user's avatar if available
                if ($user->avatar && $user->avatar !== $existingUser->avatar) {
                    $existingUser->update(['avatar' => $user->avatar]);
                }
                Auth::login($existingUser, true);
            } else {
                // Split the full name into first and last name
                $nameParts = explode(' ', $user->name ?? 'User', 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                
                $newUser = User::create([
                    'email' => $user->email,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'avatar' => $user->avatar,
                    'password' => bcrypt(uniqid()),
                    'email_verified_at' => now(), // Auto-verify Google OAuth emails
                ]);
                Auth::login($newUser, true);
            }

            return redirect()->route('dashboard');  
        } catch (\Exception $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login.index')
                ->with('error', 'Authentication failed. Please try again.');
        }
    }
}

