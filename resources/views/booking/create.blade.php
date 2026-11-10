@extends('layouts.app')

@section('title', 'Buat Pengajuan')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="fw-bold mb-1">Buat Pengajuan Peminjaman Kelas</h4>
                <p class="text-muted mb-4">Isi formulir berikut. Pengajuan akan diverifikasi oleh Bagian Akademik.</p>

                <form method="POST" action="{{ route('booking.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ruangan <span class="text-danger">*</span></label>
                        <select name="classroom_id" id="classroomSelect" class="form-select" required>
                            <option value="" disabled {{ (old('classroom_id', $selectedClassroomId ?? '')) ? '' : 'selected' }}>Pilih ruangan</option>
                            @foreach($classrooms as $room)
                                <option value="{{ $room->id }}" data-kapasitas="{{ $room->kapasitas }}" {{ old('classroom_id', $selectedClassroomId ?? '') == $room->id ? 'selected' : '' }}>
                                    {{ $room->kode_ruangan }} — {{ $room->nama_ruangan }} (Kap. {{ $room->kapasitas }} orang)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keperluan <span class="text-danger">*</span></label>
                        <textarea name="keperluan" rows="3" class="form-control" placeholder="Contoh: Rapat koordinasi organisasi, latihan, seminar, dll." required>{{ old('keperluan') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal" value="{{ old('tanggal') }}" min="{{ now()->toDateString() }}" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai') }}" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Jumlah Peserta <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah_peserta" id="jumlahPeserta" value="{{ old('jumlah_peserta', 1) }}" min="1" class="form-control" required>
                        <div id="kapasitasInfo" class="form-text">Pilih ruangan untuk melihat kapasitas maksimal.</div>
                        <div id="kapasitasWarning" class="alert alert-danger d-none mt-2 mb-0 py-2 small"></div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-brand">
                            <i class="bi bi-send me-1"></i>Kirim Pengajuan
                        </button>
                        <a href="{{ route('booking.index') }}" class="btn btn-outline-brand">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var select = document.getElementById('classroomSelect');
            var input = document.getElementById('jumlahPeserta');
            var info = document.getElementById('kapasitasInfo');
            var warn = document.getElementById('kapasitasWarning');
            if (!select || !input) return;

            function update() {
                var opt = select.options[select.selectedIndex];
                var kap = opt ? parseInt(opt.getAttribute('data-kapasitas') || '0', 10) : 0;
                if (!kap) {
                    info.textContent = 'Pilih ruangan untuk melihat kapasitas maksimal.';
                    warn.classList.add('d-none');
                    input.classList.remove('is-invalid');
                    return;
                }
                info.textContent = 'Kapasitas maksimal ruangan ini: ' + kap + ' orang.';
                var val = parseInt(input.value || '0', 10);
                if (val > kap) {
                    warn.innerHTML = '<i class="bi bi-exclamation-triangle me-1"></i>Jumlah peserta (' + val + ' orang) melebihi kapasitas (' + kap + ' orang). Pengajuan akan <strong>otomatis DITOLAK</strong> jika dilanjutkan.';
                    warn.classList.remove('d-none');
                    input.classList.add('is-invalid');
                } else {
                    warn.classList.add('d-none');
                    input.classList.remove('is-invalid');
                }
            }
            select.addEventListener('change', update);
            input.addEventListener('input', update);
            update();
        })();
    </script>
@endsection
