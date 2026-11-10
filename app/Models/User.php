<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_MAHASISWA = 'mahasiswa';
    public const ROLE_AKADEMIK = 'akademik';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_MAHASISWA => 'Mahasiswa',
        self::ROLE_AKADEMIK => 'Bagian Akademik',
        self::ROLE_ADMIN => 'Admin',
    ];

    protected $fillable = [
        'name',
        'email',
        'nim',
        'phone',
        'program_studi',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isAkademik(): bool
    {
        return $this->role === self::ROLE_AKADEMIK;
    }

    public function isMahasiswa(): bool
    {
        return $this->role === self::ROLE_MAHASISWA;
    }

    public function roleLabel(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}
