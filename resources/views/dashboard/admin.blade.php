@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Dashboard Admin</h1>
            <p>Kelola seluruh sistem peminjaman kelas</p>
        </div>
    </div>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="stat-ico teal"><i class="bi bi-people"></i></div>
            <div><div class="stat-value">{{ $stats['total_mahasiswa'] }}</div><div class="stat-label">Mahasiswa</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico blue"><i class="bi bi-person-badge"></i></div>
            <div><div class="stat-value">{{ $stats['total_akademik'] }}</div><div class="stat-label">Bagian Akademik</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico green"><i class="bi bi-door-open"></i></div>
            <div><div class="stat-value">{{ $stats['total_ruangan'] }}</div><div class="stat-label">Ruangan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico orange"><i class="bi bi-clipboard-data"></i></div>
            <div><div class="stat-value">{{ $stats['total_pengajuan'] }}</div><div class="stat-label">Total Pengajuan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico amber"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu</div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="section-title">
                <h2>Pengajuan Terbaru</h2>
                <a href="{{ route('admin.bookings') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
            </div>
            @if($recentBookings->isEmpty())
                <p class="text-muted mb-0">Belum ada pengajuan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead><tr><th>Kode</th><th>Mahasiswa</th><th>Ruangan</th><th>Tanggal</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($recentBookings as $b)
                                <tr>
                                    <td class="fw-semibold">{{ $b->kode_pengajuan }}</td>
                                    <td>{{ $b->user->name }}</td>
                                    <td>{{ $b->classroom->nama_ruangan }}</td>
                                    <td>{{ $b->tanggal->format('d M Y') }}</td>
                                    <td>@include('partials.status-badge', ['booking' => $b])</td>
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
