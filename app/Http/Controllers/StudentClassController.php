<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Support\Facades\Auth;

class StudentClassController extends Controller
{
  
    public function index()
    {
        $student = Auth::user();

        $classes = $student->classes()
            ->with('teacher')
            ->withCount([
                // Count ONLY published materials for display
                'materials as published_materials_count' => function ($query) {
                    $query->where('is_published', true);
                }
            ])
            ->orderBy('name')
            ->get();

        return view('student.classes', compact('classes'));
    }


    public function show(ClassRoom $class)
    {
        $student = Auth::user();

        if (! $student->classes()->where('classes.id', $class->id)->exists()) {
            abort(403, 'You are not enrolled in this class.');
        }

    
        $class->load([
            'teacher',
            'materials' => function ($query) {
                $query->where('is_published', true)
                      ->whereNotNull('class_id') // prevent public-only leakage
                      ->latest();
            }
        ]);

        return view('student.class-detail', compact('class'));
    }
}
