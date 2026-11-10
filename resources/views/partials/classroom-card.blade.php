@php
    $room = $room ?? $classroom;
    $canBook = auth()->check() && auth()->user()->isMahasiswa() && $room->status === 'tersedia';
    $link = $canBook ? route('booking.create', ['classroom_id' => $room->id]) : null;
@endphp

@if($link)
    <a href="{{ $link }}" class="card classroom-card classroom-card-link h-100 overflow-hidden text-reset">
@else
    <div class="card classroom-card h-100 overflow-hidden">
@endif
        <div class="classroom-card-img">
            <img src="{{ $room->fotoUrl() }}" alt="{{ $room->nama_ruangan }}" loading="lazy">
            @if($room->status === 'tersedia')
                <span class="badge bg-success status-badge">Tersedia</span>
            @else
                <span class="badge bg-danger status-badge">Tidak Tersedia</span>
            @endif
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <h6 class="card-title fw-bold mb-0">{{ $room->nama_ruangan }}</h6>
                <span class="badge bg-secondary">{{ $room->kode_ruangan }}</span>
            </div>
            <div class="text-muted small mb-2">
                @if($room->gedung || $room->lokasi)
                    <div><i class="bi bi-geo-alt me-1"></i>{{ $room->gedung ?? '' }}{{ $room->lokasi ? ' · '.$room->lokasi : '' }}</div>
                @endif
                <div><i class="bi bi-people me-1"></i>Kapasitas {{ $room->kapasitas }} orang</div>
                @if($room->fasilitas)
                    <div class="text-truncate"><i class="bi bi-grid me-1"></i>{{ $room->fasilitas }}</div>
                @endif
            </div>
            @if(isset($showStats) && $showStats)
                <div class="d-flex justify-content-between small text-muted border-top pt-2">
                    <span>Pengajuan: {{ $room->total_pengajuan ?? 0 }}</span>
                    <span>Pending: {{ $room->pengajuan_pending ?? 0 }}</span>
                </div>
            @endif
            @if($canBook)
                <div class="mt-2 pt-2 border-top d-flex align-items-center text-brand small fw-semibold">
                    <i class="bi bi-plus-circle me-1"></i>Klik untuk ajukan peminjaman
                </div>
            @endif
        </div>
@if($link)
    </a>
@else
    </div>
@endif
