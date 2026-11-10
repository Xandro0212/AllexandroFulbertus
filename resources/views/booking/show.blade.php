@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h4 class="fw-bold mb-0">{{ $booking->kode_pengajuan }}</h4>
                        <span class="text-muted">Diajukan {{ $booking->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div>@include('partials.status-badge', ['booking' => $booking])</div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6"><div class="text-muted small">Ruangan</div><div class="fw-semibold">{{ $booking->classroom->nama_ruangan }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Lokasi</div><div class="fw-semibold">{{ $booking->classroom->gedung ?? '-' }} {{ $booking->classroom->lokasi ? '· '.$booking->classroom->lokasi : '' }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Tanggal</div><div class="fw-semibold">{{ $booking->tanggal->format('d F Y') }}</div></div>
                    <div class="col-md-6"><div class="text-muted small">Waktu</div><div class="fw-semibold">{{ substr($booking->jam_mulai,0,5) }} - {{ substr($booking->jam_selesai,0,5) }} WITA</div></div>
                    <div class="col-md-6"><div class="text-muted small">Jumlah Peserta</div><div class="fw-semibold">{{ $booking->jumlah_peserta }} orang <span class="text-muted fw-normal">/ kapasitas {{ $booking->classroom->kapasitas }}</span></div></div>
                    <div class="col-md-6"><div class="text-muted small">Pemohon</div><div class="fw-semibold">{{ $booking->user->name }} ({{ $booking->user->nim ?? '-' }})</div></div>
                </div>

                <div class="mb-4">
                    <div class="text-muted small">Keperluan</div>
                    <p class="mb-0">{{ $booking->keperluan }}</p>
                </div>

                @if($booking->status === 'disetujui' && $booking->catatan_akademik)
                    <div class="alert alert-success mb-4">
                        <strong>Catatan Akademik:</strong> {{ $booking->catatan_akademik }}
                    </div>
                @endif

                @if($booking->status === 'ditolak')
                    <div class="alert alert-danger mb-4">
                        <strong>Alasan Penolakan:</strong> {{ $booking->alasan_penolakan }}
                    </div>
                @endif

                @if($booking->approver)
                    <div class="text-muted small mb-4">
                        Diproses oleh {{ $booking->approver->name }} pada {{ $booking->approved_at?->format('d M Y H:i') }}
                    </div>
                @endif

                <div class="d-flex gap-2">
                    <a href="{{ route('booking.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
                    @if($booking->isPending() && $booking->user_id === auth()->id())
                        <form method="POST" action="{{ route('booking.cancel', $booking) }}" onsubmit="return confirm('Batalkan pengajuan ini?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-x-circle me-1"></i>Batalkan Pengajuan</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
