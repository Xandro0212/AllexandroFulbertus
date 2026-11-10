<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">

    {{-- Tema & PWA --}}
    <meta name="theme-color" content="#0A7A8C">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="SIPKA IDB">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    <title>Masuk — SIPKA IDB Bali</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="lg-body">
        {{-- ============ Panel Brand (kiri) ============ --}}
        <aside class="lg-aside">
            <div class="lg-brand">
                <img src="{{ asset('images/idbbali-logo.png') }}" alt="IDB Bali">
                <div>
                    <div class="lg-brand-name">SIPKA</div>
                    <div class="lg-brand-tag">IDB Bali</div>
                </div>
            </div>

            <div class="lg-hero">
                <h1>Kelola peminjaman kelas<br>dengan mudah &amp; terpusat.</h1>
                <p>Ajukan peminjaman ruangan secara online, pantau status persetujuan, dan lihat jadwal kelas secara real-time.</p>
            </div>

            <div class="lg-foot">© {{ date('Y') }} Institut Desain dan Bisnis Bali</div>
        </aside>

        {{-- ============ Panel Form (kanan) ============ --}}
        <main class="lg-panel">
            <div class="lg-form">
                <div class="lg-brand-mobile">
                    <img src="{{ asset('images/idbbali-logo.png') }}" alt="IDB Bali">
                    <span>SIPKA IDB Bali</span>
                </div>

                <div class="lg-head">
                    <h2>Selamat datang kembali</h2>
                    <p>Masuk ke akun SIPKA Anda untuk mengelola peminjaman kelas.</p>
                </div>

                @include('partials.flash')

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="lg-field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="nama@idb.ac.id" required autofocus>
                    </div>

                    <div class="lg-field">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="lg-row">
                        <label class="lg-check">
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit" class="lg-btn">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </form>

                <div class="lg-footer-note">
                    Belum punya akun mahasiswa?
                    <a href="{{ route('register') }}">Daftar di sini</a>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
