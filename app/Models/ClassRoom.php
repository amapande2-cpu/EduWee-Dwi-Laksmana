<?php

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

    /**
     * Auto-generate class code on create
     */
    protected static function booted()
    {
        static::creating(function (self $class) {
            if (empty($class->class_code)) {
                $class->class_code = strtoupper(Str::random(6));
            }
        });
    }

    /* ==============================
     |  RELATIONSHIPS
     |==============================*/

    /**
     * Class owner (teacher)
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Students enrolled in the class
     */
    public function students()
    {
        return $this->belongsToMany(
            User::class,
            'class_student',
            'class_id',
            'user_id'
        )
        ->withTimestamps()
        ->withPivot('joined_at');
    }

    /**
     * All materials (teacher view)
     */
    public function materials()
    {
        return $this->hasMany(Material::class, 'class_id');
    }

    /**
     * Only published materials (student view)
     */
    public function publishedMaterials()
    {
        return $this->hasMany(Material::class, 'class_id')
            ->where('is_published', true)
            ->orderByDesc('created_at');
    }

    /* ==============================
     |  SCOPES
     |==============================*/

    /**
     * Active classes only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Class visibility check
     */
    public function isStudentEnrolled(int $userId): bool
    {
        return $this->students()
            ->where('users.id', $userId)
            ->exists();
    }
}
