<?php
// app/Http/Controllers/MaterialController.php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get enrolled class IDs - handle case when no classes enrolled
        $enrolledClassIds = $user->classes()->pluck('classes.id');

        // If no classes, return empty collections
        if ($enrolledClassIds->isEmpty()) {
            $scratchMaterials = collect();
            $pictoMaterials = collect();
        } else {
            // Get materials from enrolled classes
            $scratchMaterials = Material::whereIn('class_id', $enrolledClassIds)
                ->where('category', 'scratch')
                ->where('is_published', true)
                ->with('class') // Load class relationship
                ->latest()
                ->take(6)
                ->get();

            $pictoMaterials = Material::whereIn('class_id', $enrolledClassIds)
                ->where('category', 'picto-ai')
                ->where('is_published', true)
                ->with('class') // Load class relationship
                ->latest()
                ->take(6)
                ->get();
        }

        // Get enrolled classes with material count
        $classes = $user->classes()
            ->withCount('materials')
            ->with('teacher')
            ->get();

        return view('home', compact('scratchMaterials', 'pictoMaterials', 'classes'));
    }

    public function category($category)
    {
        $user = Auth::user();
        $enrolledClassIds = $user->classes()->pluck('classes.id');

        $materials = Material::whereIn('class_id', $enrolledClassIds)
            ->where('category', $category)
            ->where('is_published', true)
            ->with('class') // Load class relationship
            ->latest()
            ->get();

        return view('category', compact('materials', 'category'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $enrolledClassIds = $user->classes()->pluck('classes.id');

        $material = Material::whereIn('class_id', $enrolledClassIds)
            ->with('class') // Load class relationship
            ->findOrFail($id);
        
        $relatedMaterials = Material::where('category', $material->category)
            ->whereIn('class_id', $enrolledClassIds)
            ->where('id', '!=', $id)
            ->where('is_published', true)
            ->with('class') // Load class relationship
            ->take(3)
            ->get();

        return view('material-detail', compact('material', 'relatedMaterials'));
    }

    public function joinClass(Request $request)
    {
        try {
            $validated = $request->validate([
                'class_code' => 'required|string|size:6'
            ]);

            $class = ClassRoom::where('class_code', strtoupper($validated['class_code']))
                ->where('is_active', true)
                ->first();

            if (!$class) {
                return redirect()->route('student.home')
                    ->with('error', 'Invalid class code. Please check and try again.');
            }

            $user = Auth::user();

            // Check if already enrolled
            if ($user->classes()->where('classes.id', $class->id)->exists()) {
                return redirect()->route('student.home')
                    ->with('error', 'You are already enrolled in this class!');
            }

            $user->classes()->attach($class->id, ['joined_at' => now()]);

            return redirect()->route('student.home')
                ->with('success', 'Successfully joined ' . $class->name . '!');
        } catch (\Exception $e) {
            \Log::error('Join class error: ' . $e->getMessage());
            return redirect()->route('student.home')
                ->with('error', 'An error occurred. Please try again.');
        }
    }
}