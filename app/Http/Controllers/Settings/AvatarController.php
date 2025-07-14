<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller
{
    //
    public function validation(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ]);
    }

    public function store(Request $request)
    {
        // $path = public_path('profile') . '/' . $request->file('avatar');
        $storeImage = Storage::disk('profile')->putFile('', $request->file('avatar'));
        $imageURL = '/profile/' . $storeImage;

        User::where('id', Auth::id())->update([
            'avatar' => $imageURL
        ]);


        return redirect()->back()->with('success', 'Avatar updated successfully.');
    }
}
