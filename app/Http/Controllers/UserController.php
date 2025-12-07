<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //
    public function update_last_active(Request $request, $id)
    {
        User::where('id', $id)->update(['last_active_at' => now()]);

        return back();
    }

    public function users($pattern) {
        return User::select('id', 'first_name', 'last_name', 'avatar')
        ->where('id', '!=', Auth::id())
        ->where('first_name', 'like', "%".$pattern."%")
        ->orWhere('last_name', 'like', "%".$pattern."%")
        ->limit(10)
        ->get();
    }
}
