<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        return view('student.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();

        return view('student.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            // password confirmation to save any changes
            'current_password' => ['required', 'string'],
            // optional photo upload
            'profile_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()
                ->withErrors(['current_password' => 'Incorrect password.'])
                ->withInput();
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->boolean('remove_photo')) {
            $user->profile_photo_blob = null;
            $user->profile_photo_mime = null;
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $user->profile_photo_blob = file_get_contents($file->getRealPath());
            $user->profile_photo_mime = $file->getMimeType();
        }

        $user->save();

        return redirect()
            ->route('student.profile.show')
            ->with('success', 'Profile updated successfully.');
    }
}

