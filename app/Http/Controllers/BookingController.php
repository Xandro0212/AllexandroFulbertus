<?php

namespace App\Http\Controllers;

use App\Events\BookingCreated;
use App\Events\BookingRejected;
use App\Models\Booking;
use App\Models\Classroom;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $bookings = Booking::with('classroom')
            ->where('user_id', auth()->id())
            ->when($status && array_key_exists($status, Booking::STATUSES), function ($q) use ($status) {
                $q->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('booking.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $classrooms = Classroom::where('status', 'tersedia')->orderBy('kode_ruangan')->get();
        $selectedClassroomId = $request->query('classroom_id');

        return view('booking.create', compact('classrooms', 'selectedClassroomId'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'keperluan' => ['required', 'string', 'min:5', 'max:1000'],
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai' => ['required', 'date_format:H:i'],
            'jam_selesai' => ['required', 'date_format:H:i', 'after:jam_mulai'],
            'jumlah_peserta' => ['required', 'integer', 'min:1', 'max:500'],
        ], [
            'classroom_id.required' => 'Ruangan wajib dipilih.',
            'keperluan.required' => 'Keperluan wajib diisi.',
            'keperluan.min' => 'Keperluan minimal 5 karakter.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.after_or_equal' => 'Tanggal tidak boleh di masa lalu.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'jumlah_peserta.required' => 'Jumlah peserta wajib diisi.',
            'jumlah_peserta.integer' => 'Jumlah peserta harus berupa angka.',
            'jumlah_peserta.max' => 'Jumlah peserta melebihi batas maksimal.',
        ]);

        $jamMulai = strlen($data['jam_mulai']) === 5 ? $data['jam_mulai'].':00' : $data['jam_mulai'];
        $jamSelesai = strlen($data['jam_selesai']) === 5 ? $data['jam_selesai'].':00' : $data['jam_selesai'];

        $classroom = Classroom::findOrFail($data['classroom_id']);

        // ===== Aturan kapasitas: peserta melebihi kapasitas ruangan -> otomatis DITOLAK =====
        if ($data['jumlah_peserta'] > $classroom->kapasitas) {
            $booking = Booking::create([
                'kode_pengajuan' => $this->generateKode(),
                'user_id' => auth()->id(),
                'classroom_id' => $classroom->id,
                'keperluan' => $data['keperluan'],
                'tanggal' => $data['tanggal'],
                'jam_mulai' => $jamMulai,
                'jam_selesai' => $jamSelesai,
                'jumlah_peserta' => $data['jumlah_peserta'],
                'status' => Booking::STATUS_DITOLAK,
                'alasan_penolakan' => "Jumlah peserta ({$data['jumlah_peserta']} orang) melebihi kapasitas ruangan {$classroom->nama_ruangan} (maksimal {$classroom->kapasitas} orang). Pengajuan ditolak otomatis oleh sistem.",
                'approved_at' => now(),
            ]);

            Notification::create([
                'user_id' => auth()->id(),
                'judul' => 'Pengajuan Ditolak Otomatis',
                'pesan' => "Pengajuan {$booking->kode_pengajuan} otomatis DITOLAK karena jumlah peserta ({$data['jumlah_peserta']} orang) melebihi kapasitas {$classroom->nama_ruangan} ({$classroom->kapasitas} orang).",
                'tipe' => 'danger',
            ]);

            $booking->load(['user', 'classroom']);
            event(new BookingRejected($booking));

            return redirect()->route('booking.index')
                ->with('error', "Pengajuan otomatis DITOLAK: jumlah peserta ({$data['jumlah_peserta']} orang) melebihi kapasitas {$classroom->nama_ruangan} (maksimal {$classroom->kapasitas} orang).");
        }

        // Cek bentrokan jadwal
        $conflict = Booking::where('classroom_id', $data['classroom_id'])
            ->where('tanggal', $data['tanggal'])
            ->whereIn('status', [Booking::STATUS_PENDING, Booking::STATUS_DISETUJUI])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->whereBetween('jam_mulai', [$jamMulai, $jamSelesai])
                    ->orWhereBetween('jam_selesai', [$jamMulai, $jamSelesai])
                    ->orWhere(function ($q2) use ($jamMulai, $jamSelesai) {
                        $q2->where('jam_mulai', '<=', $jamMulai)
                            ->where('jam_selesai', '>=', $jamSelesai);
                    });
            })
            ->exists();

        if ($conflict) {
            return back()
                ->withInput()
                ->with('error', 'Ruangan sudah dipesan pada tanggal dan jam tersebut. Silakan pilih waktu lain.');
        }

        $booking = Booking::create([
            'kode_pengajuan' => $this->generateKode(),
            'user_id' => auth()->id(),
            'classroom_id' => $data['classroom_id'],
            'keperluan' => $data['keperluan'],
            'tanggal' => $data['tanggal'],
            'jam_mulai' => $jamMulai,
            'jam_selesai' => $jamSelesai,
            'jumlah_peserta' => $data['jumlah_peserta'],
            'status' => Booking::STATUS_PENDING,
        ]);

        // Notifikasi ke seluruh bagian akademik
        $akademikUsers = User::where('role', User::ROLE_AKADEMIK)->get();
        foreach ($akademikUsers as $akademik) {
            Notification::create([
                'user_id' => $akademik->id,
                'judul' => 'Pengajuan Baru',
                'pesan' => 'Mahasiswa '.auth()->user()->name.' mengajukan peminjaman ruangan dengan kode '.$booking->kode_pengajuan.'.',
                'tipe' => 'info',
            ]);
        }

        // Broadcast realtime
        $booking->load(['user', 'classroom']);
        event(new BookingCreated($booking));

        return redirect()->route('booking.index')
            ->with('success', 'Pengajuan peminjaman berhasil dibuat dengan kode '.$booking->kode_pengajuan.'. Menunggu persetujuan bagian akademik.');
    }

    public function show(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id() || ! auth()->user()->isMahasiswa(), 403);

        $booking->load(['user', 'classroom', 'approver']);

        return view('booking.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_unless($booking->isPending(), 422, 'Hanya pengajuan yang masih menunggu yang bisa dibatalkan.');

        $booking->update(['status' => Booking::STATUS_DIBATALKAN]);

        return back()->with('success', 'Pengajuan '.$booking->kode_pengajuan.' berhasil dibatalkan.');
    }

    private function generateKode(): string
    {
        $prefix = 'PJ-'.date('Ymd').'-';
        $last = Booking::where('kode_pengajuan', 'like', $prefix.'%')
            ->orderByDesc('kode_pengajuan')
            ->value('kode_pengajuan');

        $seq = 1;
        if ($last) {
            $seq = (int) substr($last, -4) + 1;
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
