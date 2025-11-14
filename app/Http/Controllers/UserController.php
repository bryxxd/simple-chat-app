<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //
    public function update_last_active(Request $request, $id)
    {
        User::where('id', $id)->update(['last_active_at' => now()]);

        return back();
    }
}
