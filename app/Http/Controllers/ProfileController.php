<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = Auth::user();
        return Inertia::render('Profile', [
            'profile' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id);

        // return dd($request->all(), $request->file('image'));

        $validated = $request->validate([
            'name' => 'sometimes|min:3',
            'email' => 'sometimes|email',
            'password' => 'nullable|min:3',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                Storage::delete('profile/' . $user->image);
            }

            $fileName = uniqid() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs('profile', $fileName);
            $user->image = $fileName;
        }

        $user->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }
}
