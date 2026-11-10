@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="page-head">
        <div>
            <h1>Manajemen Pengguna</h1>
            <p>Kelola akun mahasiswa, akademik, dan admin</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-brand">
            <i class="bi bi-person-plus me-1"></i>Tambah Pengguna
        </a>
    </div>

    <div class="mb-3">
        <div class="btn-group" role="group">
            <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary {{ !request('role') ? 'active' : '' }}">Semua</a>
            @foreach(\App\Models\User::ROLES as $key => $label)
                <a href="{{ route('admin.users', ['role' => $key]) }}" class="btn btn-outline-secondary {{ request('role') === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>NIM</th>
                        <th>Program Studi</th>
                        <th>Peran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nim ?? '-' }}</td>
                            <td>{{ $user->program_studi ?? '-' }}</td>
                            <td>
                                @php $colors = ['mahasiswa' => 'primary', 'akademik' => 'info', 'admin' => 'danger']; @endphp
                                <span class="badge bg-{{ $colors[$user->role] ?? 'secondary' }}">{{ $user->roleLabel() }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline" onsubmit="return confirm('Hapus pengguna ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $users->links() }}</div>
    </div>
@endsection
