<aside class="h-screen w-72 fixed left-0 top-0 bg-surface border-r border-outline-variant/60 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-50 flex flex-col justify-between hidden md:flex transition-transform duration-300 ease-in-out">
<div class="flex flex-col h-full py-6 px-4">
<div class="mb-8 px-4 flex items-center gap-4 group cursor-pointer">
<div class="p-2 bg-primary/5 rounded-xl group-hover:scale-105 transition-transform duration-300">
    <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Lambang Kabupaten Bogor" class="w-10 h-10 object-contain shrink-0 drop-shadow-sm">
</div>
<div>
<h1 class="text-headline-md font-headline-md font-bold text-on-surface leading-tight tracking-tight">LENTERA</h1>
<p class="text-caption font-caption text-on-surface-variant font-medium">Kabupaten Bogor</p>
</div>
</div>
<nav class="flex-1 space-y-1.5 overflow-y-auto">

<!-- Pelayanan Publik Dropdown -->
<div x-data="{ open: {{ (Route::is('kesbangpol.layanan*', 'kesbangpol.participants*', 'kesbangpol.history*') || (Route::is('kesbangpol.dashboard') && request('tab', 'pelayanan') === 'pelayanan')) ? 'true' : 'false' }} }" class="mt-3">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-2.5 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary hover:bg-surface-container-low rounded-xl transition-all duration-150 focus:outline-none text-left group">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="material-symbols-outlined text-[19px] shrink-0 text-on-surface-variant group-hover:text-primary transition-colors">folder_open</span>
            <span class="truncate whitespace-nowrap">Pengajuan Layanan</span>
        </div>
        <span class="material-symbols-outlined text-[18px] shrink-0 text-on-surface-variant group-hover:text-primary transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="ml-3.5 pl-3 border-l-2 border-outline-variant/60 space-y-1 my-1.5">
        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ (Route::is('kesbangpol.dashboard') && request('tab', 'pelayanan') === 'pelayanan') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.dashboard', ['tab' => 'pelayanan']) }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ (Route::is('kesbangpol.dashboard') && request('tab', 'pelayanan') === 'pelayanan') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">analytics</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Dashboard Pelayanan</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('kesbangpol.layanan*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.layanan.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('kesbangpol.layanan*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">verified</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Verifikasi Pengajuan</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('kesbangpol.participants*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.participants.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('kesbangpol.participants*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">groups</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Manajemen Peserta</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('kesbangpol.history*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.history.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('kesbangpol.history*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">history</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Riwayat Pengajuan</span>
        </a>
    </div>
</div>

<!-- Rekrutmen Internal Dropdown -->
<div x-data="{ open: {{ (Route::is('dinas.bidang*', 'dinas.rekrutmen*', 'dinas.applications*', 'dinas.participants*') || (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat') || (Route::is('kesbangpol.dashboard') && request('tab') === 'internal')) ? 'true' : 'false' }} }" class="mt-3">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-2.5 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary hover:bg-surface-container-low rounded-xl transition-all duration-150 focus:outline-none text-left group">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="material-symbols-outlined text-[19px] shrink-0 text-on-surface-variant group-hover:text-primary transition-colors">corporate_fare</span>
            <span class="truncate whitespace-nowrap">Rekrutmen Internal</span>
        </div>
        <span class="material-symbols-outlined text-[18px] shrink-0 text-on-surface-variant group-hover:text-primary transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="ml-3.5 pl-3 border-l-2 border-outline-variant/60 space-y-1 my-1.5">
        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ (Route::is('kesbangpol.dashboard') && request('tab') === 'internal') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.dashboard', ['tab' => 'internal']) }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ (Route::is('kesbangpol.dashboard') && request('tab') === 'internal') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">monitoring</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Dashboard Rekrutmen</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('dinas.bidang*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('dinas.bidang.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('dinas.bidang*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">business</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Kelola Bidang Internal</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('dinas.rekrutmen*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('dinas.rekrutmen.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('dinas.rekrutmen*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">campaign</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Kelola Lowongan</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('dinas.applications*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('dinas.applications.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('dinas.applications*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">person_add</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Pengajuan Masuk</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('dinas.participants*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('dinas.participants.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('dinas.participants*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">groups</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Manajemen Peserta</span>
        </a>

        @if(!in_array(Auth::user()->role, ['superadmin', 'admin']))
        <a data-turbo="false" class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('absensi.admin.dashboard') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('absensi.admin.dashboard') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('absensi.admin.dashboard') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">assignment_ind</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Manajemen Magang & Sertifikat</span>
        </a>
        @endif
    </div>
</div>

<!-- Master Data Superadmin -->
@if(in_array(Auth::user()->role, ['superadmin', 'admin']))
<div x-data="{ open: {{ Route::is('kesbangpol.dinas*', 'admin.surat*') ? 'true' : 'false' }} }" class="mt-3">
    <button @click="open = !open" type="button" class="flex items-center justify-between w-full px-2.5 py-2 text-xs font-bold text-on-surface-variant uppercase tracking-wider hover:text-primary hover:bg-surface-container-low rounded-xl transition-all duration-150 focus:outline-none text-left group">
        <div class="flex items-center gap-2.5 min-w-0">
            <span class="material-symbols-outlined text-[19px] shrink-0 text-on-surface-variant group-hover:text-primary transition-colors">database</span>
            <span class="truncate whitespace-nowrap">Master Data</span>
        </div>
        <span class="material-symbols-outlined text-[18px] shrink-0 text-on-surface-variant group-hover:text-primary transition-transform duration-200" :class="{'rotate-180': open}">expand_more</span>
    </button>
    
    <div x-show="open" x-collapse class="ml-3.5 pl-3 border-l-2 border-outline-variant/60 space-y-1 my-1.5">
        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('kesbangpol.dinas*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('kesbangpol.dinas.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('kesbangpol.dinas*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">admin_panel_settings</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Kelola Akun Dinas</span>
        </a>

        <a class="flex items-center gap-2.5 px-3 py-2 overflow-hidden {{ Route::is('admin.surat*') ? 'bg-primary text-white shadow-sm shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-150 group" href="{{ route('admin.surat.index') }}">
            <span class="material-symbols-outlined shrink-0 text-[20px] {{ Route::is('admin.surat*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-105">mail</span>
            <span class="text-label-md font-label-md min-w-0 leading-snug truncate">Kelola Surat</span>
        </a>
    </div>
</div>
@endif
</nav>
<div class="mt-auto space-y-1.5 pt-6 border-t border-outline-variant/50">
<a class="flex items-center gap-3 px-3.5 py-2.5 overflow-hidden {{ Route::is('dinas.profile*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} transition-colors duration-150 rounded-xl group" href="{{ route('dinas.profile.edit') }}">
<span class="material-symbols-outlined shrink-0 text-[22px] {{ Route::is('dinas.profile*') ? 'icon-filled' : '' }} group-hover:translate-x-1 transition-transform">person</span>
<span class="text-label-md font-label-md min-w-0 leading-snug">Profil</span>
</a>
<a class="flex items-center gap-3 px-3.5 py-2.5 overflow-hidden text-error/80 hover:bg-error/10 hover:text-error transition-colors duration-150 rounded-xl group" href="javascript:document.getElementById('logout-form-kesbangpol').submit();">
<span class="material-symbols-outlined shrink-0 text-[22px] group-hover:translate-x-1 transition-transform">logout</span>
<span class="text-label-md font-label-md min-w-0 leading-snug">Keluar</span>
</a>
</div>
</div>
</aside>
<form id="logout-form-kesbangpol" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
