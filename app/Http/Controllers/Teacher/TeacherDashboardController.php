<?php
// app/Http/Controllers/Teacher/TeacherDashboardController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Material;
use App\Models\MaterialStepProgress;
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

    public function classesIndex()
    {
        $teacher = Auth::guard('teacher')->user();
        $classes = $teacher->classes()->withCount(['students', 'materials'])->get();

        return view('teacher.classes', compact('classes'));
    }

    public function showClass($id)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->where('id', $id)
            ->with(['students', 'materials'])
            ->firstOrFail();

        // Calculate completed count for each material
        foreach ($class->materials as $material) {
            $totalSteps = $material->steps()->count();
            $completedStudents = 0;

            foreach ($class->students as $student) {
                $completedSteps = MaterialStepProgress::where('student_id', $student->id)
                    ->whereIn('material_step_id', $material->steps()->pluck('id'))
                    ->where('is_completed', true)
                    ->count();

                if ($completedSteps === $totalSteps && $totalSteps > 0) {
                    $completedStudents++;
                }
            }

            $material->completed_count = $completedStudents;
        }

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

        return redirect()->route('teacher.classes.show', $class->id)
            ->with('success', 'Class created successfully! Share code: ' . $class->class_code);
    }

    public function destroyClass($id)
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

        return redirect()->route('teacher.classes.show', $id)
            ->with('success', 'Class updated successfully!');
    }

    public function removeStudent($classId, $studentId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);
        
        // Remove student from class
        $class->students()->detach($studentId);

        return redirect()->route('teacher.classes.show', $classId)
            ->with('success', 'Student removed from class successfully!');
    }
}