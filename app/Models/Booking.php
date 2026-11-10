<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_DISETUJUI = 'disetujui';
    public const STATUS_DITOLAK = 'ditolak';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUSES = [
        self::STATUS_PENDING => 'Menunggu Persetujuan',
        self::STATUS_DISETUJUI => 'Disetujui',
        self::STATUS_DITOLAK => 'Ditolak',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    protected $fillable = [
        'kode_pengajuan',
        'user_id',
        'classroom_id',
        'keperluan',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'jumlah_peserta',
        'status',
        'catatan_akademik',
        'alasan_penolakan',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'approved_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
