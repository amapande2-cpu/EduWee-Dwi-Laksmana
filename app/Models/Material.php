<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'category',
        'description',
        'duration',
        'difficulty',
        'cover_image',
        'quiz_url',
        'is_published',
        'is_public'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /* =======================
        Relationships
    ======================== */

    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

   public function steps()
    {
        return $this->hasMany(MaterialStep::class)->orderBy('step_order');
    }   

    public function progress()
    {
        return $this->hasOne(MaterialProgress::class)
            ->where('student_id', auth()->id());
    }

}
