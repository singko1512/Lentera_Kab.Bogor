<style>
    .nav-center-link {
        position: relative;
        padding: 6px 14px;
        border-radius: 20px;
        color: var(--text);
        font-weight: 600;
        font-size: 0.92rem;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
    }
    .nav-center-link::after {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 50%;
        width: 0%;
        height: 2.5px;
        background: linear-gradient(90deg, #1f477b, #115cb9);
        border-radius: 4px;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        transform: translateX(-50%);
        box-shadow: 0 0 8px rgba(17, 92, 185, 0.5);
    }
    .nav-center-link:hover {
        color: #115cb9 !important;
        background: rgba(17, 92, 185, 0.08);
        transform: translateY(-2px) scale(1.03);
        box-shadow: 0 4px 12px rgba(17, 92, 185, 0.12);
    }
    .nav-center-link:hover::after {
        width: 65%;
    }

    .nav-blue-glow-btn {
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
    }
    .nav-blue-glow-btn:hover {
        background: rgba(17, 92, 185, 0.1) !important;
        color: #115cb9 !important;
        transform: translateY(-2px) scale(1.06) !important;
        box-shadow: 0 4px 12px rgba(17, 92, 185, 0.15) !important;
    }

    /* View Transitions for Smooth Circular Theme Expand */
    ::view-transition-old(root),
    ::view-transition-new(root) {
        animation: none;
        mix-blend-mode: normal;
    }
    ::view-transition-old(root) {
        z-index: 1;
    }
    ::view-transition-new(root) {
        z-index: 9999;
    }
    .dark::view-transition-old(root) {
        z-index: 9999;
    }
    .dark::view-transition-new(root) {
        z-index: 1;
    }
    .theme-ripple-overlay {
        position: fixed;
        top: 0;
        left: 0;
        border-radius: 50%;
        pointer-events: none;
        z-index: 99999;
        transform: translate(-50%, -50%) scale(0);
        transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease-out;
    }
</style>

    <!-- Navbar -->
    <nav class="site-nav">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="max-width: 1280px; padding-left: 40px; padding-right: 40px; margin: 0 auto;">
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" style="height: 42px; width: auto; object-fit: contain; margin-right: 0.5rem;">
                <div class="nav-brand-text">
                    <strong>LENTERA Kab Bogor</strong>
                    <span>Layanan Integrasi Izin Riset dan Magang Kabupaten Bogor</span>
                </div>
            </a>

            @php
                $hasAcceptedMagang = auth()->check() && \App\Models\MagangApplication::where('user_id', auth()->id())->whereIn('status', ['diterima', 'aktif'])->exists();
            @endphp

            <!-- Center Navigation Links -->
            <ul class="d-none d-md-flex align-items-center gap-2 mb-0 list-unstyled mx-auto">
                @guest
                <li><a href="{{ route('home') }}#timeline-section" class="nav-center-link">Alur Permohonan</a></li>
                <li><span class="text-muted opacity-30">|</span></li>
                @endguest
                <li><a href="{{ route('home') }}#services-section" class="nav-center-link">Jenis Layanan</a></li>
                <li><span class="text-muted opacity-30">|</span></li>
                <li><a href="{{ route('home') }}#instansi-section" class="nav-center-link">Instansi Tujuan</a></li>
                @if($hasAcceptedMagang)
                <li><span class="text-muted opacity-30">|</span></li>
                <li>
                    <a href="{{ Route::is('home') ? '#jadwal-magang-section' : route('home') . '#jadwal-magang-section' }}" class="nav-center-link text-primary font-weight-bold" style="background: rgba(17, 92, 185, 0.08); border: 1px solid rgba(17, 92, 185, 0.2);">
                        <i class="fa-solid fa-fingerprint text-primary"></i> Absensi
                    </a>
                </li>
                @endif
            </ul>

            <ul class="nav-links d-flex align-items-center gap-2.5">
                @if($hasAcceptedMagang)
                <li class="d-md-none">
                    <a href="{{ Route::is('home') ? '#jadwal-magang-section' : route('home') . '#jadwal-magang-section' }}" class="btn btn-primary btn-sm px-3 py-1.5 rounded-pill font-weight-bold d-inline-flex align-items-center gap-1.5 text-white shadow-sm" style="font-size: 0.85rem;">
                        <i class="fa-solid fa-fingerprint"></i> Absensi
                    </a>
                </li>
                @endif

                <!-- Light / Dark Mode Toggle Button (Outside, round button) -->
                <li>
                    <button id="theme-toggle" class="nav-blue-glow-btn p-0 d-flex align-items-center justify-content-center border-0" type="button" aria-label="Toggle theme" style="width: 38px; height: 38px; border-radius: 50% !important; background: var(--border); color: var(--text-muted); outline: none; box-shadow: none; text-decoration: none !important;">
                        <span id="theme-toggle-icon" class="material-symbols-outlined" style="font-size: 20px; text-decoration: none !important; border-bottom: 0 !important;">dark_mode</span>
                    </button>
                </li>

                @if(auth()->check())
                    <!-- Burger Toggle Button (For Sidebar Menu, only shown when logged in) -->
                    <li>
                        <button class="nav-blue-glow-btn p-0 d-flex align-items-center justify-content-center border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#userMenu" aria-label="Toggle menu" style="width: 38px; height: 38px; border-radius: 50% !important; color: var(--text); outline: none; box-shadow: none; text-decoration: none !important;">
                            <i class="fa-solid fa-bars fs-5" style="text-decoration: none !important;"></i>
                        </button>
                    </li>
                @else
                    <!-- Login Button (For Guest, shown outside on navbar) -->
                    <li>
                        <a href="{{ route('login.form') }}" class="nav-center-link nav-blue-glow-btn px-3 py-1.5" style="font-size: 0.88rem;">
                            <i class="fa-solid fa-lock"></i> Login
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </nav>

    @if(auth()->check())
    <!-- Sidebar Drawer (Off-canvas, only for authenticated users) -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="userMenu" style="max-width: 340px; background-color: var(--bg);">
        <div class="offcanvas-header justify-content-end pb-0 border-0">
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-4 pt-2">
            <!-- User Profile Section -->
            <div class="text-center py-4 mb-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 72px; height: 72px; border-radius: 50%; background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%); color: white; font-weight: 700; font-size: 1.8rem; box-shadow: 0 4px 15px rgba(108, 92, 231, 0.2);">
                    {{ strtoupper(substr(auth()->user()->name ?? auth()->user()->nama ?? 'U', 0, 1)) }}
                </div>
                <h5 style="font-weight: 700; color: var(--dark); margin-bottom: 0.2rem;" class="text-truncate">{{ auth()->user()->name ?? auth()->user()->nama ?? 'User' }}</h5>
                @if(auth()->user()->role !== 'user')
                <span class="badge rounded-pill bg-light text-muted px-3 py-1.5 border" style="font-size: 0.75rem; text-transform: capitalize; color: var(--text-muted) !important;">
                    {{ str_replace('_', ' ', auth()->user()->role) }}
                </span>
                @endif
            </div>

            <!-- Navigation Links -->
            <div class="d-flex flex-column gap-2.5">
                <!-- Edit Profil / Data Diri -->
                <a href="{{ route('landing.profile') }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all" style="color: var(--text); background: var(--bg); border: 1px solid var(--border);" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
                    <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                        <i class="fa-regular fa-user text-primary fs-5"></i> Edit Profil / Data Diri
                    </span>
                    <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                </a>

                <!-- Notifikasi -->
                <button type="button" data-bs-toggle="modal" data-bs-target="#notificationModal" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all border-0 text-start w-100" style="color: var(--text); background: var(--bg); border: 1px solid var(--border) !important; outline: none;" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary) !important';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border) !important';">
                    <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                        <i class="fa-regular fa-bell text-primary fs-5"></i> Notifikasi
                        <span id="nav-notif-badge" class="badge rounded-pill bg-danger ms-1 d-none" style="font-size: 0.7rem;">0</span>
                    </span>
                    <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                </button>

                @if(auth()->user()->role === 'user')
                    @php
                        $isPesertaAccepted = \App\Models\MagangApplication::where('user_id', auth()->id())
                            ->whereIn('status', ['diterima', 'aktif'])
                            ->exists();
                    @endphp
                    @if($isPesertaAccepted)
                    <!-- Absensi (Peserta) -->
                    <a href="{{ route('peserta.dashboard') }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all border-0 text-start w-100 mt-1" style="color: var(--text); background: var(--bg); border: 1px solid var(--border) !important; outline: none;" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary) !important';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border) !important';">
                        <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-clipboard-user text-primary fs-5"></i> Absensi
                        </span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                    </a>
                    @endif
                @endif

                @if(auth()->user()->role === 'bidang')
                    <!-- Absensi (Simalam Home) -->
                    <a href="{{ route('absensi.home') }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all mt-1" style="color: var(--text); background: var(--bg); border: 1px solid var(--border) !important;" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary) !important';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border) !important';">
                        <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-check text-primary fs-5"></i> Absensi
                        </span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                    </a>

                    <!-- Dashboard Simalam -->
                    <a href="{{ route('absensi.admin.dashboard') }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all mt-1" style="color: var(--text); background: var(--bg); border: 1px solid var(--border) !important;" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary) !important';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border) !important';">
                        <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-gauge-high text-primary fs-5"></i> Dashboard
                        </span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                    </a>
                @endif

                <!-- Dashboard Route -->
                @php
                    $dashboardRoute = null;
                    if (auth()->user()->role === 'superadmin') {
                        $dashboardRoute = route('superadmin.dashboard');
                    } elseif (in_array(auth()->user()->role, ['admin', 'admin_instansi'], true)) {
                        $dashboardRoute = route('admin.dashboard');
                    } elseif (auth()->user()->role === 'dinas') {
                        $dashboardRoute = route('dinas.dashboard');
                    }
                @endphp
                @if($dashboardRoute)
                    <a href="{{ $dashboardRoute }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all mt-1" style="color: var(--text); background: var(--bg); border: 1px solid var(--border);" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
                        <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-gauge-high text-primary fs-5"></i> Dashboard Admin
                        </span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                    </a>
                @endif
                
                @if(auth()->user()->role === 'dinas')
                    <!-- Dashboard SIMALAM (Absensi) -->
                    <a href="{{ route('absensi.admin.dashboard') }}" class="d-flex align-items-center justify-content-between p-3 rounded-3 text-decoration-none transition-all mt-1" style="color: var(--text); background: var(--bg); border: 1px solid var(--border);" onmouseover="this.style.transform='translateX(4px)'; this.style.borderColor='var(--primary)';" onmouseout="this.style.transform='none'; this.style.borderColor='var(--border)';">
                        <span class="d-flex align-items-center gap-2" style="font-weight: 600; font-size: 0.9rem;">
                            <i class="fa-solid fa-calendar-check text-primary fs-5"></i> Dashboard Absensi
                        </span>
                        <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                    </a>
                @endif
            </div>

            <!-- Logout Button -->
            <div class="pt-3 border-top mt-auto">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-decoration-none w-100 rounded-3 d-flex align-items-center justify-content-center gap-2 py-2.5" style="color: var(--rose); font-weight: 600; font-size: 0.95rem; transition: all 0.2s;" onmouseover="this.style.background='rgba(225, 112, 85, 0.08)'" onmouseout="this.style.background='transparent'">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar dari Akun
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content" style="background-color: var(--bg); color: var(--text); border-radius: 1rem; border: 1px solid var(--border);">
                <div class="modal-header border-0 pb-0">
                    <h4 class="modal-title" id="notificationModalLabel" style="font-weight: 700; color: var(--primary);">
                        <i class="fa-regular fa-bell me-2"></i> Notifikasi
                    </h4>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-3" id="notification-list-container">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn w-100 rounded-3 text-muted" style="font-size: 0.9rem; background: var(--surface-variant); border: none;" onclick="markAllNotificationsAsRead()">Tandai Semua Telah Dibaca</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadNotifications() {
            fetch('/api/notifications', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) return;
                
                const badge = document.getElementById('nav-notif-badge');
                if (badge) {
                    if (data.unread_count > 0) {
                        badge.textContent = data.unread_count;
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                }
                
                const container = document.getElementById('notification-list-container');
                if (container) {
                    if (!data.notifications || data.notifications.length === 0) {
                        container.innerHTML = '<div class="text-center text-muted py-5" style="font-size:1rem;"><i class="fa-solid fa-check-circle mb-3 fs-1 text-success opacity-50"></i><br>Tidak ada notifikasi baru</div>';
                        return;
                    }
                    
                    let html = '<div class="d-flex flex-column gap-3">';
                    data.notifications.forEach(n => {
                        const bgClass = n.dibaca ? 'bg-transparent' : 'bg-primary bg-opacity-10';
                        const dot = n.dibaca ? '' : '<span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>';
                        html += `
                            <div class="p-3 rounded-3 position-relative transition-all border ${bgClass}" style="border-color: var(--border) !important; cursor: pointer;" onclick="if(this.dataset.link){ window.location.href=this.dataset.link; } markNotificationAsRead(${n.id});" data-link="${n.link || ''}">
                                ${dot}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0" style="font-size: 1.05rem; font-weight: 600; color: var(--primary);">${n.judul}</h5>
                                    <small style="font-size: 0.8rem; color: rgba(255,255,255,0.6);">${new Date(n.created_at).toLocaleDateString('id-ID')}</small>
                                </div>
                                <p class="mb-0" style="font-size: 0.9rem; line-height: 1.5; color: rgba(255,255,255,0.85);">${n.pesan}</p>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                }
            })
            .catch(err => console.error(err));
        }

        function markAllNotificationsAsRead() {
            fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(() => loadNotifications())
            .catch(err => console.error(err));
        }

        function markNotificationAsRead(id) {
            fetch('/api/notifications/' + id + '/read', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            }).catch(err => console.error(err));
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadNotifications();
            const notifModal = document.getElementById('notificationModal');
            if (notifModal) {
                notifModal.addEventListener('show.bs.modal', function () {
                    loadNotifications();
                });
            }
        });
    </script>
    @endif

    <script>
        (function () {
            const html = document.documentElement;
            
            function updateThemeUI() {
                const isDark = html.classList.contains('dark');
                const toggleIcon = document.getElementById('theme-toggle-icon');
                
                if (toggleIcon) {
                    if (isDark) {
                        toggleIcon.textContent = 'light_mode';
                        toggleIcon.style.color = '#fdcb6e'; // sun gold
                    } else {
                        toggleIcon.textContent = 'dark_mode';
                        toggleIcon.style.color = 'var(--text-muted)';
                    }
                }
            }

            function toggleThemeWithRipple(e) {
                const isDark = html.classList.contains('dark');
                const toggleBtn = document.getElementById('theme-toggle');

                let x = e && e.clientX ? e.clientX : window.innerWidth / 2;
                let y = e && e.clientY ? e.clientY : 50;

                if (toggleBtn) {
                    const rect = toggleBtn.getBoundingClientRect();
                    x = rect.left + rect.width / 2;
                    y = rect.top + rect.height / 2;
                }

                const nextIsDark = !isDark;
                const targetBg = nextIsDark ? '#0b1329' : '#ffffff';

                const maxRadius = Math.hypot(
                    Math.max(x, window.innerWidth - x),
                    Math.max(y, window.innerHeight - y)
                );

                const circle = document.createElement('div');
                circle.style.cssText = `
                    position: fixed;
                    top: ${y}px;
                    left: ${x}px;
                    width: 14px;
                    height: 14px;
                    margin-top: -7px;
                    margin-left: -7px;
                    border-radius: 50%;
                    background-color: ${targetBg};
                    pointer-events: none;
                    z-index: 999999;
                    will-change: transform;
                    transition: transform 0.48s cubic-bezier(0.22, 1, 0.36, 1);
                    transform: scale(1);
                    box-shadow: 0 0 30px ${targetBg};
                `;

                document.body.appendChild(circle);

                const scaleFactor = (maxRadius * 2.3) / 14;

                requestAnimationFrame(() => {
                    circle.style.transform = `scale(${scaleFactor})`;
                });

                setTimeout(() => {
                    if (nextIsDark) {
                        html.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        html.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                    updateThemeUI();
                }, 160);

                setTimeout(() => {
                    circle.style.transition = 'opacity 0.22s ease-out';
                    circle.style.opacity = '0';
                    setTimeout(() => circle.remove(), 220);
                }, 460);
            }

            // Sync UI state initially
            document.addEventListener('DOMContentLoaded', () => {
                updateThemeUI();

                const toggleBtn = document.getElementById('theme-toggle');
                if (toggleBtn) {
                    toggleBtn.addEventListener('click', (e) => {
                        toggleThemeWithRipple(e);
                    });
                }
            });
        })();
    </script>
