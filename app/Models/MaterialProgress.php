<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProgress extends Model
{
    protected $fillable = [
        'student_id',
        'material_id',
        'completed_steps',
        'total_steps',
        'progress_percent',
        'is_completed',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
