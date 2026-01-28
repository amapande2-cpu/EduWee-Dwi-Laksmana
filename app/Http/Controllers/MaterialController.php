<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC MATERIALS (NO AUTH)
    |--------------------------------------------------------------------------
    */

    /**
     * Public materials listing (published only)
     */
    public function publicIndex()
    {
        $materials = Material::query()
            // ->where('is_public', true) // Temporarily commented for debugging
            ->with('class', 'teacher')
            ->latest()
            ->paginate(12);

        return view('public.materials', compact('materials'));
    }

    /**
     * Public material detail
     */
    public function showPublic($id)
    {
        $material = Material::query()
            // ->where('is_public', true) // Temporarily commented for debugging
            ->with(['class', 'teacher', 'steps'])
            ->findOrFail($id);

        return view('material-detail', compact('material') + ['is_public' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | STUDENT SIDE
    |--------------------------------------------------------------------------
    */

    /**
     * Student dashboard (home)
     */
    public function index()
    {
        $user = Auth::user();

        $enrolledClassIds = $user->classes()->pluck('classes.id');

        if ($enrolledClassIds->isEmpty()) {
            $scratchMaterials = collect();
            $pictoMaterials   = collect();
        } else {
            $scratchMaterials = Material::query()
                ->whereIn('class_id', $enrolledClassIds)
                ->where('category', 'scratch')
                ->where('is_published', true)
                ->with('class')
                ->latest()
                ->take(6)
                ->get();

            $pictoMaterials = Material::query()
                ->whereIn('class_id', $enrolledClassIds)
                ->where('category', 'picto-ai')
                ->where('is_published', true)
                ->with('class')
                ->latest()
                ->take(6)
                ->get();
        }

        $classes = $user->classes()
            ->withCount('materials')
            ->with('teacher')
            ->get();

        $publicMaterials = Material::query()
            ->where('is_published', true)
            ->with('teacher')
            ->latest()
            ->take(4)
            ->get();

        return view('home', compact(
            'scratchMaterials',
            'pictoMaterials',
            'classes',
            'publicMaterials'
        ));
    }

    /**
     * Student material detail (must be enrolled)
     */
   public function show($id)
    {
        $user = Auth::user();

        $enrolledClassIds = $user->classes()->pluck('classes.id');

        $material = Material::query()
            ->whereIn('class_id', $enrolledClassIds)
            ->with(['class', 'steps'])
            ->findOrFail($id);

        // 🚨 ACCESS CONTROL
        if (! $material->is_published && Auth::guard('teacher')->guest()) {
            abort(403);
        }

        $relatedMaterials = Material::query()
            ->where('category', $material->category)
            ->whereIn('class_id', $enrolledClassIds)
            ->where('id', '!=', $material->id)
            ->where('is_published', true)
            ->with('class')
            ->take(3)
            ->get();

        return view('student.material-detail', compact(
            'material',
            'relatedMaterials'
        ));
    }


    /**
     * Category page (student)
     */
    public function category(string $category)
    {
        $user = Auth::user();

        $enrolledClassIds = $user->classes()->pluck('classes.id');

        $materials = Material::query()
            ->whereIn('class_id', $enrolledClassIds)
            ->where('category', $category)
            ->where('is_published', true)
            ->with('class')
            ->latest()
            ->get();

        return view('category', compact('materials', 'category'));
    }

    /**
     * Join class using class code
     */
    public function joinClass(Request $request)
    {
        $request->validate([
            'class_code' => 'required|string|size:6',
        ]);

        $class = ClassRoom::query()
            ->where('class_code', strtoupper($request->class_code))
            ->where('is_active', true)
            ->first();

        if (! $class) {
            return redirect()
                ->route('student.home')
                ->with('error', 'Invalid class code.');
        }

        $user = Auth::user();

        if ($user->classes()->where('classes.id', $class->id)->exists()) {
            return redirect()
                ->route('student.home')
                ->with('error', 'You are already enrolled in this class.');
        }

        $user->classes()->attach($class->id, [
            'joined_at' => now(),
        ]);

        return redirect()
            ->route('student.home')
            ->with('success', 'Successfully joined ' . $class->name);
    }
}
