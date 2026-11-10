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

    <title>@yield('title', 'SIPKA') — SIPKA IDB Bali</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="auth-body">
        {{-- Panel Brand --}}
        <aside class="auth-aside">
            <div class="aside-logo">
                <img src="{{ asset('images/idbbali-logo.png') }}" alt="IDB Bali">
            </div>

            <div class="aside-hero">
                <h2>Sistem Peminjaman Kelas<br>Kampus IDB Bali</h2>
                <p>Ajukan peminjaman ruangan secara online, pantau status persetujuan, dan lihat jadwal kelas secara real-time.</p>

                <div class="aside-features">
                    <div class="feat"><i class="bi bi-lightning-charge-fill"></i> Pengajuan cepat &amp; terpusat</div>
                    <div class="feat"><i class="bi bi-shield-check"></i> Persetujuan resmi Bagian Akademik</div>
                    <div class="feat"><i class="bi bi-broadcast"></i> Notifikasi &amp; jadwal real-time</div>
                </div>
            </div>

            <div class="aside-foot">© {{ date('Y') }} Institut Desain dan Bisnis Bali</div>
        </aside>

        {{-- Panel Form --}}
        <main class="auth-panel">
            <div class="auth-form-wrap">
                <div class="auth-mobile-logo">
                    <img src="{{ asset('images/idbbali-logo.png') }}" alt="IDB Bali">
                </div>
                @include('partials.flash')
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
