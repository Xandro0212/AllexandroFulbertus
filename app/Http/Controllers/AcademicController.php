<?php

namespace App\Http\Controllers;

use App\Events\BookingApproved;
use App\Events\BookingRejected;
use App\Models\Booking;
use App\Models\Notification;
use Illuminate\Http\Request;

class AcademicController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $bookings = Booking::with(['user', 'classroom', 'approver'])
            ->when($status && array_key_exists($status, Booking::STATUSES), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $pendingCount = Booking::where('status', Booking::STATUS_PENDING)->count();

        return view('academic.index', compact('bookings', 'pendingCount'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'classroom', 'approver']);

        return view('academic.show', compact('booking'));
    }

    public function approve(Request $request, Booking $booking)
    {
        abort_unless($booking->isPending(), 422, 'Pengajuan ini sudah diproses.');

        $data = $request->validate([
            'catatan_akademik' => ['nullable', 'string', 'max:1000'],
        ]);

        // Cek ulang bentrokan dengan jadwal yang sudah disetujui
        $conflict = Booking::where('classroom_id', $booking->classroom_id)
            ->where('tanggal', $booking->tanggal->toDateString())
            ->whereIn('status', [Booking::STATUS_DISETUJUI])
            ->where('id', '!=', $booking->id)
            ->where(function ($q) use ($booking) {
                $q->whereBetween('jam_mulai', [$booking->jam_mulai, $booking->jam_selesai])
                    ->orWhereBetween('jam_selesai', [$booking->jam_mulai, $booking->jam_selesai])
                    ->orWhere(function ($q2) use ($booking) {
                        $q2->where('jam_mulai', '<=', $booking->jam_mulai)
                            ->where('jam_selesai', '>=', $booking->jam_selesai);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Tidak dapat menyetujui: ruangan sudah disetujui untuk waktu yang sama.');
        }

        $booking->update([
            'status' => Booking::STATUS_DISETUJUI,
            'catatan_akademik' => $data['catatan_akademik'] ?? null,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Notification::create([
            'user_id' => $booking->user_id,
            'judul' => 'Pengajuan Disetujui',
            'pesan' => 'Pengajuan peminjaman '.$booking->kode_pengajuan.' untuk ruangan '.$booking->classroom->nama_ruangan.' telah DISETUJUI.',
            'tipe' => 'success',
        ]);

        // Broadcast realtime ke mahasiswa & channel publik
        $booking->load(['user', 'classroom']);
        event(new BookingApproved($booking));

        return redirect()->route('academic.index')
            ->with('success', 'Pengajuan '.$booking->kode_pengajuan.' berhasil disetujui.');
    }

    public function reject(Request $request, Booking $booking)
    {
        abort_unless($booking->isPending(), 422, 'Pengajuan ini sudah diproses.');

        $data = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ], [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $booking->update([
            'status' => Booking::STATUS_DITOLAK,
            'alasan_penolakan' => $data['alasan_penolakan'],
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        Notification::create([
            'user_id' => $booking->user_id,
            'judul' => 'Pengajuan Ditolak',
            'pesan' => 'Pengajuan peminjaman '.$booking->kode_pengajuan.' DITOLAK. Alasan: '.$data['alasan_penolakan'],
            'tipe' => 'danger',
        ]);

        // Broadcast realtime ke mahasiswa & channel publik
        $booking->load(['user', 'classroom']);
        event(new BookingRejected($booking));

        return redirect()->route('academic.index')
            ->with('success', 'Pengajuan '.$booking->kode_pengajuan.' berhasil ditolak.');
    }
}
