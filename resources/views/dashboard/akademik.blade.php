@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard Akademik</h1>
            <p>Kelola persetujuan peminjaman kelas</p>
        </div>
        <a href="{{ route('academic.index') }}" class="btn btn-brand">
            <i class="bi bi-check2-circle me-1"></i>Kelola Persetujuan
        </a>
    </div>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="stat-ico amber"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu Persetujuan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico green"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-value">{{ $stats['disetujui'] }}</div><div class="stat-label">Disetujui</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red"><i class="bi bi-x-circle"></i></div>
            <div><div class="stat-value">{{ $stats['ditolak'] }}</div><div class="stat-label">Ditolak</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico teal"><i class="bi bi-door-open"></i></div>
            <div><div class="stat-value">{{ $stats['total_ruangan'] }}</div><div class="stat-label">Total Ruangan</div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-title">
                <h2>Pengajuan Menunggu Persetujuan</h2>
                <a href="{{ route('academic.index', ['status' => 'pending']) }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
            </div>
            @if($pendingBookings->isEmpty())
                <p class="text-muted mb-0">Tidak ada pengajuan yang menunggu persetujuan. 🎉</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr><th>Kode</th><th>Mahasiswa</th><th>Ruangan</th><th>Tanggal &amp; Waktu</th><th class="text-end">Aksi</th></tr>
                        </thead>
                        <tbody>
                            @foreach($pendingBookings as $b)
                                <tr>
                                    <td class="fw-semibold">{{ $b->kode_pengajuan }}</td>
                                    <td>{{ $b->user->name }}</td>
                                    <td>{{ $b->classroom->nama_ruangan }}</td>
                                    <td>{{ $b->tanggal->format('d M Y') }} · {{ substr($b->jam_mulai,0,5) }}-{{ substr($b->jam_selesai,0,5) }}</td>
                                    <td class="text-end"><a href="{{ route('academic.show', $b) }}" class="btn btn-sm btn-outline-brand">Tinjau</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @include('partials.classroom-section')
@endsection
