@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface mb-2">Riwayat Pengajuan</h1>
        <p class="text-body-md font-body-md text-on-surface-variant max-w-3xl">Riwayat seluruh pengajuan magang yang telah selesai diproses.</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Card 1: Total Riwayat -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary flex flex-col justify-between h-[130px]">
            <div class="flex justify-between items-start">
                <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Total Riwayat</p>
                <span class="material-symbols-outlined text-primary opacity-80" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalRiwayat ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 2: Diterima -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-emerald-500 flex flex-col justify-between h-[130px]">
            <div class="flex justify-between items-start">
                <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Diterima</p>
                <span class="material-symbols-outlined text-emerald-500 opacity-80" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalDiterima ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 3: Ditolak -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-rose-500 flex flex-col justify-between h-[130px]">
            <div class="flex justify-between items-start">
                <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Ditolak</p>
                <span class="material-symbols-outlined text-rose-500 opacity-80" style="font-variation-settings: 'FILL' 1;">cancel</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalDitolak ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Card 4: Berlaku Habis -->
        <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-slate-400 flex flex-col justify-between h-[130px]">
            <div class="flex justify-between items-start">
                <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Berlaku Habis</p>
                <span class="material-symbols-outlined text-slate-400 opacity-80" style="font-variation-settings: 'FILL' 1;">timer_off</span>
            </div>
            <div class="mt-auto">
                <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalBerlakuHabis ?? 0, 0, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <!-- Toolbar (Search & Filters) -->
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6 mb-6 border border-outline-variant/20">
        <form action="{{ route('kesbangpol.history.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 items-center justify-between">
            <!-- Search Field -->
            <div class="w-full lg:w-1/3 relative">
                <label class="sr-only" for="search-history">Pencarian</label>
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70">search</span>
                <input id="search-history" 
                       name="search" 
                       value="{{ request('search') }}" 
                       class="w-full pl-10 pr-4 py-2.5 bg-surface rounded-lg border border-outline-variant/50 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-shadow text-body-md font-body-md placeholder:text-on-surface-variant/50 text-on-surface" 
                       placeholder="Cari nama peserta atau instansi..." 
                       type="text"/>
            </div>

            <!-- Filters -->
            <div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3 items-center">
                <!-- Status Filter -->
                <div class="relative min-w-[170px] w-full sm:w-auto">
                    <select name="status" onchange="this.form.submit()" class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
                        <option value="Semua Status" {{ request('status') == 'Semua Status' || !request('status') ? 'selected' : '' }}>Semua Status</option>
                        <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
                </div>

                <!-- Date Filter -->
                <div class="relative min-w-[180px] w-full sm:w-auto">
                    <input name="date" 
                           value="{{ request('date') }}" 
                           onchange="this.form.submit()" 
                           class="w-full bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer" 
                           type="date"/>
                </div>

                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-primary text-white font-medium rounded-lg text-label-md hover:bg-primary/90 transition-colors shadow-sm whitespace-nowrap">
                    Cari
                </button>

                @if(request('search') || (request('status') && request('status') !== 'Semua Status') || request('date'))
                    <a href="{{ route('kesbangpol.history.index') }}" class="w-full sm:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-label-md font-medium transition-colors inline-flex items-center gap-1.5 shrink-0 justify-center">
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
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Asal Instansi</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Jenis Layanan</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tgl. Selesai</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($historyPengajuan as $app)
                    <tr class="hover:bg-surface-bright/50 transition-colors group">
                        <!-- Nama Peserta -->
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 text-primary border border-blue-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                                    {{ strtoupper(substr($app->atas_nama ?? '?', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-body-md font-body-md font-medium text-on-surface">{{ $app->atas_nama }}</p>
                                    <p class="text-caption font-caption text-on-surface-variant flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] text-slate-400">call</span>
                                        <span>{{ $app->no_hp ?? '-' }}</span>
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Asal Instansi -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">
                            {{ $app->asal_instansi ?? '-' }}
                        </td>

                        <!-- Jenis Layanan -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant max-w-[240px]" title="{{ $app->jenisLayanan->nama ?? '-' }}">
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-lg bg-blue-50 text-primary border border-blue-200/80 flex items-center justify-center shrink-0 shadow-2xs">
                                    <span class="material-symbols-outlined text-[18px]">assignment</span>
                                </div>
                                <span class="truncate font-medium text-on-surface">{{ $app->jenisLayanan->nama ?? '-' }}</span>
                            </div>
                        </td>

                        <!-- Tgl. Selesai -->
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-[18px] text-slate-400">event_available</span>
                                <span>{{ \Carbon\Carbon::parse($app->updated_at)->format('d M Y') }}</span>
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            @php
                                $stNama = $app->statusMaster->nama ?? ($app->status ?? 'Selesai');
                                $stLower = strtolower($stNama);
                                if (in_array($stLower, ['diterima', 'disetujui', 'selesai', 'aktif'])) {
                                    $stBadgeClass = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                    $stDotClass = 'bg-[#28A745]';
                                } elseif (in_array($stLower, ['ditolak', 'batal'])) {
                                    $stBadgeClass = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                    $stDotClass = 'bg-[#DC3545]';
                                } elseif (str_contains($stLower, 'menunggu') || str_contains($stLower, 'proses')) {
                                    $stBadgeClass = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                    $stDotClass = 'bg-[#E0A800]';
                                } else {
                                    $stBadgeClass = 'bg-slate-100 text-slate-700 border-slate-300';
                                    $stDotClass = 'bg-slate-400';
                                }
                            @endphp
                            <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-caption font-semibold {{ $stBadgeClass }} border shadow-2xs">
                                <span class="w-1.5 h-1.5 rounded-full {{ $stDotClass }} shrink-0"></span>
                                <span>{{ $stNama }}</span>
                            </span>
                        </td>

                        <!-- Aksi -->
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center">
                                <a href="{{ route('kesbangpol.layanan.show', $app->id) }}" class="w-9 h-9 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors rounded-lg flex items-center justify-center border border-outline-variant/30 shrink-0 shadow-2xs" title="Lihat Detail Riwayat">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-1">
                                    <span class="material-symbols-outlined text-[24px]">history</span>
                                </div>
                                <p class="text-body-md font-medium text-on-surface">Belum ada riwayat pengajuan</p>
                                <p class="text-caption text-on-surface-variant">Pengajuan yang telah selesai diproses akan diarsipkan di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Pagination -->
        @if($historyPengajuan->hasPages())
        <div class="bg-surface-container-lowest px-6 py-4 border-t border-outline-variant/30 flex items-center justify-between">
            <div class="w-full">
                {{ $historyPengajuan->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
