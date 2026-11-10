# Deploy SIPKA IDB Bali ke Host Laravel-Friendly

Aplikasi ini adalah **Laravel 12 (PHP) + MySQL + Reverb (WebSocket) + Queue**.
Proyek sudah siap-deploy memakai **Docker** (1 kontainer berisi nginx + PHP-FPM +
Reverb + queue worker).

> ❌ Vercel **tidak** mendukung PHP/Laravel — jangan pakai Vercel.
> Gunakan **Railway** (disarankan) atau **Render**.

---

## Struktur deployment

| Proses | Fungsi |
|---|---|
| nginx + PHP-FPM | Melayani aplikasi web (HTTP) & proxy WebSocket |
| `artisan reverb:start` | Server WebSocket realtime (notifikasi) |
| `artisan queue:work` | Memproses antrean broadcast (event `ShouldBroadcast`) |
| MySQL | Database data, session, cache, dan antrean |

Semua berjalan dalam **satu kontainer** lewat `supervisord`, sehingga realtime
tetap bekerja di domain yang sama (browser → nginx → Reverb).

---

## Cara 1: Railway (Disarankan)

1. Buka [railway.app](https://railway.app) dan login (bisa pakai akun GitHub).
2. Klik **New Project → Deploy from GitHub repo**.
3. Pilih repository **`Xandro0212/Pinjam-Kelas`**.
4. Railway akan otomatis mendeteksi `Dockerfile` dan membangunnya.

### Tambahkan database MySQL

5. Di dalam project, klik **+ Add** → **Database → MySQL**.
6. Railway otomatis membuat variabel `MYSQLHOST`, `MYSQLPORT`, `MYSQLUSER`,
   `MYSQLPASSWORD`, `MYSQLDATABASE` (dengan namespace nama service, mis.
   `MYSQL_...`). Catat nama-namanya dari tab **Variables** layanan MySQL.

### Setel variabel environment di layanan aplikasi

Buka layanan aplikasi → **Variables**, lalu tambahkan:

| Variabel | Nilai / contoh |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | `base64:...` (lihat langkah di bawah) |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | nilai `MYSQLHOST` dari layanan MySQL |
| `DB_PORT` | `3306` |
| `DB_DATABASE` | nilai `MYSQLDATABASE` |
| `DB_USERNAME` | nilai `MYSQLUSER` |
| `DB_PASSWORD` | nilai `MYSQLPASSWORD` |
| `REVERB_APP_ID` | angka bebas, mis. `244922` |
| `REVERB_APP_KEY` | string bebas, mis. `sipka-idb-reverb-key` |
| `REVERB_APP_SECRET` | string bebas, mis. `sipka-idb-reverb-secret` |
| `REVERB_SCHEME` | `https` |
| `REVERB_PORT` | `443` |
| `REVERB_SERVER_PORT` | `8080` |
| `RUN_MIGRATIONS` | `true` |
| `RUN_SEED` | `true` **(hanya deploy pertama, lalu hapus/`false`)** |

> `APP_URL` dan `REVERB_HOST` otomatis terisi dari `RAILWAY_PUBLIC_DOMAIN`
> oleh entrypoint — tidak perlu diisi manual.

### Generate APP_KEY

Jalankan di komputer Anda (di folder proyek):

```bash
php artisan key:generate --show
```

Salin hasilnya (format `base64:...`) ke variabel `APP_KEY` di Railway.

### Aktifkan port & deploy

- Pastikan service menggunakan `PORT` default (Railway otomatis men-set `PORT`;
  entrypoint sudah membaca variabel `PORT`).
- Klik **Deploy**. Entrypoint otomatis: migrasi → (seed, jika diaktifkan) →
  jalankan server.

### Setelah live

- Buka URL `https://<proyek>.up.railway.app`.
- **Login pertama** (dari seeder): lihat tabel akun demo di bawah.
- Setelah data demo masuk, setel `RUN_SEED` ke `false` lalu deploy ulang agar
  data tidak ter-duplikasi.

---

## Cara 2: Render

1. Buka [render.com](https://render.com) → **New → Web Service**.
2. Pilih repo `Xandro0212/Pinjam-Kelas` (Render mendeteksi `Dockerfile`).
3. Tambah **PostgreSQL/MySQL** via **New → MySQL** (atau pakai DB eksternal).
4. Setel variabel environment yang sama seperti tabel di atas, ditambah:
   - `PORT` = `80` (Render memakai port ini untuk web service).
   - `RENDER_EXTERNAL_URL` otomatis disediakan Render; entrypoint akan
     memakai `APP_URL` dan `REVERB_HOST` darinya.
5. Deploy. Health check bisa diarahkan ke path `/up`.

---

## Akun demo (dari `DatabaseSeeder`)

| Role | Email | Password |
|---|---|---|
| Admin | `admin@idb.ac.id` | `password` |
| Bagian Akademik | `akademik@idb.ac.id` | `password` |
| Mahasiswa | `mahasiswa@idb.ac.id` | `password` |

> ⚠️ Ganti password setelah deploy (akun demo memakai password publik).

---

## Catatan penting

- **APP_KEY harus tetap** — setel sebagai environment variable (jangan biarkan
  entrypoint membuat sementara, agar sesi login tidak ter-reset tiap deploy).
- **Upload foto ruangan** disimpan di filesystem lokal (`storage/app`) yang
  bersifat sementara di Railway/Render (hilang saat redeploy). Untuk produksi
  nyata, pindahkan `FILESYSTEM_DISK` ke S3/R2. Untuk demo tidak masalah.
- **`RUN_SEED=true` hanya sekali** — seeder tidak idempoten dan akan
  menggandakan data jika dijalankan berulang.
- **Realtime (WebSocket)** bekerja lewat proxy nginx di path `/app` dan `/apps`,
  jadi domain yang sama dipakai untuk HTTP dan WebSocket.

---

## Uji lokal dengan Docker (opsional)

```bash
docker build -t sipka .
docker run --rm -p 8000:80 \
  -e APP_KEY="base64:..." \
  -e DB_CONNECTION=mysql \
  -e DB_HOST=host.docker.internal \
  -e DB_PORT=3306 \
  -e DB_DATABASE=sipka_idb \
  -e DB_USERNAME=root \
  -e DB_PASSWORD= \
  -e REVERB_APP_ID=244922 \
  -e REVERB_APP_KEY=sipka-idb-reverb-key \
  -e REVERB_APP_SECRET=sipka-idb-reverb-secret \
  -e REVERB_SCHEME=http \
  -e REVERB_PORT=8000 \
  -e RUN_SEED=true \
  sipka
```
