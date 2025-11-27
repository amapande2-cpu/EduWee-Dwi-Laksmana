<?php
// app/Http/Controllers/Teacher/TeacherDashboardController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherDashboardController extends Controller
{
    public function index()
    {
        $teacher = Auth::guard('teacher')->user();
        $classes = $teacher->classes()->withCount(['students', 'materials'])->get();
        $totalStudents = $teacher->classes()->withCount('students')->get()->sum('students_count');
        $totalMaterials = $teacher->materials()->count();

        return view('teacher.dashboard', compact('classes', 'totalStudents', 'totalMaterials'));
    }

    public function showClass($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->where('id', $id)
            ->with(['students', 'materials'])
            ->firstOrFail();

        return view('teacher.class-detail', compact('class'));
    }

    public function createClass(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::create([
            'teacher_id' => $teacher->id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('teacher.class.show', $class->id)
            ->with('success', 'Class created successfully! Share code: ' . $class->class_code);
    }

    public function deleteClass($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($id);
        $class->delete();

        return redirect()->route('teacher.dashboard')
            ->with('success', 'Class deleted successfully!');
    }

    public function editClass($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($id);

        return view('teacher.class-edit', compact('class'));
    }

    public function updateClass(Request $request, $id)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $class->update($validated);

        return redirect()->route('teacher.class.show', $id)
            ->with('success', 'Class updated successfully!');
    }

    public function removeStudent($classId, $studentId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);
        
        // Remove student from class
        $class->students()->detach($studentId);

        return redirect()->route('teacher.class.show', $classId)
            ->with('success', 'Student removed from class successfully!');
    }
}