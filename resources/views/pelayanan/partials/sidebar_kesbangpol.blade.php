<aside class="sidebar-kesbangpol bg-white border-end d-flex flex-column vh-100" style="width: 280px; position: fixed; top: 0; left: 0; z-index: 1040; transition: transform 0.3s ease;">
    <div class="sidebar-header d-flex align-items-center gap-3 p-4 border-bottom">
        <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" style="width: 44px; height: 44px; object-fit: contain; flex-shrink: 0;">
        <div>
            <h5 class="mb-0 fw-bold" style="font-family: var(--font);">LENTERA</h5>
            <small class="text-muted">Kabupaten Bogor</small>
        </div>
    </div>
    
    <div class="sidebar-menu flex-grow-1 p-3 overflow-auto" style="scrollbar-width: thin;">
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="{{ route('kesbangpol.layanan.index') }}" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-dark @if(Route::is('kesbangpol.layanan.*')) active @endif" style="transition: all 0.2s;">
                    <i class="fa-solid fa-file-signature"></i> Verifikasi Pengajuan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kesbangpol.participants.index') }}" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-dark @if(Route::is('kesbangpol.participants.*')) active @endif" style="transition: all 0.2s;">
                    <i class="fa-solid fa-users"></i> Manajemen Peserta
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('kesbangpol.history.index') }}" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-dark @if(Route::is('kesbangpol.history.*')) active @endif" style="transition: all 0.2s;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengajuan
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-muted hover-bg-light" style="transition: all 0.2s;">
                    <i class="fa-solid fa-file-alt"></i> Pengajuan Magang
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-muted hover-bg-light" style="transition: all 0.2s;">
                    <i class="fa-solid fa-user-plus"></i> Penempatan Peserta
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-muted hover-bg-light" style="transition: all 0.2s;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Pengajuan Surat Izin Rekomendasi
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-muted hover-bg-light" style="transition: all 0.2s;">
                    <i class="fa-solid fa-clock-rotate-left"></i> Riwayat Pengajuan
                </a>
            </li>
        </ul>
    </div>
    
    <div class="sidebar-footer p-3 border-top">
        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="nav-link w-100 text-start d-flex align-items-center gap-3 py-2 px-3 rounded-3 fw-semibold text-danger border-0 bg-transparent hover-bg-light" style="transition: all 0.2s;">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</aside>

<style>
    .sidebar-kesbangpol .nav-link.active {
        background: rgba(99, 102, 241, 0.1);
        color: var(--primary) !important;
    }
    .sidebar-kesbangpol .hover-bg-light:hover {
        background: #f8fafc;
        color: var(--dark) !important;
    }
</style>
