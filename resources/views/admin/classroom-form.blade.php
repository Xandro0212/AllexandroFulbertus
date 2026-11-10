@extends('layouts.app')

@section('title', $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-4">{{ $isEdit ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h4>

                <form method="POST" action="{{ $isEdit ? route('admin.classrooms.update', $classroom) : route('admin.classrooms.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if($isEdit) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="kode_ruangan" value="{{ old('kode_ruangan', $classroom->kode_ruangan) }}" class="form-control" placeholder="R-101" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Ruangan <span class="text-danger">*</span></label>
                            <input type="text" name="nama_ruangan" value="{{ old('nama_ruangan', $classroom->nama_ruangan) }}" class="form-control" placeholder="Ruang R-101" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gedung</label>
                            <input type="text" name="gedung" value="{{ old('gedung', $classroom->gedung) }}" class="form-control" placeholder="Gedung A">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi (Lantai)</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $classroom->lokasi) }}" class="form-control" placeholder="Lantai 1">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kapasitas <span class="text-danger">*</span></label>
                            <input type="number" name="kapasitas" value="{{ old('kapasitas', $classroom->kapasitas) }}" min="1" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select name="status" class="form-select" required>
                                <option value="tersedia" {{ old('status', $classroom->status) === 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="tidak_tersedia" {{ old('status', $classroom->status) === 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Fasilitas</label>
                        <textarea name="fasilitas" rows="2" class="form-control" placeholder="Proyektor, AC, Whiteboard">{{ old('fasilitas', $classroom->fasilitas) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Foto Ruangan</label>
                        @if($classroom->foto)
                            <div class="mb-2">
                                <img src="{{ $classroom->fotoUrl() }}" alt="Foto ruangan" class="rounded" style="height:120px; width:auto; object-fit:cover;">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                        <div class="form-text">Format JPG/PNG/WEBP/SVG, maksimal 4 MB. Kosongkan jika tidak ingin mengubah foto.</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-save me-1"></i>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan' }}
                        </button>
                        <a href="{{ route('admin.classrooms') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
