<?php
// app/Http/Controllers/Teacher/TeacherAuthController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TeacherAuthController extends Controller
{
    public function showLogin()
    {
        return view('teacher.auth.login');
    }

    public function showRegister()
    {
        return view('teacher.auth.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::guard('teacher')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('teacher.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:teachers',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:100',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $teacher = Teacher::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'subject' => $validated['subject'] ?? null,
            'password' => Hash::make($validated['password'])
        ]);

        Auth::guard('teacher')->login($teacher);

        return redirect()->route('teacher.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('teacher')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('teacher.login');
    }
}