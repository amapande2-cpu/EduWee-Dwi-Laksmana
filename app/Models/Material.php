<?php
// app/Models/Material.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    // Make sure class_id and teacher_id are in fillable
    protected $fillable = [
        'class_id',
        'teacher_id',
        'title',
        'category',
        'description',
        'thumbnail',
        'video_url',
        'duration',
        'difficulty',
        'is_published'
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relationships
    public function class()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}