<?php
// app/Http/Controllers/Teacher/TeacherMaterialController.php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMaterialController extends Controller
{
    public function create($classId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);

        return view('teacher.material-create', compact('class'));
    }

    public function store(Request $request, $classId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:scratch,picto-ai',
            'description' => 'required|string',
            'video_url' => 'required|url',
            'duration' => 'nullable|integer|min:1',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
        ]);

        // Explicitly create with all required fields
        $material = new Material();
        $material->class_id = $class->id;
        $material->teacher_id = $teacher->id;
        $material->title = $validated['title'];
        $material->category = $validated['category'];
        $material->description = $validated['description'];
        $material->video_url = $validated['video_url'];
        $material->duration = $validated['duration'] ?? null;
        $material->difficulty = $validated['difficulty'];
        $material->is_published = true;
        $material->save();

        return redirect()->route('teacher.class.show', $class->id)
            ->with('success', 'Material added successfully!');
    }

    public function edit($classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);
        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->findOrFail($materialId);

        return view('teacher.material-edit', compact('class', 'material'));
    }

    public function update(Request $request, $classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);
        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->findOrFail($materialId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:scratch,picto-ai',
            'description' => 'required|string',
            'video_url' => 'required|url',
            'duration' => 'nullable|integer|min:1',
            'difficulty' => 'required|in:beginner,intermediate,advanced',
        ]);

        // Update with validated data
        $material->title = $validated['title'];
        $material->category = $validated['category'];
        $material->description = $validated['description'];
        $material->video_url = $validated['video_url'];
        $material->duration = $validated['duration'] ?? null;
        $material->difficulty = $validated['difficulty'];
        $material->save();

        return redirect()->route('teacher.class.show', $class->id)
            ->with('success', 'Material updated successfully!');
    }

    public function destroy($classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();
        $class = ClassRoom::where('teacher_id', $teacher->id)->findOrFail($classId);
        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->findOrFail($materialId);

        $material->delete();

        return redirect()->route('teacher.class.show', $class->id)
            ->with('success', 'Material deleted successfully!');
    }
}