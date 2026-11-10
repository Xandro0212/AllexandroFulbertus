@extends('layouts.app')

@section('title', 'Semua Pengajuan')

@section('content')
    <div class="page-head">
        <div>
            <h1>Semua Pengajuan Peminjaman</h1>
            <p>Seluruh pengajuan dari semua mahasiswa</p>
        </div>
    </div>

    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
            @foreach(\App\Models\Booking::STATUSES as $key => $label)
                <a href="{{ route('admin.bookings', ['status' => $key]) }}" class="btn btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="card p-3">
        @if($bookings->isEmpty())
            <div class="text-center py-5 text-muted">Tidak ada pengajuan.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Mahasiswa</th>
                            <th>Ruangan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Status</th>
                            <th>Pemroses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td>{{ $b->kode_pengajuan }}</td>
                                <td>{{ $b->user->name }}</td>
                                <td>{{ $b->classroom->nama_ruangan }}</td>
                                <td>{{ $b->tanggal->format('d M Y') }} · {{ substr($b->jam_mulai,0,5) }}-{{ substr($b->jam_selesai,0,5) }}</td>
                                <td>@include('partials.status-badge', ['booking' => $b])</td>
                                <td>{{ $b->approver?->name ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
