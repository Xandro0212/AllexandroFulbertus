@switch($booking->status)
    @case('pending')
        <span class="badge-soft is-pending">Menunggu Persetujuan</span>
        @break
    @case('disetujui')
        <span class="badge-soft is-success">Disetujui</span>
        @break
    @case('ditolak')
        <span class="badge-soft is-danger">Ditolak</span>
        @break
    @case('dibatalkan')
        <span class="badge-soft is-neutral">Dibatalkan</span>
        @break
    @default
        <span class="badge-soft is-neutral">{{ $booking->status }}</span>
@endswitch
