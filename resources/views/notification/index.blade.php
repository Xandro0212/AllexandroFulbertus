@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <div class="page-head">
        <div>
            <h1>Notifikasi</h1>
            <p>Pemberitahuan terkait pengajuan peminjaman Anda</p>
        </div>
        @if($unreadNotificationsCount > 0)
            <form method="POST" action="{{ route('notification.readAll') }}">
                @csrf
                <button type="submit" class="btn btn-outline-brand btn-sm"><i class="bi bi-check2-all me-1"></i>Tandai Semua Dibaca</button>
            </form>
        @endif
    </div>

    <div class="card p-3">
        @if($notifications->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-bell-slash text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2 mb-0">Tidak ada notifikasi.</p>
            </div>
        @else
            <ul class="list-group list-group-flush">
                @foreach($notifications as $n)
                    @php
                        $icon = match($n->tipe) {
                            'success' => 'bi-check-circle-fill text-success',
                            'danger' => 'bi-x-circle-fill text-danger',
                            'warning' => 'bi-exclamation-triangle-fill text-warning',
                            default => 'bi-info-circle-fill text-primary',
                        };
                    @endphp
                    <li class="list-group-item d-flex justify-content-between align-items-start {{ is_null($n->read_at) ? 'bg-light' : '' }}">
                        <div class="d-flex">
                            <i class="bi {{ $icon }} me-3 mt-1"></i>
                            <div>
                                <div class="fw-semibold">{{ $n->judul }}</div>
                                <div class="text-muted small">{{ $n->pesan }}</div>
                                <small class="text-muted">{{ $n->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if(is_null($n->read_at))
                            <form method="POST" action="{{ route('notification.read', $n) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link">Tandai dibaca</button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
            <div class="mt-3">{{ $notifications->links() }}</div>
        @endif
    </div>
@endsection
