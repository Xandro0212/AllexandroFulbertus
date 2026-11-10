@extends('layouts.app')

@section('title', 'Kelola Ruangan')

@section('content')
    <div class="page-head">
        <div>
            <h1>Kelola Ruangan</h1>
            <p>Data ruangan kelas beserta foto dan fasilitas</p>
        </div>
        <a href="{{ route('admin.classrooms.create') }}" class="btn btn-brand">
            <i class="bi bi-plus-lg me-1"></i>Tambah Ruangan
        </a>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Foto</th>
                        <th>Kode</th>
                        <th>Nama Ruangan</th>
                        <th>Gedung</th>
                        <th>Kapasitas</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($classrooms as $room)
                        <tr>
                            <td>
                                <img src="{{ $room->fotoUrl() }}" alt="{{ $room->nama_ruangan }}" class="rounded" style="width:56px; height:40px; object-fit:cover;">
                            </td>
                            <td>{{ $room->kode_ruangan }}</td>
                            <td>{{ $room->nama_ruangan }}</td>
                            <td>{{ $room->gedung ?? '-' }}</td>
                            <td>{{ $room->kapasitas }} orang</td>
                            <td>
                                @if($room->status === 'tersedia')
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-danger">Tidak Tersedia</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.classrooms.edit', $room) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.classrooms.destroy', $room) }}" class="d-inline" onsubmit="return confirm('Hapus ruangan ini?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $classrooms->links() }}</div>
    </div>
@endsection
