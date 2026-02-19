<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'city',
        'country',
        'zip_code',
        'designation',
        'website',
        'description',
        'avatar',
        'cover_image',
        'skills',
        'status',
        'visits',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'skills' => 'array',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /* ================= JWT ================= */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /* ============= Accessors ============= */

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }
}

/**
 *'name', 'email', 'password', 'first_name', 'last_name', 'phone', 'city', 'country', 'zip_code', 'designation', 'website', 'description', 'avatar', 'cover_image', 'skills'
 */
