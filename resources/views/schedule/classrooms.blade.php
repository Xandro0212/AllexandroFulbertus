@extends('layouts.app')

@section('title', 'Daftar Ruangan')

@section('content')
    <div class="page-head">
        <div>
            <h1>Daftar Ruangan</h1>
            <p>Ruangan kelas yang tersedia untuk dipinjam
                @if(auth()->user()->isMahasiswa())— klik ruangan untuk langsung mengajukan peminjaman.@endif
            </p>
        </div>
    </div>

    <div class="row g-3">
        @forelse($classrooms as $room)
            <div class="col-12 col-sm-6 col-lg-4 col-xl-3">
                @include('partials.classroom-card', ['room' => $room, 'showStats' => true])
            </div>
        @empty
            <div class="col-12">
                <div class="card p-4 text-center text-muted">Belum ada ruangan terdaftar.</div>
            </div>
        @endforelse
    </div>
@endsection
