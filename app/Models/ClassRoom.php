<?php
// app/Models/ClassRoom.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'teacher_id',
        'name',
        'class_code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate class code when creating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($class) {
            if (empty($class->class_code)) {
                $class->class_code = strtoupper(Str::random(6));
            }
        });
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'user_id')
            ->withTimestamps()
            ->withPivot('joined_at')
            ->using(\Illuminate\Database\Eloquent\Relations\Pivot::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }
}