<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\ClassRoom;
use App\Models\Material;
use App\Models\MaterialProgress;
use App\Models\MaterialStepProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TeacherMaterialController extends Controller
{
    /* ================= CREATE ================= */

    public function create($classId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        return view('teacher.material-create', compact('class'));
    }

    /* ================= STORE ================= */

    public function store(Request $request, $classId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:coding,ai,robotics',
            'description'  => 'required|string',
            'duration'     => 'nullable|integer|min:1',
            'difficulty'   => 'required|in:beginner,intermediate,advanced',
            'cover_image'  => 'nullable|image|max:10240',
            'quiz_url'     => 'nullable|url',
            'is_published' => 'boolean',

            'steps'                     => 'required|array|min:1',
            'steps.*.title'             => 'required|string|max:255',
            'steps.*.description'       => 'required|string',
            'steps.*.image'             => 'nullable|image|max:10240',
            'steps.*.video_url'         => 'nullable|url',
        ]);

        $coverImagePath = null;
        if (!empty($data['cover_image'])) {
            $coverImagePath = $data['cover_image']->store('material_covers', 'public');
        }

        $material = Material::create([
            'class_id'     => $class->id,
            'teacher_id'   => $teacher->id,
            'title'        => $data['title'],
            'category'     => $data['category'],
            'description'  => $data['description'],
            'duration'     => $data['duration'] ?? null,
            'difficulty'   => $data['difficulty'],
            'cover_image'  => $coverImagePath,
            'quiz_url'     => $data['quiz_url'] ?? null,
            'is_published' => $request->boolean('is_published'),
        ]);

       foreach ($data['steps'] as $index => $step) {
            $imagePath = null;

            if (!empty($step['image'])) {
                $imagePath = $step['image']->store('material_steps', 'public');
            }

            $material->steps()->create([
                'title'           => $step['title'],
                'description'     => $step['description'],
                'image'           => $imagePath,
                'video_url'       => $step['video_url'] ?? null,
                'step_order'      => $index + 1,
            ]);
        }

        return redirect()
            ->route('teacher.classes.show', $class->id)
            ->with('success', 'Material added successfully!');
    }

    /* ================= EDIT ================= */

    public function edit($classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->with(['steps' => fn ($q) => $q->orderBy('step_order')])
            ->findOrFail($materialId);

        return view('teacher.material-edit', compact('class', 'material'));
    }

    /* ================= UPDATE ================= */

    public function update(Request $request, $classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->with('steps')
            ->findOrFail($materialId);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|in:coding,ai,robotics',
            'description'  => 'required|string',
            'duration'     => 'nullable|integer|min:1',
            'difficulty'   => 'required|in:beginner,intermediate,advanced',
            'cover_image'  => 'nullable|image|max:10240',
            'quiz_url'     => 'nullable|url',
            'is_published' => 'boolean',
            'is_public'    => 'boolean',

            'steps'                     => 'required|array|min:1',
            'steps.*.title'             => 'required|string|max:255',
            'steps.*.description'       => 'required|string',
            'steps.*.image'             => 'nullable|image|max:10240',
            'steps.*.video_url'         => 'nullable|url',
        ]);

        $coverImagePath = $material->cover_image; // Keep old by default
        if (!empty($data['cover_image'])) {
            // Delete old cover image
            if ($material->cover_image) {
                Storage::disk('public')->delete($material->cover_image);
            }
            $coverImagePath = $data['cover_image']->store('material_covers', 'public');
        }

        $material->update([
            'title'        => $data['title'],
            'category'     => $data['category'],
            'description'  => $data['description'],
            'duration'     => $data['duration'] ?? null,
            'difficulty'   => $data['difficulty'],
            'cover_image'  => $coverImagePath,
            'quiz_url'     => $data['quiz_url'] ?? null,
            'is_published' => $request->boolean('is_published'),
            'is_public'    => $request->boolean('is_public'),
        ]);

        // Get old steps for image preservation
        $oldSteps = $material->steps->keyBy('step_order');

        // Replace steps
        $material->steps()->delete();

        foreach ($data['steps'] as $index => $step) {
            $imagePath = null;

            if (!empty($step['image'])) {
                $imagePath = $step['image']->store('material_steps', 'public');
                // Delete old image if exists
                $oldStep = $oldSteps->get($index + 1);
                if ($oldStep && $oldStep->image) {
                    Storage::disk('public')->delete($oldStep->image);
                }
            } else {
                // Keep old image if no new one
                $oldStep = $oldSteps->get($index + 1);
                $imagePath = $oldStep ? $oldStep->image : null;
            }

            $material->steps()->create([
                'title'      => $step['title'],
                'description' => $step['description'],
                'image'      => $imagePath,
                'video_url'  => $step['video_url'] ?? null,
                'step_order' => $index + 1,
            ]);
        }

        // Delete any remaining old images not reused
        foreach ($oldSteps as $oldStep) {
            if ($oldStep->image && !in_array($oldStep->image, $material->steps->pluck('image')->toArray())) {
                Storage::disk('public')->delete($oldStep->image);
            }
        }

        return redirect()
            ->route('teacher.classes.show', $class->id)
            ->with('success', 'Material updated successfully!');
    }

    /* ================= DELETE ================= */

    public function destroy($classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->with('steps')
            ->findOrFail($materialId);

        foreach ($material->steps as $step) {
            if ($step->image) {
                Storage::disk('public')->delete($step->image);
            }
        }

        $material->delete();

        return redirect()
            ->route('teacher.classes.show', $class->id)
            ->with('success', 'Material deleted successfully!');
    }

    /* ================= STUDENT PROGRESS ================= */

    public function showStudentProgress($classId, $materialId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->findOrFail($materialId);

        // Get all students in the class
        $students = $class->students;

        // Get progress for each student
        $studentProgress = [];
        $completedStudents = 0;
        $totalProgress = 0;

        foreach ($students as $student) {
            // Calculate progress in real-time from step progress
            $totalSteps = $material->steps()->count();
            $completedSteps = MaterialStepProgress::where('student_id', $student->id)
                ->whereIn('material_step_id', $material->steps()->pluck('id'))
                ->where('is_completed', true)
                ->count();

            $progressPercent = $totalSteps > 0 ? (int)(($completedSteps / $totalSteps) * 100) : 0;
            $isCompleted = $completedSteps === $totalSteps && $totalSteps > 0;

            $studentProgress[] = [
                'student' => $student,
                'progress_percent' => $progressPercent,
                'is_completed' => $isCompleted,
                'completed_steps' => $completedSteps,
                'total_steps' => $totalSteps,
            ];

            if ($isCompleted) {
                $completedStudents++;
            }
            $totalProgress += $progressPercent;
        }

        $averageProgress = $students->count() > 0 ? round($totalProgress / $students->count()) : 0;

        // Filter by completed if requested
        $filter = request()->get('filter');
        if ($filter === 'completed') {
            $studentProgress = array_filter($studentProgress, function ($item) {
                return $item['is_completed'];
            });
        }

        return view('teacher.material-student-progress', [
            'class' => $class,
            'material' => $material,
            'students' => collect($studentProgress),
            'totalStudents' => $students->count(),
            'completedStudents' => $completedStudents,
            'averageProgress' => $averageProgress,
            'filter' => $filter,
        ]);
    }

    /* ================= STUDENT DETAIL PROGRESS ================= */

    public function showStudentDetailProgress($classId, $materialId, $studentId)
    {
        $teacher = Auth::guard('teacher')->user();

        $class = ClassRoom::where('teacher_id', $teacher->id)
            ->findOrFail($classId);

        $material = Material::where('class_id', $class->id)
            ->where('teacher_id', $teacher->id)
            ->with(['steps' => fn ($q) => $q->orderBy('step_order')])
            ->findOrFail($materialId);

        // Verify student is in the class
        $student = $class->students()->findOrFail($studentId);

        // Calculate progress in real-time
        $totalSteps = $material->steps()->count();
        $completedSteps = MaterialStepProgress::where('student_id', $student->id)
            ->whereIn('material_step_id', $material->steps()->pluck('id'))
            ->where('is_completed', true)
            ->count();

        $progressPercent = $totalSteps > 0 ? (int)(($completedSteps / $totalSteps) * 100) : 0;
        $isCompleted = $completedSteps === $totalSteps && $totalSteps > 0;

        // Create a progress object for consistency
        $progress = (object) [
            'progress_percent' => $progressPercent,
            'is_completed' => $isCompleted,
            'completed_steps' => $completedSteps,
            'total_steps' => $totalSteps,
        ];

        // Get step-by-step progress
        $stepProgress = [];
        foreach ($material->steps as $step) {
            $stepProg = MaterialStepProgress::where('material_step_id', $step->id)
                ->where('student_id', $student->id)
                ->first();

            $stepProgress[] = [
                'step' => $step,
                'is_completed' => $stepProg ? $stepProg->is_completed : false,
                'completed_at' => $stepProg ? $stepProg->updated_at->setTimezone('Asia/Makassar') : null,
            ];
        }

        return view('teacher.material-student-detail-progress', [
            'class' => $class,
            'material' => $material,
            'student' => $student,
            'progress' => $progress,
            'stepProgress' => $stepProgress,
        ]);
    }
}
