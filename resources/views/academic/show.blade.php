@extends('layouts.app')

@section('title', 'Tinjau Pengajuan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">{{ $booking->kode_pengajuan }}</h4>
                        <span class="text-muted">Diajukan {{ $booking->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>@include('partials.status-badge', ['booking' => $booking])</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6"><div class="text-muted small">Mahasiswa</div><div class="fw-semibold">{{ $booking->user->name }}</div><small class="text-muted">{{ $booking->user->nim ?? '-' }} · {{ $booking->user->program_studi ?? '-' }}</small></div>
                    <div class="col-md-6"><div class="text-muted small">Kontak</div><div class="fw-semibold">{{ $booking->user->email }}</div><small class="text-muted">{{ $booking->user->phone ?? '-' }}</small></div>
                    <div class="col-md-6"><div class="text-muted small">Ruangan</div><div class="fw-semibold">{{ $booking->classroom->nama_ruangan }}</div><small class="text-muted">{{ $booking->classroom->kode_ruangan }} · Kap. {{ $booking->classroom->kapasitas }}</small></div>
                    <div class="col-md-6"><div class="text-muted small">Tanggal & Waktu</div><div class="fw-semibold">{{ $booking->tanggal->format('d F Y') }}</div><small class="text-muted">{{ substr($booking->jam_mulai,0,5) }} - {{ substr($booking->jam_selesai,0,5) }} WITA</small></div>
                    <div class="col-md-6"><div class="text-muted small">Jumlah Peserta</div><div class="fw-semibold">{{ $booking->jumlah_peserta }} orang <span class="text-muted fw-normal">/ kapasitas {{ $booking->classroom->kapasitas }}</span></div></div>
                </div>

                <div class="mb-4">
                    <div class="text-muted small">Keperluan</div>
                    <p class="mb-0">{{ $booking->keperluan }}</p>
                </div>

                @if($booking->status === 'disetujui')
                    <div class="alert alert-success mb-3">
                        <strong>Catatan:</strong> {{ $booking->catatan_akademik ?? 'Tidak ada catatan.' }}<br>
                        <small>Disetujui oleh {{ $booking->approver?->name }} pada {{ $booking->approved_at?->format('d M Y H:i') }}</small>
                    </div>
                @elseif($booking->status === 'ditolak')
                    <div class="alert alert-danger mb-3">
                        <strong>Alasan Penolakan:</strong> {{ $booking->alasan_penolakan }}<br>
                        <small>Ditolak oleh {{ $booking->approver?->name }} pada {{ $booking->approved_at?->format('d M Y H:i') }}</small>
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('academic.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                </div>
            </div>

            @if($booking->isPending())
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Proses Pengajuan</h5>

                    <form method="POST" action="{{ route('academic.approve', $booking) }}" class="mb-3">
                        @csrf
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="catatan_akademik" rows="2" class="form-control mb-2" placeholder="Catatan untuk mahasiswa, misal: silakan ambil kunci di resepsionis."></textarea>
                        <button type="submit" class="btn btn-success" onclick="return confirm('Setujui pengajuan ini?');">
                            <i class="bi bi-check-circle me-1"></i>Setujui
                        </button>
                    </form>

                    <hr>

                    <form method="POST" action="{{ route('academic.reject', $booking) }}">
                        @csrf
                        <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="alasan_penolakan" rows="2" class="form-control mb-2" placeholder="Jelaskan alasan penolakan..." required></textarea>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak pengajuan ini?');">
                            <i class="bi bi-x-circle me-1"></i>Tolak
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
