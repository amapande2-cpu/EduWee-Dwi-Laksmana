<?php
// app/Http/Controllers/StudentClassController.php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled classes with counts
        $classes = $user->classes()
            ->withCount(['materials' => function($query) {
                $query->where('is_published', true);
            }])
            ->with('teacher')
            ->get();

        return view('student.classes', compact('classes'));
    }

    public function show($id)
    {
        $user = Auth::user();
        
        // Check if user is enrolled in this class
        $class = $user->classes()
            ->where('classes.id', $id)
            ->with('teacher')
            ->firstOrFail();

        // Get materials for this class
        $scratchMaterials = $class->materials()
            ->where('category', 'scratch')
            ->where('is_published', true)
            ->latest()
            ->get();

        $pictoMaterials = $class->materials()
            ->where('category', 'picto-ai')
            ->where('is_published', true)
            ->latest()
            ->get();

        return view('student.class-detail', compact('class', 'scratchMaterials', 'pictoMaterials'));
    }
}