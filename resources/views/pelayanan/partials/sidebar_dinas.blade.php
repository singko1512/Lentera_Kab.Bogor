<aside class="h-screen w-72 fixed left-0 top-0 bg-surface border-r border-outline-variant/60 shadow-[4px_0_24px_rgba(0,0,0,0.02)] z-50 flex flex-col justify-between hidden md:flex transition-all duration-300 ease-in-out">
<div class="flex flex-col h-full py-6 px-4">
<div class="mb-8 px-4 flex items-center gap-4 group cursor-pointer">
<div class="p-2 bg-primary/5 rounded-xl group-hover:scale-105 transition-transform duration-300">
    <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" class="w-10 h-10 object-contain shrink-0 drop-shadow-sm">
</div>
<div>
<h1 class="text-headline-md font-headline-md font-bold text-on-surface leading-tight tracking-tight">LENTERA</h1>
<p class="text-[12px] text-on-surface-variant font-medium truncate w-40">Kabupaten Bogor</p>
</div>
</div>
@php
    $isKesbangpol = Auth::check() && Auth::user()->dinas && Auth::user()->dinas->is_kesbangpol;
@endphp
<nav class="flex-1 space-y-1.5">
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.dashboard') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.dashboard') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.dashboard') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">dashboard</span>
<span class="text-label-md font-label-md">Dashboard</span>
</a>

@if($isKesbangpol)
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('kesbangpol.layanan*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('kesbangpol.layanan.index') }}">
<span class="material-symbols-outlined {{ Route::is('kesbangpol.layanan*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">assignment</span>
<span class="text-label-md font-label-md">Pengajuan Layanan</span>
</a>
@endif


<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.applications*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.applications.index') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.applications*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">person_add</span>
<span class="text-label-md font-label-md">Pengajuan Masuk (ACC)</span>
</a>

<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.participants*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.participants.index') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.participants*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">group</span>
<span class="text-label-md font-label-md">Peserta Magang</span>
</a>

<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.rekrutmen*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.rekrutmen.index') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.rekrutmen*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">campaign</span>
<span class="text-label-md font-label-md">Rekrutmen Layanan</span>
</a>

<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.bidang*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('dinas.bidang.index') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.bidang*') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">corporate_fare</span>
<span class="text-label-md font-label-md">Kelola Bidang</span>
</a>

<a class="flex items-center gap-3 px-4 py-3 {{ (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} rounded-xl transition-all duration-200" href="{{ route('absensi.admin.dashboard', ['tab' => 'sertifikat']) }}">
<span class="material-symbols-outlined {{ (Route::is('absensi.admin.dashboard') && request('tab') === 'sertifikat') ? 'icon-filled' : '' }} transition-transform group-hover:scale-110">workspace_premium</span>
<span class="text-label-md font-label-md">Kelola Sertifikat</span>
</a>
</nav>
<div class="mt-auto space-y-1.5 pt-6 border-t border-outline-variant/50">
<a class="flex items-center gap-3 px-4 py-3 {{ Route::is('dinas.profile*') ? 'bg-primary text-white shadow-md shadow-primary/20 font-semibold' : 'text-on-surface-variant hover:bg-surface-container-low hover:text-primary' }} transition-all duration-200 rounded-xl group" href="{{ route('dinas.profile.edit') }}">
<span class="material-symbols-outlined {{ Route::is('dinas.profile*') ? 'icon-filled' : '' }} group-hover:translate-x-1 transition-transform">person</span>
<span class="text-label-md font-label-md">Profil</span>
</a>
<a class="flex items-center gap-3 px-4 py-3 text-error/80 hover:bg-error/10 hover:text-error transition-all duration-200 rounded-xl group" href="javascript:document.getElementById('logout-form-dinas').submit();">
<span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">logout</span>
<span class="text-label-md font-label-md">Keluar</span>
</a>
</div>
</div>
</aside>
<form id="logout-form-dinas" action="{{ route('logout') }}" method="POST" class="hidden">
    @csrf
</form>
