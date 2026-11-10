@extends('layouts.app')

@section('title', 'Persetujuan Akademik')

@section('content')
    <div class="page-head">
        <div>
            <h1>Persetujuan Peminjaman Kelas</h1>
            <p>Tinjau dan proses pengajuan peminjaman dari mahasiswa</p>
        </div>
    </div>

    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('academic.index') }}" class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">Semua</a>
            @foreach(\App\Models\Booking::STATUSES as $key => $label)
                <a href="{{ route('academic.index', ['status' => $key]) }}" class="btn btn-outline-secondary {{ request('status') === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="card p-3">
        @if($bookings->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size:3rem;"></i>
                <p class="text-muted mt-2 mb-0">Tidak ada pengajuan.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Mahasiswa</th>
                            <th>Ruangan</th>
                            <th>Tanggal & Waktu</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                            <tr>
                                <td>{{ $b->kode_pengajuan }}</td>
                                <td>
                                    <div>{{ $b->user->name }}</div>
                                    <small class="text-muted">{{ $b->user->nim ?? '' }}</small>
                                </td>
                                <td>{{ $b->classroom->nama_ruangan }}</td>
                                <td>{{ $b->tanggal->format('d M Y') }}<br><small class="text-muted">{{ substr($b->jam_mulai,0,5) }}-{{ substr($b->jam_selesai,0,5) }}</small></td>
                                <td>{{ Str::limit($b->keperluan, 30) }}</td>
                                <td>@include('partials.status-badge', ['booking' => $b])</td>
                                <td class="text-end">
                                    <a href="{{ route('academic.show', $b) }}" class="btn btn-sm btn-outline-primary">Tinjau</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $bookings->links() }}</div>
        @endif
    </div>
@endsection
