<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    //
    public function update_last_active(Request $request, $id) {
        User::where('id', $id)->update(['last_active_at' => now()]);

        return response()->json([
            'message' => 'User last active time updated successfully.',
        ]);
    }

    public function get_last_active($id) {
        $user = User::select('last_active_at')->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'last_active_at' => $user->last_active_at,
        ]);
    }
}
