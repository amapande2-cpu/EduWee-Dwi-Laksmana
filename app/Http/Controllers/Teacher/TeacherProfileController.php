<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class TeacherProfileController extends Controller
{
    public function show()
    {
        $teacher = Auth::guard('teacher')->user();

        return view('teacher.profile.show', compact('teacher'));
    }

    public function edit()
    {
        $teacher = Auth::guard('teacher')->user();

        return view('teacher.profile.edit', compact('teacher'));
    }

    public function update(Request $request)
    {
        $teacher = Auth::guard('teacher')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('teachers', 'email')->ignore($teacher->id),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:255'],
            'current_password' => ['required', 'string'],
            'profile_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
        ]);

        if (! Hash::check($validated['current_password'], $teacher->password)) {
            return back()
                ->withErrors(['current_password' => 'Incorrect password.'])
                ->withInput();
        }

        $teacher->name = $validated['name'];
        $teacher->email = $validated['email'];
        $teacher->phone = $validated['phone'];
        $teacher->subject = $validated['subject'];

        if ($request->boolean('remove_photo')) {
            $teacher->profile_photo_blob = null;
            $teacher->profile_photo_mime = null;
        }

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $teacher->profile_photo_blob = file_get_contents($file->getRealPath());
            $teacher->profile_photo_mime = $file->getMimeType();
        }

        $teacher->save();

        return redirect()
            ->route('teacher.profile.edit')
            ->with('success', 'Profile updated successfully.');
    }
}

