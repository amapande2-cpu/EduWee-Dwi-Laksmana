<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialStep extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'material_id',
        'title',
        'description',
        'image',
        'video_url',
        'step_order',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'step_order' => 'integer',
    ];

    /**
     * Parent material relationship
     */
    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Progress for the currently authenticated student
     * (safe for student context, ignored in teacher/admin)
     */
    public function progress()
    {
        if (!auth()->check()) {
            return $this->hasOne(MaterialStepProgress::class)
                ->whereRaw('1 = 0'); // returns empty relation safely
        }

        return $this->hasOne(MaterialStepProgress::class, 'material_step_id')
            ->where('student_id', auth()->id());
    }

    /**
     * Scope: ordered steps (used everywhere)
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('step_order');
    }

    /**
     * Helper: check if step has video
     */
    public function hasVideo(): bool
    {
        return !empty($this->video_url);
    }

    /**
     * Helper: check if step has image
     */
    public function hasImage(): bool
    {
        return !empty($this->image);
    }
}
