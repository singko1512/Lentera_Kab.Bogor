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
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.dashboard') || Route::is('admin.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.dashboard') }}">
<span class="material-symbols-outlined {{ Route::is('kesbangpol.dashboard') || Route::is('admin.dashboard') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">dashboard</span>
<span class="text-label-md font-label-md">Dashboard</span>
</a>

<!-- Fitur Lengkap Admin Kesbangpol -->
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

<!-- Kelola Akun Dinas -->
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.dinas*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.dinas.index') }}">
<span class="material-symbols-outlined {{ Route::is('kesbangpol.dinas*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">admin_panel_settings</span>
<span class="text-label-md font-label-md">Kelola Akun Dinas</span>
</a>
</nav>
<div class="mt-auto space-y-1.5 pt-6 border-t border-outline-variant/50">
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
