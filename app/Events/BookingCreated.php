<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('bookings'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'booking.created';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'kode' => $this->booking->kode_pengajuan,
            'nama_mahasiswa' => $this->booking->user->name,
            'ruangan' => $this->booking->classroom->nama_ruangan,
            'tanggal' => $this->booking->tanggal->format('d M Y'),
            'jam' => substr($this->booking->jam_mulai, 0, 5).'-'.substr($this->booking->jam_selesai, 0, 5),
            'status' => $this->booking->status,
            'created_at' => $this->booking->created_at->diffForHumans(),
        ];
    }
}
