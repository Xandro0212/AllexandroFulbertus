@extends('layouts.app')

@section('title', 'Jadwal Kelas')

@section('content')
    <div class="page-head">
        <div>
            <h1>Jadwal Peminjaman Kelas</h1>
            <p>Kalender peminjaman ruangan per bulan</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('schedule.index', ['month' => $prevMonth]) }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-chevron-left"></i></a>
            <span class="fw-semibold">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</span>
            <a href="{{ route('schedule.index', ['month' => $nextMonth]) }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-chevron-right"></i></a>
        </div>
    </div>

    <div class="alert alert-info">
        <i class="bi bi-info-circle me-1"></i>Jadwal menampilkan pengajuan <strong>disetujui</strong> (hijau) dan <strong>menunggu</strong> (kuning).
    </div>

    <div class="card p-3 overflow-auto">
        @php
            $grouped = $approvedBookings->groupBy(fn($b) => $b->classroom_id . '|' . $b->tanggal->toDateString());
        @endphp
        <table class="table table-bordered align-middle" style="min-width: 900px;">
            <thead class="table-light">
                <tr>
                    <th class="text-center" style="min-width:150px;">Ruangan</th>
                    @foreach($days as $day)
                        <th class="text-center {{ $day === now()->toDateString() ? 'table-primary' : '' }}">
                            <div>{{ \Carbon\Carbon::parse($day)->translatedFormat('d') }}</div>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($day)->translatedFormat('D') }}</small>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($classrooms as $room)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $room->nama_ruangan }}</div>
                            <small class="text-muted">{{ $room->kode_ruangan }}</small>
                        </td>
                        @foreach($days as $day)
                            @php $key = $room->id . '|' . $day; $items = $grouped->get($key, collect()); @endphp
                            <td class="p-1 text-center" style="min-width:70px;">
                                @foreach($items as $b)
                                    <div class="badge {{ $b->status === 'disetujui' ? 'bg-success' : 'bg-warning text-dark' }} d-block mb-1" style="font-size:.7rem;">
                                        {{ substr($b->jam_mulai,0,5) }}-{{ substr($b->jam_selesai,0,5) }}
                                    </div>
                                @endforeach
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
