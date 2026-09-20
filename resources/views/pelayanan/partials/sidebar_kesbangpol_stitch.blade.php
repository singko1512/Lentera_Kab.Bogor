<aside class="h-screen w-72 fixed left-0 top-0 bg-surface border-r border-outline-variant/60 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-50 flex flex-col justify-between hidden md:flex transition-all duration-300 ease-in-out">
<div class="flex flex-col h-full py-6 px-4">
<div class="mb-8 px-4 flex items-center gap-4 group cursor-pointer">
<div class="p-2 bg-primary/5 rounded-xl group-hover:scale-105 transition-transform duration-300">
    <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" class="w-10 h-10 object-contain shrink-0 drop-shadow-sm">
</div>
<div>
<h1 class="text-headline-md font-headline-md font-bold text-on-surface leading-tight tracking-tight">LENTERA</h1>
<p class="text-caption font-caption text-on-surface-variant font-medium">Kabupaten Bogor</p>
</div>
</div>
<nav class="flex-1 space-y-1.5 overflow-y-auto">
<!-- Dashboard -->
@if(!in_array(Auth::user()->role, ['superadmin', 'admin']))
<div class="space-y-1.5 mt-2">
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.dashboard', 'admin.dashboard', 'superadmin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ Auth::user()->role === 'superadmin' ? route('superadmin.dashboard') : route('kesbangpol.dashboard') }}">
<span class="material-symbols-outlined {{ Route::is('kesbangpol.dashboard', 'admin.dashboard', 'superadmin.dashboard') ? 'icon-filled' : '' }}">dashboard</span>
<span class="text-label-md font-label-md">Dashboard</span>
</a>
</div>
@endif

<!-- Pelayanan Publik Dropdown -->
<div x-data="{ open: {{ Route::is('kesbangpol.layanan*', 'kesbangpol.participants*', 'kesbangpol.history*') ? 'true' : 'false' }} }" class="mt-4">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-4 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary transition-colors focus:outline-none">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">folder_open</span>
            <span>Pengajuan Layanan</span>
        </div>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="space-y-1.5 mt-1.5">
        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.layanan*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.layanan.index') }}">
        <span class="material-symbols-outlined {{ Route::is('kesbangpol.layanan*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">verified</span>
        <span class="text-label-md font-label-md">Verifikasi Pengajuan</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.participants*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.participants.index') }}">
        <span class="material-symbols-outlined {{ Route::is('kesbangpol.participants*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">groups</span>
        <span class="text-label-md font-label-md">Manajemen Peserta</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.history*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.history.index') }}">
        <span class="material-symbols-outlined {{ Route::is('kesbangpol.history*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">history</span>
        <span class="text-label-md font-label-md">Riwayat Pengajuan</span>
        </a>
    </div>
</div>

<!-- Rekrutmen Internal Dropdown -->
<div x-data="{ open: {{ (Route::is('dinas.bidang*', 'dinas.rekrutmen*', 'dinas.applications*', 'dinas.participants*') || (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat')) ? 'true' : 'false' }} }" class="mt-4">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-4 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary transition-colors focus:outline-none">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">corporate_fare</span>
            <span>Rekrutmen Internal</span>
        </div>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="space-y-1.5 mt-1.5">
        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.bidang*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.bidang.index') }}">
        <span class="material-symbols-outlined {{ Route::is('dinas.bidang*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">business</span>
        <span class="text-label-md font-label-md">Kelola Bidang Internal</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.rekrutmen*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.rekrutmen.index') }}">
        <span class="material-symbols-outlined {{ Route::is('dinas.rekrutmen*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">campaign</span>
        <span class="text-label-md font-label-md">Kelola Lowongan</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.applications*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.applications.index') }}">
        <span class="material-symbols-outlined {{ Route::is('dinas.applications*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">person_add</span>
        <span class="text-label-md font-label-md">Pengajuan Masuk</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.participants*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.participants.index') }}">
        <span class="material-symbols-outlined {{ Route::is('dinas.participants*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">group</span>
        <span class="text-label-md font-label-md">Peserta Internal</span>
        </a>

        @if(!in_array(Auth::user()->role, ['superadmin', 'admin']))
        <a data-turbo="false" class="flex items-center gap-3 px-4 py-3 {{ (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('absensi.admin.dashboard', ['tab' => 'sertifikat']) }}">
        <span class="material-symbols-outlined {{ (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">workspace_premium</span>
        <span class="text-label-md font-label-md">Kelola Sertifikat</span>
        </a>
        @endif
    </div>
</div>

<!-- Master Data Superadmin -->
@if(in_array(Auth::user()->role, ['superadmin', 'admin']))
<div x-data="{ open: {{ Route::is('kesbangpol.dinas*', 'admin.surat*') ? 'true' : 'false' }} }" class="mt-4">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-4 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary transition-colors focus:outline-none">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px]">database</span>
            <span>Master Data</span>
        </div>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="space-y-1.5 mt-1.5">
        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.dinas*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.dinas.index') }}">
        <span class="material-symbols-outlined {{ Route::is('kesbangpol.dinas*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">admin_panel_settings</span>
        <span class="text-label-md font-label-md">Kelola Akun Dinas</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 {{ Route::is('admin.surat*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('admin.surat.index') }}">
        <span class="material-symbols-outlined {{ Route::is('admin.surat*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">mail</span>
        <span class="text-label-md font-label-md">Kelola Surat</span>
        </a>
    </div>
</div>
@endif
</nav>
<div class="mt-auto space-y-1.5 pt-6 border-t border-outline-variant/50">
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.profile*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} transition-all duration-200 rounded-xl group" href="{{ route('dinas.profile.edit') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.profile*') ? 'icon-filled' : '' }} group-hover:translate-x-1 transition-transform">person</span>
<span class="text-label-md font-label-md">Profil</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-error/80 hover:bg-error/10 hover:text-error transition-all duration-200 rounded-xl group" href="javascript:document.getElementById('logout-form-kesbangpol').submit();">
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">logout</span>
<span class="text-label-md font-label-md">Keluar</span>
</a>
</div>
</div>
</aside>
<form id="logout-form-kesbangpol" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
