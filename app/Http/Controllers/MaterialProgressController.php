<?php

namespace App\Http\Controllers;

use App\Models\MaterialProgress;
use App\Models\MaterialStepProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaterialProgressController extends Controller
{
    /**
     * Toggle step completion and recalculate material progress
     */
    public function toggleStepProgress(Request $request, $stepId)
    {
        $studentId = Auth::id();

        $stepProgress = MaterialStepProgress::firstOrCreate(
            [
                'student_id' => $studentId,
                'material_step_id' => $stepId,
            ],
            [
                'is_completed' => false,
            ]
        );

        // Toggle completion
        $stepProgress->update([
            'is_completed' => ! $stepProgress->is_completed,
        ]);

        // Get material from step relation
        $material = $stepProgress->step->material;

        $totalSteps = $material->steps()->count();

        $completedSteps = MaterialStepProgress::where('student_id', $studentId)
            ->whereIn(
                'material_step_id',
                $material->steps()->pluck('id')
            )
            ->where('is_completed', true)
            ->count();

        $progressPercent = (int) (
            ($completedSteps / max($totalSteps, 1)) * 100
        );

        MaterialProgress::updateOrCreate(
            [
                'student_id' => $studentId,
                'material_id' => $material->id,
            ],
            [
                'completed_steps'  => $completedSteps,
                'total_steps'      => $totalSteps,
                'progress_percent' => $progressPercent,
                'is_completed'     => $completedSteps === $totalSteps,
            ]
        );

        return response()->json([
            'success'        => true,
            'progress'       => $progressPercent,
            'completed_steps' => $completedSteps,
            'total_steps'    => $totalSteps,
        ]);
    }
}
