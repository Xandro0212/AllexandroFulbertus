{{-- Realtime: Laravel Echo + Reverb (via CDN, tanpa build npm) --}}
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;
    const userId = {{ auth()->id() ?? 'null' }};
    const isMahasiswa = {{ auth()->user()->isMahasiswa() ? 'true' : 'false' }};

    // ---- Toast container ----
    let toastContainer = document.getElementById('realtime-toasts');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'realtime-toasts';
        toastContainer.style.cssText = 'position:fixed;top:70px;right:16px;z-index:2000;display:flex;flex-direction:column;gap:10px;max-width:min(340px,calc(100vw - 32px));';
        document.body.appendChild(toastContainer);
    }

    function toast(title, message, type = 'info') {
        const colors = { success: '#0E9F6E', danger: '#E02424', warning: '#E8A020', info: '#0A7A8C' };
        const icons = { success: 'bi-check-circle-fill', danger: 'bi-x-circle-fill', warning: 'bi-exclamation-triangle-fill', info: 'bi-info-circle-fill' };
        const el = document.createElement('div');
        el.className = 'd-flex align-items-start gap-2 p-3 rounded shadow';
        el.style.cssText = 'background:#fff;border-left:4px solid ' + (colors[type] || colors.info) + ';opacity:1;transition:opacity .4s;';
        el.innerHTML = '<i class="bi ' + (icons[type] || icons.info) + '" style="color:' + (colors[type] || colors.info) + ';font-size:1.2rem;"></i>'
            + '<div><div class="fw-semibold">' + title + '</div><div class="small text-muted">' + message + '</div></div>';
        toastContainer.appendChild(el);
        setTimeout(function () {
            el.style.opacity = '0';
            setTimeout(function () { el.remove(); }, 400);
        }, 6000);
    }

    function bumpBadge(selector) {
        const badge = document.querySelector(selector);
        if (badge) {
            let n = parseInt(badge.textContent, 10);
            if (isNaN(n)) n = 0;
            badge.textContent = n + 1;
            badge.classList.remove('d-none');
        }
    }

    // ---- Inisialisasi Echo (Reverb) ----
    // Production (https) memakai wss lewat proxy nginx; lokal (http) memakai ws langsung.
    var secure = window.location.protocol === 'https:';
    var reverbPort = {{ config('reverb.servers.reverb.port', 8080) }};
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: '{{ config('broadcasting.connections.reverb.key') }}',
        wsHost: window.location.hostname,
        wsPort: secure ? 443 : reverbPort,
        wssPort: secure ? 443 : reverbPort,
        forceTLS: secure,
        enabledTransports: ['ws', 'wss'],
        auth: {
            headers: {
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
        },
    });

    // ---- Channel publik "bookings": pembaruan antrean & jadwal ----
    window.Echo.channel('bookings')
        .listen('.booking.created', function (e) {
            if (!isMahasiswa) {
                toast('Pengajuan Baru', e.nama_mahasiswa + ' · ' + e.ruangan + ' (' + e.kode + ')', 'info');
                bumpBadge('#pending-badge');
            }
        })
        .listen('.booking.approved', function (e) {
            toast('Pengajuan Disetujui', e.ruangan + ' · ' + e.tanggal + ' (' + e.kode + ')', 'success');
        })
        .listen('.booking.rejected', function (e) {
            toast('Pengajuan Ditolak', e.ruangan + ' · ' + e.tanggal + ' (' + e.kode + ')', 'danger');
        });

    // ---- Channel privat untuk pengguna saat ini (notifikasi personal) ----
    window.Echo.private('user.' + userId)
        .listen('.booking.approved', function (e) {
            toast('Selamat! Pengajuan Disetujui', e.ruangan + ' · ' + e.tanggal, 'success');
            bumpBadge('#notif-badge');
            bumpBadge('#notif-badge-side');
        })
        .listen('.booking.rejected', function (e) {
            toast('Pengajuan Ditolak', e.alasan ? ('Alasan: ' + e.alasan) : (e.ruangan + ' · ' + e.tanggal), 'danger');
            bumpBadge('#notif-badge');
            bumpBadge('#notif-badge-side');
        });
})();
</script>
