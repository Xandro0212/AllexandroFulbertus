@extends('layouts.app')

@section('title', $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-4">{{ $isEdit ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h4>

                <form method="POST" action="{{ $isEdit ? route('admin.users.update', $user) : route('admin.users.store') }}">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIM</label>
                            <input type="text" name="nim" value="{{ old('nim', $user->nim) }}" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">No. Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Program Studi</label>
                            <input type="text" name="program_studi" value="{{ old('program_studi', $user->program_studi) }}" class="form-control">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Peran <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required>
                            @foreach(\App\Models\User::ROLES as $key => $label)
                                <option value="{{ $key }}" {{ old('role', $user->role) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password {{ $isEdit ? '(kosongkan jika tidak diubah)' : '' }} <span class="text-danger">{{ $isEdit ? '' : '*' }}</span></label>
                            <input type="password" name="password" class="form-control" {{ $isEdit ? '' : 'required' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-save me-1"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
