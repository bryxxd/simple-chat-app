<?php

namespace App\Http\Controllers\Settings;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePictureController extends Controller
{
    //
    public function validation(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120'
        ], [
            'image.required' => 'Please select an image to upload.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image must not be larger than 5MB.'
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $storeImage = Storage::disk('profile')->putFile('', $request->file('image'));
        $imageURL = '/profile/' . $storeImage;

        // Get the filename from the stored path
        $filename = str_replace('/profile/', '', $user->avatar);

        // Delete the old avatar if it exists
        if ($user->avatar && Storage::disk('profile')->exists($filename)) {
            Storage::disk('profile')->delete($filename);
        }

        User::where('id', Auth::id())->update([
            'avatar' => $imageURL
        ]);

        // Display a success message
        return redirect()->back()->with('success', 'Profile picture updated successfully.');
    }
}
