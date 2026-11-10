<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingApproved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Booking $booking)
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.'.$this->booking->user_id),
            new Channel('bookings'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'booking.approved';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'kode' => $this->booking->kode_pengajuan,
            'ruangan' => $this->booking->classroom->nama_ruangan,
            'tanggal' => $this->booking->tanggal->format('d M Y'),
            'status' => $this->booking->status,
            'catatan' => $this->booking->catatan_akademik,
        ];
    }
}
