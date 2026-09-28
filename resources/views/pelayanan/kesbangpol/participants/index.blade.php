@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
<!-- Page Header -->
<div class="mb-8">
<h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface mb-2">Manajemen Peserta</h1>
<p class="text-body-md font-body-md text-on-surface-variant max-w-3xl">Kelola akun peserta selama berada dalam proses administrasi Bakesbangpol.</p>
</div>
<!-- Summary Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-secondary flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Peserta Aktif</p>
        <span class="material-symbols-outlined text-secondary opacity-80" style="font-variation-settings: 'FILL' 1;">group</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $aktif }}</h3>
    </div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary-container flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Perlu Tindakan</p>
        <span class="material-symbols-outlined text-primary-container opacity-80" style="font-variation-settings: 'FILL' 1;">assignment_late</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $perluTindakan }}</h3>
    </div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-[#F59E0B] flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Dibatasi</p>
        <span class="material-symbols-outlined text-[#F59E0B] opacity-80" style="font-variation-settings: 'FILL' 1;">warning</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $dibatasi }}</h3>
    </div>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-error flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Diblokir</p>
        <span class="material-symbols-outlined text-error opacity-80" style="font-variation-settings: 'FILL' 1;">block</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $diblokir }}</h3>
    </div>
</div>
</div>
<!-- Toolbar (Search & Filters) -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6 mb-6 flex flex-col lg:flex-row gap-4 items-center justify-between border border-outline-variant/20">
<!-- Search -->
<div class="w-full lg:w-1/3 relative">
<label class="sr-only" for="search-peserta">Cari nama peserta</label>
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70">search</span>
<input class="w-full pl-10 pr-4 py-2.5 bg-surface rounded-lg border border-outline-variant/50 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-shadow text-body-md font-body-md placeholder:text-on-surface-variant/50 text-on-surface" id="search-peserta" placeholder="Cari nama peserta..." type="text"/>
</div>
<!-- Filters -->
<div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
<div class="relative min-w-[180px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Status Akun</option>
<option value="aktif">Aktif</option>
<option value="dibatasi">Dibatasi</option>
<option value="diblokir">Diblokir</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
<div class="relative min-w-[200px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Status Pengajuan</option>
<option value="menunggu">Menunggu Verifikasi</option>
<option value="diterima">Diterima</option>
<option value="ditolak">Ditolak</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
<div class="relative min-w-[180px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Dinas Tujuan</option>
<option value="kominfo">Dinas Kominfo</option>
<option value="kesehatan">Dinas Kesehatan</option>
<option value="pendidikan">Dinas Pendidikan</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
</div>
</div>
<!-- Data Table Card -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
<tr>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Asal Instansi</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Dinas Tujuan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status Pengajuan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status Akun</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tahap Pengelolaan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-[#E2E8F0]">
                                @forelse($participants as $p)
                                @php
                                    $app = $p->magangApplications->first();
                                    $status = $app->status ?? 'Belum Mengajukan';
                                    $rawDinas = $app?->rekrutmen?->dinas?->nama 
                                        ?? $app?->permohonanLayanan?->dinas?->name 
                                        ?? $app?->permohonanLayanan?->dinas?->nama 
                                        ?? $app?->dinas?->name 
                                        ?? $app?->permohonanLayanan?->tempat_kegiatan 
                                        ?? null;
                                    $dinasSingkat = $rawDinas ? \App\Models\Dinas::formatSingkatan($rawDinas) : '-';
                                    $isDiterimaDinas = in_array(strtolower($status), ['diterima', 'aktif', 'selesai']) && !empty($rawDinas);
                                @endphp
                                <tr class="hover:bg-surface-bright/50 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 text-primary border border-blue-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                                                {{ strtoupper(substr($p->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-body-md font-body-md font-medium text-on-surface">{{ $p->name }}</p>
                                                <p class="text-caption font-caption text-on-surface-variant">{{ $p->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ $p->asal_instansi ?? '-' }}</td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap" title="{{ $rawDinas ?? '-' }}">
                                        {{ $dinasSingkat }}
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @php
                                            $st = strtolower($status);
                                            if (in_array($st, ['diterima', 'disetujui', 'aktif', 'selesai'])) {
                                                $statusBadgeClass = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                                $dotStatusClass = 'bg-[#28A745]';
                                                $statusLabel = 'Diterima';
                                            } elseif ($st == 'menunggu' || str_contains($st, 'menunggu')) {
                                                $statusBadgeClass = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                                $dotStatusClass = 'bg-[#E0A800]';
                                                $statusLabel = 'Menunggu';
                                            } elseif ($st == 'ditolak') {
                                                $statusBadgeClass = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                                $dotStatusClass = 'bg-[#DC3545]';
                                                $statusLabel = 'Ditolak';
                                            } else {
                                                $statusBadgeClass = 'bg-slate-100 text-slate-700 border-slate-300';
                                                $dotStatusClass = 'bg-slate-400';
                                                $statusLabel = 'Belum Mengajukan';
                                            }
                                        @endphp
                                        <span class="w-36 inline-flex items-center justify-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-semibold {{ $statusBadgeClass }} border shadow-2xs">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotStatusClass }} shrink-0"></span>
                                            <span class="truncate">{{ $statusLabel }}</span>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @php
                                            $statusAkun = strtolower($p->status_akun ?? 'aktif');
                                            if ($statusAkun == 'aktif') {
                                                $badgeClass = 'bg-[#D4EDDA] text-[#155724] border border-[#C3E6CB]';
                                                $dotClass = 'bg-[#28A745]';
                                            } elseif ($statusAkun == 'dibatasi') {
                                                $badgeClass = 'bg-yellow-100 text-yellow-800 border border-yellow-200';
                                                $dotClass = 'bg-yellow-500';
                                            } else {
                                                $badgeClass = 'bg-red-100 text-red-800 border border-red-200';
                                                $dotClass = 'bg-red-500';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-caption font-medium {{ $badgeClass }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span> {{ ucfirst($statusAkun) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @php
                                            $isBakesbangpol = str_contains(strtoupper($rawDinas ?? ''), 'KESATUAN BANGSA') || strtolower($dinasSingkat) === 'bakesbangpol';
                                        @endphp
                                        
                                        <div x-data="{ 
                                            open: false,
                                            x: 0, 
                                            y: 0,
                                            show(e) {
                                                const rect = e.currentTarget.getBoundingClientRect();
                                                this.x = rect.left + (rect.width / 2);
                                                this.y = rect.top;
                                                this.open = true;
                                            },
                                            hide() {
                                                this.open = false;
                                            }
                                        }" 
                                        @mouseleave="hide()"
                                        @scroll.window="hide()"
                                        class="inline-block">

                                            @if($isDiterimaDinas)
                                                @if($isBakesbangpol)
                                                    {{-- Kasus: Diterima di internal Bakesbangpol --}}
                                                    <div @mouseenter="show($event)" class="flex items-center gap-2.5 cursor-pointer">
                                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 text-indigo-700 border border-indigo-200/80 flex items-center justify-center shrink-0 shadow-2xs hover:scale-105 transition-transform">
                                                            <span class="material-symbols-outlined text-[18px]">account_balance</span>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="text-body-md font-body-md font-semibold text-on-surface">Bakesbangpol</span>
                                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-indigo-100 text-indigo-700 border border-indigo-200/60 uppercase">Internal</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Tooltip Card (Fixed Viewport via Teleport to Body, Always Upwards, Zero Scrollbar Impact) -->
                                                    <template x-teleport="body">
                                                        <div x-show="open"
                                                             x-cloak
                                                             x-transition:enter="transition ease-out duration-150"
                                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                                             x-transition:enter-end="opacity-100 translate-y-0"
                                                             x-transition:leave="transition ease-in duration-100"
                                                             x-transition:leave-start="opacity-100 translate-y-0"
                                                             x-transition:leave-end="opacity-0 -translate-y-1"
                                                             :style="`position: fixed; left: ${x}px; top: ${y - 10}px; transform: translate(-50%, -100%);`"
                                                             class="z-[99999] pointer-events-none w-72 p-3.5 bg-slate-900/95 text-white rounded-xl shadow-2xl text-left border border-slate-700/80 backdrop-blur-sm whitespace-normal">
                                                            <div class="flex items-center gap-1.5 text-indigo-300 font-bold text-xs mb-1 whitespace-normal">
                                                                <span class="material-symbols-outlined text-[16px] shrink-0">account_balance</span>
                                                                <span>Tahap Internal Bakesbangpol</span>
                                                            </div>
                                                            <p class="text-[11px] text-slate-300 leading-relaxed whitespace-normal break-words">
                                                                Peserta telah resmi diterima magang di Bakesbangpol. Operasional bimbingan, penempatan bidang, dan absensi dikelola melalui menu <strong>Rekrutmen Internal</strong>.
                                                            </p>
                                                            <div class="absolute left-1/2 -translate-x-1/2 top-full w-0 h-0 border-x-[6px] border-x-transparent border-t-[6px] border-t-slate-900/95"></div>
                                                        </div>
                                                    </template>
                                                @else
                                                    {{-- Kasus: Diterima di dinas lain --}}
                                                    <div @mouseenter="show($event)" class="flex items-center gap-2.5 cursor-pointer">
                                                        <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary border border-blue-200/80 flex items-center justify-center shrink-0 shadow-2xs hover:scale-105 transition-transform">
                                                            <span class="material-symbols-outlined text-[18px]">corporate_fare</span>
                                                        </div>
                                                        <div class="flex flex-col">
                                                            <div class="flex items-center gap-1.5">
                                                                <span class="text-body-md font-body-md font-semibold text-on-surface">{{ $dinasSingkat }}</span>
                                                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-blue-100 text-primary border border-blue-200/60 uppercase">Dinas Tujuan</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Tooltip Card (Fixed Viewport via Teleport to Body, Always Upwards, Zero Scrollbar Impact) -->
                                                    <template x-teleport="body">
                                                        <div x-show="open"
                                                             x-cloak
                                                             x-transition:enter="transition ease-out duration-150"
                                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                                             x-transition:enter-end="opacity-100 translate-y-0"
                                                             x-transition:leave="transition ease-in duration-100"
                                                             x-transition:leave-start="opacity-100 translate-y-0"
                                                             x-transition:leave-end="opacity-0 -translate-y-1"
                                                             :style="`position: fixed; left: ${x}px; top: ${y - 10}px; transform: translate(-50%, -100%);`"
                                                             class="z-[99999] pointer-events-none w-72 p-3.5 bg-slate-900/95 text-white rounded-xl shadow-2xl text-left border border-slate-700/80 backdrop-blur-sm whitespace-normal">
                                                            <div class="flex items-center gap-1.5 text-blue-300 font-bold text-xs mb-1 whitespace-normal">
                                                                <span class="material-symbols-outlined text-[16px] shrink-0">corporate_fare</span>
                                                                <span>Dikelola: {{ $dinasSingkat }}</span>
                                                            </div>
                                                            <p class="text-[10px] text-slate-400 font-medium mb-1 truncate whitespace-nowrap">{{ $rawDinas }}</p>
                                                            <p class="text-[11px] text-slate-300 leading-relaxed whitespace-normal break-words">
                                                                Peserta telah diterima di dinas yang dituju. Hak pengelolaan akun, bimbingan, penempatan bidang, dan verifikasi absensi dialihkan sepenuhnya ke dinas ini.
                                                            </p>
                                                            <div class="absolute left-1/2 -translate-x-1/2 top-full w-0 h-0 border-x-[6px] border-x-transparent border-t-[6px] border-t-slate-900/95"></div>
                                                        </div>
                                                    </template>
                                                @endif
                                            @else
                                                {{-- Kasus: Masih dalam tahap Pelayanan Bakesbangpol --}}
                                                <div @mouseenter="show($event)" class="flex items-center gap-2.5 cursor-pointer">
                                                    <div class="h-8 w-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0 shadow-2xs hover:scale-105 transition-transform">
                                                        <span class="material-symbols-outlined text-[18px]">support_agent</span>
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-body-md font-body-md font-semibold text-on-surface">Bakesbangpol</span>
                                                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-200/60 uppercase">Pelayanan</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Tooltip Card (Fixed Viewport via Teleport to Body, Always Upwards, Zero Scrollbar Impact) -->
                                                <template x-teleport="body">
                                                    <div x-show="open"
                                                         x-cloak
                                                         x-transition:enter="transition ease-out duration-150"
                                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         x-transition:leave="transition ease-in duration-100"
                                                         x-transition:leave-start="opacity-100 translate-y-0"
                                                         x-transition:leave-end="opacity-0 -translate-y-1"
                                                         :style="`position: fixed; left: ${x}px; top: ${y - 10}px; transform: translate(-50%, -100%);`"
                                                         class="z-[99999] pointer-events-none w-72 p-3.5 bg-slate-900/95 text-white rounded-xl shadow-2xl text-left border border-slate-700/80 backdrop-blur-sm whitespace-normal">
                                                        <div class="flex items-center gap-1.5 text-amber-400 font-bold text-xs mb-1 whitespace-normal">
                                                            <span class="material-symbols-outlined text-[16px] shrink-0">support_agent</span>
                                                            <span>Tahap Pelayanan Publik Bakesbangpol</span>
                                                        </div>
                                                        <p class="text-[11px] text-slate-300 leading-relaxed whitespace-normal break-words">
                                                            Peserta masih dalam tahap administrasi pelayanan rekomendasi magang & verifikasi berkas oleh Bakesbangpol sebelum diteruskan ke dinas tujuan.
                                                        </p>
                                                        <div class="absolute left-1/2 -translate-x-1/2 top-full w-0 h-0 border-x-[6px] border-x-transparent border-t-[6px] border-t-slate-900/95"></div>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center whitespace-nowrap">
                                        <div class="inline-flex items-center justify-center gap-2">
                                            <a href="{{ route('kesbangpol.participants.detail', $p->id) }}" aria-label="Lihat Detail" class="w-9 h-9 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors rounded-lg flex items-center justify-center border border-outline-variant/30 shrink-0" title="Lihat Detail">
                                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                                            </a>
                                            <div class="w-32 shrink-0">
                                                @if($isDiterimaDinas)
                                                    <span class="w-full h-9 inline-flex items-center justify-center gap-1.5 px-2.5 bg-slate-100 text-slate-500 rounded-lg text-caption font-medium border border-slate-200 cursor-not-allowed select-none whitespace-nowrap" title="Peserta telah diterima di {{ $dinasSingkat }}. Hak pengelolaan akun dialihkan ke dinas yang dituju.">
                                                        <span class="material-symbols-outlined text-[16px] text-slate-400">lock</span>
                                                        <span>Dikelola Dinas</span>
                                                    </span>
                                                @else
                                                    <button type="button" data-id="{{ $p->id }}" data-name="{{ $p->name }}" data-status="{{ strtolower($p->status_akun ?? 'aktif') }}" onclick="openKelolaAkunModal(this)" class="w-full h-9 px-3 bg-primary text-white text-label-md font-label-md font-medium rounded-lg hover:bg-primary/90 transition-colors shadow-sm whitespace-nowrap flex items-center justify-center">
                                                        Kelola Akun
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-on-surface-variant">Belum ada peserta yang diterima.</td>
                                </tr>
                                @endforelse
                            </tbody>
</table>
</div>
<!-- Pagination -->
<div class="bg-surface-container-lowest px-6 py-4 border-t border-outline-variant/30 flex items-center justify-between">
    <div class="w-full">
        {{ $participants->links() }}
    </div>
</div>
</div>
</div>

<!-- Modal Kelola Akun -->
<div id="modalKelolaAkun" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300" id="modalKelolaAkunContent">
        <form id="formKelolaAkun" method="POST" action="">
            @csrf
            <div class="p-6 border-b border-outline-variant/30 flex items-center justify-between">
                <h3 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">manage_accounts</span>
                    Kelola Akun Peserta
                </h3>
                <button type="button" onclick="closeKelolaAkunModal()" class="text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <p class="text-body-md text-on-surface-variant">Atur status akun untuk peserta: <strong id="modalPesertaName" class="text-on-surface"></strong></p>
                
                <div class="flex flex-col gap-2">
                    <label class="text-label-md font-bold text-on-surface">Status Akun</label>
                    <select name="status_akun" id="modalStatusAkun" class="w-full rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" required>
                        <option value="aktif">Aktif (Normal)</option>
                        <option value="dibatasi">Dibatasi (Hanya bisa melihat data)</option>
                        <option value="diblokir">Diblokir (Indikasi Spam/Melanggar)</option>
                    </select>
                    <p class="text-caption text-on-surface-variant mt-1">
                        Pilih <b>Diblokir</b> untuk menonaktifkan total akses pengguna ini dan mencegah spam pengajuan.
                    </p>
                </div>
            </div>
            
            <div class="p-6 border-t border-outline-variant/30 bg-surface-container-low/50 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="closeKelolaAkunModal()" class="px-5 py-2.5 rounded-lg border border-outline text-primary font-label-lg hover:bg-surface-variant transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label-lg hover:bg-primary/90 transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openKelolaAkunModal = function(btn) {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const currentStatus = btn.dataset.status;
        
        const modal = document.getElementById('modalKelolaAkun');
        const modalContent = document.getElementById('modalKelolaAkunContent');
        const form = document.getElementById('formKelolaAkun');
        
        // Update URL form
        form.action = `/kesbangpol/participants/${id}/status`;
        
        document.getElementById('modalPesertaName').textContent = name;
        document.getElementById('modalStatusAkun').value = currentStatus;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }
    
    window.closeKelolaAkunModal = function() {
        const modal = document.getElementById('modalKelolaAkun');
        const modalContent = document.getElementById('modalKelolaAkunContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>

@endsection
