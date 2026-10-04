@extends('pelayanan.layouts.dinas_stitch')

@section('content')
@php
    $namaDinas = $dinas->name ?? ($dinas->nama ?? 'Dinas');
    $singkatanDinas = \App\Models\Dinas::formatSingkatan($namaDinas);
@endphp

<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface mb-2">Pengajuan Magang / PKL Masuk</h1>
        <p class="text-body-md font-body-md text-on-surface-variant max-w-3xl">Daftar permohonan magang yang masuk ke {{ $singkatanDinas }}.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-800 p-4 rounded-xl border border-emerald-200/80 flex items-center gap-3 shadow-xs">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <span class="text-body-md font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Toolbar (Search & Filters) -->
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6 mb-6 border border-outline-variant/20">
        <form method="GET" action="{{ route('dinas.applications.index') }}" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <!-- Search Field -->
            <div class="w-full lg:w-1/3 relative">
                <label class="sr-only" for="search-peserta">Cari nama peserta atau instansi</label>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70">search</span>
                <input class="w-full pl-10 pr-4 py-2.5 bg-surface rounded-lg border border-outline-variant/50 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-shadow text-body-md font-body-md placeholder:text-on-surface-variant/50 text-on-surface" 
                       id="search-peserta" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama peserta atau instansi..." 
                       type="text"/>
            </div>
            
            <!-- Filters -->
            <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
                <div class="relative min-w-[200px]">
                    <select name="status" onchange="this.form.submit()" class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
                </div>

                @if(request('search') || request('status'))
                    <a href="{{ route('dinas.applications.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-label-md font-medium transition-colors inline-flex items-center gap-1.5 shrink-0 justify-center">
                        <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                        <span>Reset</span>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table Card -->
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
                    <tr>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Instansi Asal</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status & Penempatan</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tanggal Pengajuan</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Periode Magang</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($applications as $app)
                    <tr class="hover:bg-surface-bright/50 transition-colors group">
                        <!-- Nama Peserta -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 text-primary border border-blue-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                                    {{ strtoupper(substr($app->user->nama ?? ($app->user->name ?? '?'), 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-body-md font-body-md font-medium text-on-surface">{{ $app->user->nama ?? ($app->user->name ?? '-') }}</p>
                                    <p class="text-caption font-caption text-on-surface-variant">{{ $app->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>

                        <!-- Instansi Asal -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">
                            {{ $app->user->asal_instansi ?? ($app->instansi_asal ?? '-') }}
                        </td>

                        <!-- Status & Penempatan -->
                        <td class="py-4 px-6 whitespace-nowrap">
                            @php
                                $st = strtolower($app->status ?? 'menunggu');
                                if (in_array($st, ['diterima', 'disetujui', 'aktif', 'selesai'])) {
                                    $statusBadgeClass = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                    $dotStatusClass = 'bg-[#28A745]';
                                    $statusLabel = 'Diterima';
                                } elseif ($st == 'ditolak') {
                                    $statusBadgeClass = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                    $dotStatusClass = 'bg-[#DC3545]';
                                    $statusLabel = 'Ditolak';
                                } elseif (str_contains($st, 'menunggu')) {
                                    $statusBadgeClass = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                    $dotStatusClass = 'bg-[#E0A800]';
                                    $statusLabel = 'Menunggu';
                                } else {
                                    $statusBadgeClass = 'bg-slate-100 text-slate-700 border-slate-300';
                                    $dotStatusClass = 'bg-slate-400';
                                    $statusLabel = ucfirst($app->status ?? 'Menunggu');
                                }
                            @endphp
                            <div class="flex flex-col gap-1.5 items-start">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-caption font-semibold {{ $statusBadgeClass }} border shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotStatusClass }} shrink-0"></span>
                                    <span>{{ $statusLabel }}</span>
                                </span>
                                @if($app->permohonanLayanan && $app->permohonanLayanan->statusMaster)
                                    @php
                                        $kbKode = $app->permohonanLayanan->statusMaster->kode;
                                        if (in_array($kbKode, ['disetujui', 'selesai'])) {
                                            $kbClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            $kbLabel = 'Kesbangpol: Disetujui';
                                        } elseif ($kbKode == 'ditolak') {
                                            $kbClass = 'bg-red-50 text-red-700 border-red-200';
                                            $kbLabel = 'Kesbangpol: Ditolak';
                                        } elseif ($kbKode == 'perlu_revisi') {
                                            $kbClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                            $kbLabel = 'Kesbangpol: Perlu Revisi';
                                        } else {
                                            $kbClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                            $kbLabel = 'Kesbangpol: Pending';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold {{ $kbClass }} border">
                                        <span class="material-symbols-outlined text-[13px]">verified</span>
                                        {{ $kbLabel }}
                                    </span>
                                @endif
                                @if($app->bidang)
                                    <span class="text-caption font-caption text-on-surface-variant font-medium flex items-center gap-1.5" title="{{ $app->bidang->name }}">
                                        <span class="material-symbols-outlined text-[15px] text-slate-400 shrink-0">domain</span>
                                        <span class="max-w-[220px] truncate">{{ $app->bidang->name }}</span>
                                    </span>
                                @else
                                    <span class="text-caption font-caption text-slate-400 italic">Belum Ditentukan</span>
                                @endif
                            </div>
                        </td>

                        <!-- Tanggal Pengajuan -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                                <span>{{ $app->created_at->format('d M Y') }}</span>
                            </div>
                        </td>

                        <!-- Periode Magang -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap">
                            <div class="flex items-center gap-2.5">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary border border-blue-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="material-symbols-outlined text-[18px]">date_range</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-medium text-on-surface">{{ $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') : '-' }}</span>
                                    <span class="text-caption font-caption text-on-surface-variant">s/d {{ $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') : '-' }}</span>
                                </div>
                            </div>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-2">
                                <a href="{{ route('dinas.applications.show', $app->id) }}" class="inline-flex items-center gap-2 px-4 h-9 bg-primary text-white hover:bg-primary/90 rounded-lg transition-colors text-label-md font-label-md font-medium shadow-sm whitespace-nowrap" title="Detail & Verifikasi">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    <span>Detail & Verifikasi</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-1">
                                    <span class="material-symbols-outlined text-[24px]">inbox</span>
                                </div>
                                <p class="text-body-md font-medium text-on-surface">Tidak ada pengajuan magang yang masuk saat ini</p>
                                <p class="text-caption text-on-surface-variant">Pengajuan magang baru dari peserta akan muncul pada daftar ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($applications->hasPages())
        <div class="bg-surface-container-lowest px-6 py-4 border-t border-outline-variant/30 flex items-center justify-between">
            <div class="w-full">
                {{ $applications->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
