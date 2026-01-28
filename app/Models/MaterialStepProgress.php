<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialStepProgress extends Model
{
    protected $fillable = [
        'student_id',
        'material_step_id',
        'is_completed',
    ];

    public function step()
    {
        return $this->belongsTo(MaterialStep::class, 'material_step_id');
    }
}
