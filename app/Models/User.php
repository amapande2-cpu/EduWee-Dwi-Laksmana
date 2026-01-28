<?php

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
        'profile_photo_blob',
        'profile_photo_mime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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

    /**
     * Classes joined by the student
     */
    public function classes()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'class_student',   // pivot table
            'user_id',         // THIS user model FK
            'class_id'         // class FK
        )
        ->withTimestamps()
        ->withPivot('joined_at');
    }
}
