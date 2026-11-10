@php
    $previewRooms = $classrooms instanceof \Illuminate\Pagination\LengthAwarePaginator
        ? $classrooms->items()
        : ($classrooms instanceof \Illuminate\Support\Collection ? $classrooms->take(4) : collect($classrooms)->take(4));
@endphp
<div class="mt-4">
    <div class="section-title">
        <h2><i class="bi bi-door-open me-1"></i>Daftar Ruangan</h2>
        <a href="{{ route('schedule.classrooms') }}">Lihat semua <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-3">
        @forelse($previewRooms as $room)
            <div class="col-12 col-sm-6 col-lg-3">
                @include('partials.classroom-card', ['room' => $room])
            </div>
        @empty
            <div class="col-12">
                <div class="card p-4 text-center text-muted">Belum ada ruangan terdaftar.</div>
            </div>
        @endforelse
    </div>
</div>
