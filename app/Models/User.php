<?php
// app/Models/User.php - Complete updated version

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Add this relationship with explicit foreign keys
    public function classes()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_student', 'user_id', 'class_id')
            ->withTimestamps()
            ->withPivot('joined_at');
    }
}