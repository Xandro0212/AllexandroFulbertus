<!DOCTYPE html>
<html lang="id" class="ed-html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    {{-- Tema & PWA --}}
    <meta name="theme-color" content="#0d0d0d">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="application-name" content="SIPKA IDB">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">

    {{-- iOS Safari --}}
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIPKA IDB">

    {{-- Ikon --}}
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}">
    <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <title>Beranda — SIPKA IDB Bali</title>

    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>
<body class="ed-body">

    {{-- ================= NAV ================= --}}
    <nav class="ed-nav" id="edNav">
        <a href="{{ route('landing') }}" class="ed-brand" title="Kembali ke halaman awal">
            <img src="{{ asset('images/idbbali-logo.png') }}" alt="Logo IDB Bali">
            <span>SIPKA — IDB Bali</span>
        </a>

        <div class="ed-nav-links">
            <a href="#ruangan">Ruangan</a>
            <a href="#alur">Alur</a>
            <a href="#fitur">Fitur</a>
        </div>

        <div class="ed-nav-cta">
            @guest
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}" class="ed-nav-strong">Daftar</a>
            @else
                <a href="{{ route('dashboard') }}" class="ed-nav-strong">Dashboard</a>
            @endguest
        </div>
    </nav>

    {{-- ================= HERO ================= --}}
    <header class="ed-hero" id="edHero">
        <img class="ed-hero-img" src="https://images.unsplash.com/photo-1487958449943-2429e8be8625?w=1920&q=80" alt="Gedung kampus modern">
        <div class="ed-hero-overlay"></div>

        <div class="ed-hero-content">
            <p class="ed-hero-eyebrow">Institut Desain dan Bisnis Bali — Denpasar</p>
            <h1>Peminjaman ruang kelas,<br>tanpa bentrokan jadwal.</h1>
            <div class="ed-hero-scroll">
                <span>Gulir untuk menjelajah</span>
            </div>
        </div>
    </header>

    <main>
        {{-- ================= RUANGAN ================= --}}
        <section class="ed-bleed" id="ruangan">
            <div class="ed-section">
                <div class="ed-section-head">
                    <div>
                        <p class="ed-label">Ruangan Terpilih</p>
                        <h2 class="ed-h2">Ruangan</h2>
                    </div>
                    <span class="ed-counter">(04 / 07)</span>
                </div>

                <div class="ed-rooms">
                    <article class="ed-room">
                        <div class="ed-room-img">
                            <img src="https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=1200&q=80" alt="Aula Utama" loading="lazy">
                        </div>
                        <p class="ed-room-num">01 / 04</p>
                        <h3 class="ed-room-name">Aula Utama</h3>
                        <p class="ed-room-meta">Aula · Kapasitas 200 · Gedung Utama</p>
                    </article>

                    <article class="ed-room">
                        <div class="ed-room-img">
                            <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=1200&q=80" alt="Ruang R-201" loading="lazy">
                        </div>
                        <p class="ed-room-num">02 / 04</p>
                        <h3 class="ed-room-name">Ruang R-201</h3>
                        <p class="ed-room-meta">Ruang Kelas · Kapasitas 50 · Gedung A</p>
                    </article>

                    <article class="ed-room">
                        <div class="ed-room-img">
                            <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=1200&q=80" alt="Lab Komputer 1" loading="lazy">
                        </div>
                        <p class="ed-room-num">03 / 04</p>
                        <h3 class="ed-room-name">Lab Komputer 1</h3>
                        <p class="ed-room-meta">Laboratorium · Kapasitas 30 · Gedung B</p>
                    </article>

                    <article class="ed-room">
                        <div class="ed-room-img">
                            <img src="https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=1200&q=80" alt="Studio Desain" loading="lazy">
                        </div>
                        <p class="ed-room-num">04 / 04</p>
                        <h3 class="ed-room-name">Studio Desain</h3>
                        <p class="ed-room-meta">Studio · Kapasitas 25 · Gedung C</p>
                    </article>
                </div>
            </div>
        </section>

        {{-- ================= KUTIPAN ================= --}}
        <section class="ed-bleed">
            <div class="ed-section ed-quote">
                <div class="ed-quote-img">
                    <img src="https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=1400&q=80" alt="Ruang kelas" loading="lazy">
                </div>
                <figure class="ed-quote-fig">
                    <blockquote>
                        &ldquo;Ruang belajar yang tertata rapi membuat proses belajar lebih bermakna.&rdquo;
                    </blockquote>
                    <figcaption>Bagian Akademik — IDB Bali</figcaption>
                </figure>
            </div>
        </section>

        {{-- ================= TENTANG (GELAP) ================= --}}
        <section class="ed-bleed ed-dark" id="tentang">
            <div class="ed-section">
                <p class="ed-label">Tentang Sistem</p>
                <h2 class="ed-h2 ed-about-title">Satu sistem untuk seluruh peminjaman kelas di kampus.</h2>

                <div class="ed-about-cols">
                    <p>SIPKA menghadirkan alur peminjaman ruang kelas IDB Bali yang resmi dan terukur — dari pengajuan mahasiswa hingga persetujuan Bagian Akademik, semuanya terekam dalam satu sistem.</p>
                    <p>Tidak ada lagi formulir kertas atau jadwal ganda: setiap pengajuan diverifikasi otomatis, dan statusnya dapat dipantau secara real-time oleh mahasiswa maupun akademik.</p>
                </div>
            </div>
        </section>

        {{-- ================= ALUR ================= --}}
        <section class="ed-bleed" id="alur">
            <div class="ed-section">
                <div class="ed-section-head">
                    <div>
                        <p class="ed-label">Cara Kerja</p>
                        <h2 class="ed-h2">Alur</h2>
                    </div>
                    <span class="ed-counter">(04) Langkah</span>
                </div>

                <div class="ed-steps">
                    <div class="ed-step">
                        <p class="ed-step-num">01</p>
                        <h3>Ajukan</h3>
                        <p>Mahasiswa memilih ruangan, tanggal, jam, jumlah peserta, dan keperluan — lalu mengirim pengajuan dalam hitungan menit.</p>
                    </div>
                    <div class="ed-step">
                        <p class="ed-step-num">02</p>
                        <h3>Verifikasi Otomatis</h3>
                        <p>Sistem memeriksa ketersediaan ruangan dan menolak otomatis jika sudah ada peminjaman pada waktu yang sama.</p>
                    </div>
                    <div class="ed-step">
                        <p class="ed-step-num">03</p>
                        <h3>Persetujuan Akademik</h3>
                        <p>Bagian Akademik meninjau setiap pengajuan, lalu menyetujui atau menolaknya beserta catatan yang jelas.</p>
                    </div>
                    <div class="ed-step">
                        <p class="ed-step-num">04</p>
                        <h3>Resmi &amp; Terjadwal</h3>
                        <p>Ruangan resmi dipesan dan jadwalnya langsung tampil pada kalender kelas kampus untuk semua pengguna.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= FITUR ================= --}}
        <section class="ed-bleed" id="fitur">
            <div class="ed-section">
                <div class="ed-section-head">
                    <div>
                        <p class="ed-label">Fitur Sistem</p>
                        <h2 class="ed-h2">Fitur</h2>
                    </div>
                    <span class="ed-counter">(04) Fitur</span>
                </div>

                <div class="ed-rows">
                    <div class="ed-row">
                        <span class="ed-row-tag">Mahasiswa</span>
                        <div>
                            <h3 class="ed-row-h">Pengajuan online dalam hitungan menit</h3>
                            <p class="ed-row-p">Formulir peminjaman yang ringkas — pilih ruangan, jadwal, dan keperluan dari perangkat apa pun.</p>
                        </div>
                        <span class="ed-row-arrow">&rarr;</span>
                    </div>

                    <div class="ed-row">
                        <span class="ed-row-tag">Sistem</span>
                        <div>
                            <h3 class="ed-row-h">Deteksi bentrokan jadwal otomatis</h3>
                            <p class="ed-row-p">Dua kegiatan tidak akan pernah menempati satu ruangan pada waktu yang sama.</p>
                        </div>
                        <span class="ed-row-arrow">&rarr;</span>
                    </div>

                    <div class="ed-row">
                        <span class="ed-row-tag">Akademik</span>
                        <div>
                            <h3 class="ed-row-h">Persetujuan resmi beserta catatan</h3>
                            <p class="ed-row-p">Tinjau antrian pengajuan, setujui atau tolak dengan alasan yang terdokumentasi.</p>
                        </div>
                        <span class="ed-row-arrow">&rarr;</span>
                    </div>

                    <div class="ed-row">
                        <span class="ed-row-tag">Semua</span>
                        <div>
                            <h3 class="ed-row-h">Notifikasi &amp; kalender real-time</h3>
                            <p class="ed-row-p">Status pengajuan dan jadwal ruangan selalu mutakhir — tanpa perlu bertanya ke meja akademik.</p>
                        </div>
                        <span class="ed-row-arrow">&rarr;</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ================= CTA (GELAP) ================= --}}
        <section class="ed-bleed ed-dark">
            <div class="ed-section ed-cta">
                <p class="ed-label">Mulai Sekarang</p>
                <h2 class="ed-h2">Kelola peminjaman<br>kelas kampus Anda.</h2>

                @guest
                    <a href="{{ route('register') }}" class="ed-cta-link">Daftar Sekarang &rarr;</a>
                    <p class="ed-cta-sub">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                @else
                    <a href="{{ route('dashboard') }}" class="ed-cta-link">Buka Dashboard &rarr;</a>
                    <p class="ed-cta-sub">Butuh bantuan? <a href="mailto:akademik@idb.ac.id">akademik@idb.ac.id</a></p>
                @endguest
            </div>
        </section>
    </main>

    {{-- ================= FOOTER ================= --}}
    <footer class="ed-footer">
        <div class="ed-section">
            <div class="ed-footer-grid">
                <div class="ed-footer-brand">
                    <div class="ed-footer-wordmark">SIPKA — IDB Bali</div>
                    <p>Sistem peminjaman ruang kelas Institut Desain dan Bisnis Bali — resmi, terukur, dan bebas bentrokan jadwal.</p>
                </div>

                <div>
                    <p class="ed-footer-head">Navigasi</p>
                    <a href="#ruangan">Ruangan</a>
                    <a href="#tentang">Tentang</a>
                    <a href="#alur">Alur</a>
                    <a href="#fitur">Fitur</a>
                </div>

                <div>
                    <p class="ed-footer-head">Akun</p>
                    @guest
                        <a href="{{ route('login') }}">Masuk</a>
                        <a href="{{ route('register') }}">Daftar</a>
                    @else
                        <a href="{{ route('dashboard') }}">Dashboard</a>
                        <a href="{{ route('schedule.classrooms') }}">Daftar Ruangan</a>
                    @endguest
                    <a href="mailto:akademik@idb.ac.id">akademik@idb.ac.id</a>
                </div>
            </div>

            <div class="ed-footer-bottom">
                <span>&copy; {{ date('Y') }} SIPKA — Institut Desain dan Bisnis Bali</span>
                <span>Denpasar, Bali</span>
            </div>
        </div>
    </footer>

    <script>
        // Navigasi transparan di atas hero, putih solid saat digulir
        (function () {
            var nav = document.getElementById('edNav');
            var hero = document.getElementById('edHero');
            function onScroll() {
                var h = hero ? hero.offsetHeight : 600;
                nav.classList.toggle('is-scrolled', window.scrollY > h - 80);
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();
    </script>
</body>
</html>
