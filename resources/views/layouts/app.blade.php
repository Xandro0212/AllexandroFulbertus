<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tema & PWA --}}
    <meta name="theme-color" content="#0A7A8C">
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

    <title>@yield('title', 'SIPKA') — SIPKA IDB Bali</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div class="app-shell">
        {{-- ================= SIDEBAR ================= --}}
        <aside class="app-sidebar" id="appSidebar">
            <a href="{{ route('landing') }}" class="sidebar-brand" title="Kembali ke halaman awal">
                <img src="{{ asset('images/idbbali-logo.png') }}" alt="IDB Bali">
                <div>
                    <div class="brand-name">SIPKA</div>
                    <div class="brand-tag">IDB Bali</div>
                </div>
                <i class="bi bi-house-door brand-home-ico" title="Kembali ke halaman awal"></i>
            </a>

            <nav class="sidebar-nav">
                <div class="nav-section">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2"></i> Dashboard
                </a>
                @if(!auth()->user()->isMahasiswa())
                    <a href="{{ route('schedule.index') }}" class="{{ request()->routeIs('schedule.index') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i> Jadwal Kelas
                    </a>
                @endif
                <a href="{{ route('schedule.classrooms') }}" class="{{ request()->routeIs('schedule.classrooms') ? 'active' : '' }}">
                    <i class="bi bi-door-open"></i> Daftar Ruangan
                </a>

                @if(auth()->user()->isMahasiswa())
                    <div class="nav-section">Peminjaman</div>
                    <a href="{{ route('booking.index') }}" class="{{ request()->routeIs('booking.index') || request()->routeIs('booking.show') ? 'active' : '' }}">
                        <i class="bi bi-clipboard-check"></i> Pengajuan Saya
                    </a>
                    <a href="{{ route('booking.create') }}" class="{{ request()->routeIs('booking.create') ? 'active' : '' }}">
                        <i class="bi bi-plus-square"></i> Buat Pengajuan
                    </a>
                @endif

                @if(auth()->user()->isAkademik() || auth()->user()->isAdmin())
                    <div class="nav-section">Persetujuan</div>
                    <a href="{{ route('academic.index') }}" class="{{ request()->routeIs('academic.*') ? 'active' : '' }}">
                        <i class="bi bi-check2-circle"></i> Tinjau Pengajuan
                        <span class="nav-badge {{ $pendingCount > 0 ? '' : 'd-none' }}" id="pending-badge">{{ $pendingCount }}</span>
                    </a>
                @endif

                @if(auth()->user()->isAdmin())
                    <div class="nav-section">Administrasi</div>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Manajemen Pengguna
                    </a>
                    <a href="{{ route('admin.classrooms') }}" class="{{ request()->routeIs('admin.classrooms*') ? 'active' : '' }}">
                        <i class="bi bi-building"></i> Kelola Ruangan
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="{{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-list-check"></i> Semua Pengajuan
                    </a>
                @endif

                <div class="nav-section">Akun</div>
                <a href="{{ route('notification.index') }}" class="{{ request()->routeIs('notification.*') ? 'active' : '' }}">
                    <i class="bi bi-bell"></i> Notifikasi
                    <span class="nav-badge {{ $unreadNotificationsCount > 0 ? '' : 'd-none' }}" id="notif-badge-side">{{ $unreadNotificationsCount }}</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="sidebar-user">
                    <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="u-name text-truncate">{{ auth()->user()->name }}</div>
                        <div class="u-role">{{ auth()->user()->roleLabel() }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm text-white-50 p-1 border-0" title="Keluar"><i class="bi bi-box-arrow-right fs-6"></i></button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

        {{-- ================= MAIN ================= --}}
        <div class="app-main">
            <header class="app-topbar">
                <button class="icon-btn hamburger-btn" id="sidebarToggle" aria-label="Menu"><i class="bi bi-list"></i></button>
                <div class="topbar-title">
                    @yield('title', 'Dashboard')
                    <span class="sub">Sistem Peminjaman Kelas · IDB Bali</span>
                </div>

                <div class="topbar-actions">
                    <a href="{{ route('notification.index') }}" class="icon-btn" title="Notifikasi">
                        <i class="bi bi-bell"></i>
                        <span class="dot-badge {{ $unreadNotificationsCount > 0 ? '' : 'd-none' }}" id="notif-badge">{{ $unreadNotificationsCount }}</span>
                    </a>
                    <div class="dropdown">
                        <button class="topbar-user border-0" data-bs-toggle="dropdown" aria-label="Akun">
                            <span class="avatar-sm">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span class="text-start">
                                <span class="um-name d-block">{{ auth()->user()->name }}</span>
                                <span class="um-role d-block">{{ auth()->user()->roleLabel() }}</span>
                            </span>
                            <i class="bi bi-chevron-down small text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text small text-muted">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-1"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="app-content">
                @include('partials.flash')
                @yield('content')
            </main>
        </div>
    </div>

    {{-- ================= MOBILE BOTTOM NAV ================= --}}
    <nav class="mobile-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2"></i><span>Beranda</span>
        </a>
        <a href="{{ route('schedule.index') }}" class="{{ request()->routeIs('schedule.index') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i><span>Jadwal</span>
        </a>
        @if(auth()->user()->isMahasiswa())
            <a href="{{ route('booking.index') }}" class="{{ request()->routeIs('booking.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-check"></i><span>Pengajuan</span>
            </a>
            <a href="{{ route('booking.create') }}" class="mobile-nav-cta">
                <i class="bi bi-plus-square"></i><span>Buat</span>
            </a>
        @else
            <a href="{{ route('academic.index') }}" class="{{ request()->routeIs('academic.*') ? 'active' : '' }}">
                <i class="bi bi-check2-circle"></i><span>Persetujuan</span>
            </a>
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                    <i class="bi bi-people"></i><span>Pengguna</span>
                </a>
            @endif
        @endif
        <a href="{{ route('notification.index') }}" class="{{ request()->routeIs('notification.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i><span>Notif</span>
        </a>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar mobile
        (function () {
            var sidebar = document.getElementById('appSidebar');
            var backdrop = document.getElementById('sidebarBackdrop');
            var toggle = document.getElementById('sidebarToggle');
            function openSidebar() { sidebar.classList.add('is-open'); backdrop.classList.add('is-open'); }
            function closeSidebar() { sidebar.classList.remove('is-open'); backdrop.classList.remove('is-open'); }
            if (toggle) toggle.addEventListener('click', function () { sidebar.classList.toggle('is-open'); backdrop.classList.toggle('is-open'); });
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        })();
    </script>
    @include('partials.realtime')
</body>
</html>
