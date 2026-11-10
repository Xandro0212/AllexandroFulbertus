<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'gedung',
        'lokasi',
        'kapasitas',
        'fasilitas',
        'status',
        'foto',
    ];

    protected function casts(): array
    {
        return [
            'kapasitas' => 'integer',
        ];
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isTersedia(): bool
    {
        return $this->status === 'tersedia';
    }

    /**
     * URL foto ruangan, dengan fallback ke placeholder default.
     */
    public function fotoUrl(): string
    {
        if ($this->foto) {
            return asset('images/' . $this->foto);
        }

        return asset('images/ruangan/default.svg');
    }
}
