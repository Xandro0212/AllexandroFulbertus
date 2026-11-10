@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="page-head">
        <div>
            <h1>Halo, {{ auth()->user()->name }}</h1>
            <p>Mahasiswa · {{ auth()->user()->program_studi ?? '-' }}</p>
        </div>
        <a href="{{ route('booking.create') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg me-1"></i>Ajukan Peminjaman
        </a>
    </div>

    <div class="stat-grid mb-4">
        <div class="stat-card">
            <div class="stat-ico teal"><i class="bi bi-clipboard-data"></i></div>
            <div><div class="stat-value">{{ $stats['total'] }}</div><div class="stat-label">Total Pengajuan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico amber"><i class="bi bi-hourglass-split"></i></div>
            <div><div class="stat-value">{{ $stats['pending'] }}</div><div class="stat-label">Menunggu</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico green"><i class="bi bi-check-circle"></i></div>
            <div><div class="stat-value">{{ $stats['disetujui'] }}</div><div class="stat-label">Disetujui</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-ico red"><i class="bi bi-x-circle"></i></div>
            <div><div class="stat-value">{{ $stats['ditolak'] }}</div><div class="stat-label">Ditolak</div></div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-title">
                        <h2>Pengajuan Terbaru</h2>
                        <a href="{{ route('booking.index') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    @if($recentBookings->isEmpty())
                        <p class="text-muted mb-0">Belum ada pengajuan. <a href="{{ route('booking.create') }}">Buat pengajuan pertama Anda</a>.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead><tr><th>Kode</th><th>Ruangan</th><th>Tanggal</th><th>Status</th></tr></thead>
                                <tbody>
                                    @foreach($recentBookings as $b)
                                        <tr>
                                            <td><a href="{{ route('booking.show', $b) }}" class="fw-semibold">{{ $b->kode_pengajuan }}</a></td>
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
        </div>
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body">
                    <div class="section-title">
                        <h2>Notifikasi</h2>
                        <a href="{{ route('notification.index') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
                    </div>
                    @if($notifications->isEmpty())
                        <p class="text-muted mb-0">Tidak ada notifikasi.</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($notifications as $n)
                                <li class="list-group-item px-0 {{ is_null($n->read_at) ? 'fw-semibold' : 'text-muted' }}">
                                    <div class="d-flex justify-content-between gap-2">
                                        <span>{{ $n->judul }}</span>
                                        <small class="text-muted text-nowrap">{{ $n->created_at->diffForHumans() }}</small>
                                    </div>
                                    <small class="d-block text-muted fw-normal">{{ Str::limit($n->pesan, 80) }}</small>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('partials.classroom-section')
@endsection
