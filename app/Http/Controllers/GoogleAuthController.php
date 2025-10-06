<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect() {
        return Socialite::driver('google')->redirect();
    }

    public function callback () {
        $user = Socialite::driver('google')->user();
        $existingUser = User::where('email', $user->email)->first();

        if($existingUser) {
            Auth::login($existingUser, true);
        } else {
            $newUser = User::create([
                'email' => $user->email,
                'first_name' => $user->name,
                'last_name' => $user->name,
                'avatar' => $user->avatar,
                'username' => explode('@', $user->email)[0],
                'password' => bcrypt(uniqid())
            ]);
            Auth::login($newUser, true);
        }

        return redirect()->route('dashboard');  
    }
}

