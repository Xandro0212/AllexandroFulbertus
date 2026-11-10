# SIPKA — Sistem Peminjaman Kelas Kampus IDB Bali

Aplikasi web untuk mengelola **peminjaman kelas** di kampus **Institut Desain dan Bisnis (IDB) Bali**. Mahasiswa dapat mengajukan peminjaman ruangan secara online, dan setiap pengajuan harus melewati **persetujuan Bagian Akademik** sebelum ruangan resmi dipesan.

Dibangun dengan **Laravel 11** (PHP) + **MySQL**.

---

## ✨ Fitur

- **Login & Registrasi Mahasiswa** — autentikasi dengan email/NIM dan password.
- **Form Pengajuan Peminjaman** — pilih ruangan, tanggal, jam, jumlah peserta, dan keperluan.
- **Alur Persetujuan Akademik** — pengajuan masuk antrian, Bagian Akademik menyetujui/menolak dengan catatan.
- **Deteksi Bentrokan Jadwal** — sistem menolak otomatis jika ruangan sudah dipesan pada waktu yang sama.
- **Kalender / Jadwal Kelas** — visualisasi jadwal peminjaman per ruangan per bulan.
- **Notifikasi** — mahasiswa dan akademik mendapat notifikasi saat ada pengajuan/disetujui/ditolak.
- **Manajemen Admin** — kelola pengguna (mahasiswa/akademik/admin) dan data ruangan.

## 👥 Peran Pengguna

| Peran | Hak Akses |
|-------|-----------|
| **Mahasiswa** | Login, ajukan peminjaman, lihat status & riwayat pengajuan, lihat jadwal & ruangan |
| **Bagian Akademik** | Meninjau, menyetujui, atau menolak pengajuan peminjaman |
| **Admin** | Kelola seluruh pengguna, ruangan, dan semua data pengajuan |

## 🧩 Teknologi

- PHP 8.2+ & Laravel 11
- MySQL / MariaDB
- Bootstrap 5 (via CDN) — tanpa build tool frontend

## 🚀 Cara Menjalankan

### Prasyarat

- **PHP 8.2+** (disarankan via [XAMPP](https://www.apachefriends.org/), [Laragon](https://laragon.org/), atau [Herd](https://herd.laravel.com/))
- **Composer 2.x**
- **MySQL** (sudah termasuk di XAMPP/Laragon)

### Langkah Instalasi

```bash
# 1. Masuk ke folder proyek
cd idb-peminjaman-kelas

# 2. Install dependensi
composer install

# 3. Salin file environment
copy .env.example .env        # Windows (Command Prompt)
#   atau: cp .env.example .env   # Linux/macOS

# 4. Generate application key
php artisan key:generate

# 5. Buat database MySQL baru (misal: sipka_idb) lalu sesuaikan file .env:
#    DB_DATABASE=sipka_idb
#    DB_USERNAME=root
#    DB_PASSWORD=

# 6. Jalankan migrasi + seed data contoh
php artisan migrate --seed

# 7. Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

> **Catatan Windows:** dengan XAMPP/Laragon, MySQL harus berjalan lebih dulu. Dengan Herd, database sudah dikelola otomatis.

## 🔑 Akun Demo (dari seeder)

| Peran | Email | Password |
|-------|-------|----------|
| Admin | `admin@idb.ac.id` | `password` |
| Bagian Akademik | `akademik@idb.ac.id` | `password` |
| Mahasiswa | `mahasiswa@idb.ac.id` | `password` |

## 🔄 Alur Peminjaman

```
Mahasiswa login → pilih ruangan & jadwal → kirim pengajuan (status: Menunggu)
        ↓
Bagian Akademik meninjau → SETUJUI / TOLAK (dengan catatan/alasan)
        ↓
Mahasiswa menerima notifikasi → ruangan resmi dipesan (jika disetujui)
```

## 📁 Struktur Utama

```
app/
  Models/            User, Classroom, Booking, Notification
  Http/Controllers/  Auth, Booking, Academic, Admin, Schedule, Dashboard, Notification
  Http/Middleware/   CheckRole (pembatasan akses per peran)
database/
  migrations/        users, classrooms, bookings, notifications, dll.
  seeders/           DatabaseSeeder (akun & data contoh)
resources/views/     Blade templates (dashboard, booking, academic, admin, schedule)
routes/web.php       Semua route aplikasi
```

---

Dibuat untuk kebutuhan **Sistem Peminjaman Kelas Kampus IDB Bali**.
