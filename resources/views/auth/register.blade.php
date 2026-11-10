@extends('layouts.auth')

@section('title', 'Registrasi Mahasiswa')

@section('content')
    <div class="auth-head">
        <h3>Buat akun mahasiswa</h3>
        <p>Daftarkan akun untuk mengajukan peminjaman ruangan kelas.</p>
    </div>

    <form method="POST" action="{{ route('register.post') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Nama sesuai KTM" required autofocus>
        </div>

        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">NIM</label>
                <input type="text" name="nim" value="{{ old('nim') }}" class="form-control" placeholder="22010001" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">No. Telepon</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="nama@idb.ac.id" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Program Studi</label>
            <select name="program_studi" class="form-select" required>
                <option value="" disabled {{ old('program_studi') ? '' : 'selected' }}>Pilih Program Studi</option>
                @foreach(['Desain Komunikasi Visual', 'Desain Interior', 'Desain Mode', 'Teknik Informatika', 'Sistem Informasi', 'Bisnis Digital', 'Manajemen'] as $prodi)
                    <option value="{{ $prodi }}" {{ old('program_studi') === $prodi ? 'selected' : '' }}>{{ $prodi }}</option>
                @endforeach
            </select>
        </div>

        <div class="row g-2">
            <div class="col-md-6 mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Min. 8 karakter" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
            </div>
        </div>

        <button type="submit" class="btn btn-brand w-100 py-2 mt-1">
            <i class="bi bi-person-plus me-1"></i>Daftar
        </button>
    </form>

    <div class="auth-footer-note">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="fw-semibold">Masuk di sini</a>
    </div>
@endsection
