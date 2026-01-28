<?php
// app/Models/Teacher.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'subject',
        'profile_photo_blob',
        'profile_photo_mime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Inline (data URI) source for profile photo.
     *
     * Note: embeds the image into HTML; keep uploads small.
     */
    public function getProfilePhotoDataUriAttribute(): ?string
    {
        if (empty($this->profile_photo_blob) || empty($this->profile_photo_mime)) {
            return null;
        }

        return 'data:' . $this->profile_photo_mime . ';base64,' . base64_encode($this->profile_photo_blob);
    }

    public function hasProfilePhoto(): bool
    {
        return !empty($this->profile_photo_blob) && !empty($this->profile_photo_mime);
    }

    public function classes()
    {
        return $this->hasMany(ClassRoom::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }
}
