<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Classroom;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== Pengguna =====
        $admin = User::create([
            'name' => 'Administrator SIPKA',
            'email' => 'admin@idb.ac.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ]);

        $akademik = User::create([
            'name' => 'Bagian Akademik IDB Bali',
            'email' => 'akademik@idb.ac.id',
            'password' => Hash::make('password'),
            'role' => User::ROLE_AKADEMIK,
        ]);

        $mahasiswa1 = User::create([
            'name' => 'Putu Ayu Sari',
            'email' => 'mahasiswa@idb.ac.id',
            'nim' => '22010001',
            'phone' => '081234567801',
            'program_studi' => 'Desain Komunikasi Visual',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MAHASISWA,
        ]);

        $mahasiswa2 = User::create([
            'name' => 'Made Wira Dharma',
            'email' => 'wira@idb.ac.id',
            'nim' => '22010002',
            'phone' => '081234567802',
            'program_studi' => 'Teknik Informatika',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MAHASISWA,
        ]);

        // ===== Ruangan =====
        $classrooms = [
            ['kode_ruangan' => 'R-101', 'nama_ruangan' => 'Ruang R-101', 'gedung' => 'Gedung A', 'lokasi' => 'Lantai 1', 'kapasitas' => 40, 'fasilitas' => 'Proyektor, AC, Whiteboard', 'foto' => 'ruangan/r-101.svg'],
            ['kode_ruangan' => 'R-102', 'nama_ruangan' => 'Ruang R-102', 'gedung' => 'Gedung A', 'lokasi' => 'Lantai 1', 'kapasitas' => 30, 'fasilitas' => 'Proyektor, AC', 'foto' => 'ruangan/r-102.svg'],
            ['kode_ruangan' => 'R-201', 'nama_ruangan' => 'Ruang R-201', 'gedung' => 'Gedung A', 'lokasi' => 'Lantai 2', 'kapasitas' => 50, 'fasilitas' => 'Proyektor, AC, Sound System', 'foto' => 'ruangan/r-201.svg'],
            ['kode_ruangan' => 'Lab-Komputer-1', 'nama_ruangan' => 'Lab Komputer 1', 'gedung' => 'Gedung B', 'lokasi' => 'Lantai 1', 'kapasitas' => 30, 'fasilitas' => '30 Unit PC, Proyektor, AC', 'foto' => 'ruangan/lab-komputer-1.svg'],
            ['kode_ruangan' => 'Lab-Komputer-2', 'nama_ruangan' => 'Lab Komputer 2', 'gedung' => 'Gedung B', 'lokasi' => 'Lantai 2', 'kapasitas' => 30, 'fasilitas' => '30 Unit PC, Proyektor, AC', 'foto' => 'ruangan/lab-komputer-2.svg'],
            ['kode_ruangan' => 'Studio-Desain', 'nama_ruangan' => 'Studio Desain', 'gedung' => 'Gedung C', 'lokasi' => 'Lantai 1', 'kapasitas' => 25, 'fasilitas' => 'Meja Gambar, AC, Proyektor', 'foto' => 'ruangan/studio-desain.svg'],
            ['kode_ruangan' => 'Aula', 'nama_ruangan' => 'Aula Utama', 'gedung' => 'Gedung Utama', 'lokasi' => 'Lantai 1', 'kapasitas' => 200, 'fasilitas' => 'Sound System, Proyektor, Panggung, AC', 'foto' => 'ruangan/aula.svg'],
        ];

        foreach ($classrooms as $data) {
            Classroom::create($data);
        }

        // ===== Contoh Pengajuan =====
        $r101 = Classroom::where('kode_ruangan', 'R-101')->first();
        $lab1 = Classroom::where('kode_ruangan', 'Lab-Komputer-1')->first();
        $aula = Classroom::where('kode_ruangan', 'Aula')->first();

        $booking1 = Booking::create([
            'kode_pengajuan' => 'PJ-'.now()->format('Ymd').'-0001',
            'user_id' => $mahasiswa1->id,
            'classroom_id' => $r101->id,
            'keperluan' => 'Rapat koordinasi organisasi mahasiswa HIMA DKV',
            'tanggal' => now()->addDays(2)->toDateString(),
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'jumlah_peserta' => 25,
            'status' => Booking::STATUS_PENDING,
        ]);

        $booking2 = Booking::create([
            'kode_pengajuan' => 'PJ-'.now()->format('Ymd').'-0002',
            'user_id' => $mahasiswa2->id,
            'classroom_id' => $lab1->id,
            'keperluan' => 'Latihan lomba programming antar kampus',
            'tanggal' => now()->addDays(3)->toDateString(),
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '12:00:00',
            'jumlah_peserta' => 20,
            'status' => Booking::STATUS_DISETUJUI,
            'approved_by' => $akademik->id,
            'approved_at' => now(),
        ]);

        Notification::create([
            'user_id' => $akademik->id,
            'judul' => 'Pengajuan Baru',
            'pesan' => 'Mahasiswa '.$mahasiswa1->name.' mengajukan peminjaman ruangan dengan kode '.$booking1->kode_pengajuan.'.',
            'tipe' => 'info',
        ]);

        Notification::create([
            'user_id' => $mahasiswa2->id,
            'judul' => 'Pengajuan Disetujui',
            'pesan' => 'Pengajuan peminjaman '.$booking2->kode_pengajuan.' telah DISETUJUI.',
            'tipe' => 'success',
        ]);
    }
}
