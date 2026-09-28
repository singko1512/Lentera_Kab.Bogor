@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')

@php
    $isInternal = request('tab') === 'internal';
@endphp

<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Welcome Header -->
    <section class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-level-1 border border-outline-variant/20 relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary text-[24px]">{{ $isInternal ? 'corporate_fare' : 'support_agent' }}</span>
                <span class="text-caption font-bold uppercase tracking-wider text-primary">
                    {{ $isInternal ? 'Dashboard Rekrutmen Internal Bakesbangpol' : 'Dashboard Pelayanan Publik Bakesbangpol' }}
                </span>
            </div>
            <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">
                Selamat Datang, <span class="text-primary">{{ auth()->user()->name ?? 'Admin Bakesbangpol' }}</span>
            </h1>
            <p class="text-body-md font-body-md text-on-surface-variant max-w-2xl leading-relaxed">
                @if($isInternal)
                    Kelola kuota lowongan, penempatan bidang, serta pemantauan peserta magang khusus di lingkungan internal Bakesbangpol.
                @else
                    Kelola pendaftaran, verifikasi, dan monitoring rekomendasi magang & penelitian untuk seluruh instansi Pemerintah Kabupaten Bogor.
                @endif
            </p>
        </div>

        <!-- Decorative element -->
        <div class="absolute right-0 -top-10 h-[150%] w-1/3 opacity-[0.03] pointer-events-none">
            <svg class="w-full h-full" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path d="M47.7,-57.2C59.4,-44.6,64.8,-26.6,66.9,-8.5C69,9.5,67.8,27.7,58.7,42.5C49.6,57.3,32.6,68.7,13.6,71.8C-5.5,74.9,-26.6,69.7,-42.6,57.1C-58.5,44.5,-69.3,24.4,-70.7,4C-72,-16.3,-63.8,-36.3,-50.2,-49C-36.5,-61.6,-17.5,-67,0.1,-67.1C17.7,-67.2,35.9,-69.8,47.7,-57.2Z" fill="#115cb9" transform="translate(100 100)"></path>
            </svg>
        </div>
    </section>

    @if(!$isInternal)
    <!-- ========================================== -->
    <!-- DASHBOARD PELAYANAN PUBLIK                 -->
    <!-- ========================================== -->
    <div class="space-y-8">
        <!-- 4 Summary Cards Pelayanan -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pengajuan -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Total Pengajuan</p>
                    <span class="material-symbols-outlined text-primary opacity-80" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $totalPermohonan }}</h3>
                </div>
            </div>

            <!-- Sedang Diproses -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-[#F59E0B] flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Sedang Diproses</p>
                    <span class="material-symbols-outlined text-[#F59E0B] opacity-80" style="font-variation-settings: 'FILL' 1;">sync</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $sedangDiproses }}</h3>
                </div>
            </div>

            <!-- Peserta Disetujui -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-emerald-500 flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Peserta Disetujui</p>
                    <span class="material-symbols-outlined text-emerald-500 opacity-80" style="font-variation-settings: 'FILL' 1;">verified</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $selesai }}</h3>
                </div>
            </div>

            <!-- Ditolak -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-rose-500 flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Ditolak</p>
                    <span class="material-symbols-outlined text-rose-500 opacity-80" style="font-variation-settings: 'FILL' 1;">cancel</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $ditolak }}</h3>
                </div>
            </div>
        </div>

        <!-- Tabel Pengajuan Layanan Terbaru -->
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
            <div class="p-6 border-b border-outline-variant/30 flex items-center justify-between">
                <div>
                    <h3 class="text-title-lg font-title-lg text-on-surface font-bold">Pengajuan Layanan Terbaru</h3>
                    <p class="text-caption text-on-surface-variant mt-0.5">Daftar permohonan rekomendasi magang & penelitian yang baru masuk ke Bakesbangpol.</p>
                </div>
                <a href="{{ route('kesbangpol.layanan.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-label-md font-medium hover:bg-primary/90 transition-colors shadow-sm inline-flex items-center gap-1.5">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
                        <tr>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Pemohon</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Asal Instansi</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Jenis Layanan</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tgl Pengajuan</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Status</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($pengajuanTerbarus as $layanan)
                        <tr class="hover:bg-surface-bright/50 transition-colors group">
                            <!-- Pemohon -->
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 text-primary border border-blue-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                                        {{ strtoupper(substr($layanan->atas_nama ?? '?', 0, 2)) }}
                                    </div>
                                    <div>
                                        <p class="text-body-md font-body-md font-medium text-on-surface">{{ $layanan->atas_nama }}</p>
                                        <p class="text-caption font-caption text-on-surface-variant">{{ $layanan->no_hp ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <!-- Asal Instansi -->
                            <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">
                                {{ $layanan->asal_instansi ?? '-' }}
                            </td>

                            <!-- Jenis Layanan -->
                            <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant max-w-[220px]" title="{{ $layanan->jenisLayanan->nama ?? '-' }}">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-primary/70 shrink-0">assignment</span>
                                    <span class="truncate font-medium text-on-surface">{{ $layanan->jenisLayanan->nama ?? '-' }}</span>
                                </div>
                            </td>

                            <!-- Tgl Pengajuan -->
                            <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                                    <span>{{ $layanan->created_at->format('d M Y') }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @php
                                    $kode = strtolower($layanan->statusMaster->kode ?? '');
                                    if (in_array($kode, ['disetujui', 'selesai', 'diterima'])) {
                                        $badgeClass = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                        $dotClass = 'bg-[#28A745]';
                                    } elseif (in_array($kode, ['ditolak', 'batal'])) {
                                        $badgeClass = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                        $dotClass = 'bg-[#DC3545]';
                                    } else {
                                        $badgeClass = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                        $dotClass = 'bg-[#E0A800]';
                                    }
                                @endphp
                                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-caption font-semibold {{ $badgeClass }} border shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} shrink-0"></span>
                                    <span>{{ $layanan->statusMaster->nama ?? 'Menunggu' }}</span>
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <a href="{{ route('kesbangpol.layanan.show', $layanan->id) }}" class="w-9 h-9 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors rounded-lg inline-flex items-center justify-center border border-outline-variant/30 shrink-0 shadow-2xs" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[36px] text-slate-400">inbox</span>
                                    <p class="text-body-md font-medium text-on-surface">Belum ada pengajuan layanan baru.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
    </div>
    @else
    <!-- ========================================== -->
    <!-- DASHBOARD REKRUTMEN INTERNAL               -->
    <!-- ========================================== -->
    <div class="space-y-8">
        @if(isset($dinas) && $dinas)
        <!-- Setting Status Ketersediaan Magang Internal Bakesbangpol -->
        <section class="bg-surface-container-lowest rounded-xl p-6 border border-outline-variant/30 shadow-[0px_4px_20px_rgba(0,0,0,0.05)]">
            <form action="{{ route('dinas.status_magang.update') }}" method="POST" class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                @csrf
                <div class="flex flex-col gap-1">
                    <label for="status_magang" class="text-title-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary text-[24px]">tune</span>
                        Status Ketersediaan Magang Internal
                    </label>
                    <p class="text-body-md text-on-surface-variant">Atur penerimaan magang khusus untuk calon pendaftar magang internal Bakesbangpol.</p>
                </div>
                
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <select name="status_magang" id="status_magang" onchange="this.form.submit()" class="bg-white text-on-surface text-label-md font-semibold rounded-xl border border-outline-variant/50 focus:ring-primary focus:border-primary px-4 py-2.5 shadow-sm cursor-pointer w-full md:w-64">
                        <option value="otomatis" {{ ($dinas->status_magang ?? 'otomatis') == 'otomatis' ? 'selected' : '' }}>🔄 Otomatis (Cek Kuota)</option>
                        <option value="tersedia" {{ ($dinas->status_magang ?? '') == 'tersedia' ? 'selected' : '' }}>🟢 KUOTA TERSEDIA</option>
                        <option value="penuh" {{ ($dinas->status_magang ?? '') == 'penuh' ? 'selected' : '' }}>🔴 KUOTA PENUH</option>
                        <option value="tidak_tersedia" {{ ($dinas->status_magang ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>⚪ TIDAK TERSEDIA</option>
                    </select>
                    <button type="submit" class="px-5 py-2.5 bg-primary text-white text-label-md font-bold rounded-xl hover:bg-primary/90 transition-all shadow-sm shrink-0 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>
        </section>
        @endif

        <!-- 4 Summary Cards Internal -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Kuota -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Total Kuota</p>
                    <span class="material-symbols-outlined text-primary opacity-80" style="font-variation-settings: 'FILL' 1;">group</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $totalKuota }}</h3>
                </div>
            </div>

            <!-- Sisa Slot -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-emerald-500 flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Sisa Slot</p>
                    <span class="material-symbols-outlined text-emerald-500 opacity-80" style="font-variation-settings: 'FILL' 1;">event_seat</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $slotTersedia }}</h3>
                </div>
            </div>

            <!-- Peserta Aktif -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-[#F59E0B] flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Peserta Aktif</p>
                    <span class="material-symbols-outlined text-[#F59E0B] opacity-80" style="font-variation-settings: 'FILL' 1;">assignment_ind</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $pesertaAktif }}</h3>
                </div>
            </div>

            <!-- Pengajuan Masuk Internal -->
            <div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-indigo-500 flex flex-col justify-between h-[130px]">
                <div class="flex justify-between items-start">
                    <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Pengajuan Masuk</p>
                    <span class="material-symbols-outlined text-indigo-500 opacity-80" style="font-variation-settings: 'FILL' 1;">inbox</span>
                </div>
                <div class="mt-auto">
                    <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $totalPengajuanInternal }}</h3>
                </div>
            </div>
        </div>

        <!-- Tabel Pengajuan Masuk Internal Terbaru -->
        <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
            <div class="p-6 border-b border-outline-variant/30 flex items-center justify-between">
                <div>
                    <h3 class="text-title-lg font-title-lg text-on-surface font-bold">Pengajuan Magang Internal Terbaru</h3>
                    <p class="text-caption text-on-surface-variant mt-0.5">Daftar calon peserta yang mendaftar magang langsung di lingkungan Bakesbangpol.</p>
                </div>
                <a href="{{ route('dinas.applications.index') }}" class="px-4 py-2 bg-primary text-white rounded-lg text-label-md font-medium hover:bg-primary/90 transition-colors shadow-sm inline-flex items-center gap-1.5">
                    <span>Lihat Semua</span>
                    <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
                        <tr>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Instansi Asal</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Bidang Dituju</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tgl Pengajuan</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Status</th>
                            <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E2E8F0]">
                        @forelse($pengajuanInternalTerbarus as $app)
                        <tr class="hover:bg-surface-bright/50 transition-colors group">
                            <!-- Peserta -->
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

                            <!-- Bidang -->
                            <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">
                                <div class="flex items-center gap-1.5" title="{{ $app->bidang->name ?? 'Belum Ditentukan' }}">
                                    <span class="material-symbols-outlined text-[16px] text-slate-400 shrink-0">domain</span>
                                    <span class="max-w-[200px] truncate font-medium text-on-surface">{{ $app->bidang->name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </td>

                            <!-- Tgl Pengajuan -->
                            <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[18px] text-slate-400">calendar_today</span>
                                    <span>{{ $app->created_at->format('d M Y') }}</span>
                                </div>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                @php
                                    $st = strtolower($app->status ?? 'menunggu');
                                    if (in_array($st, ['diterima', 'disetujui', 'aktif', 'selesai'])) {
                                        $stBadge = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                        $stDot = 'bg-[#28A745]';
                                        $stLabel = 'Diterima';
                                    } elseif ($st == 'ditolak') {
                                        $stBadge = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                        $stDot = 'bg-[#DC3545]';
                                        $stLabel = 'Ditolak';
                                    } else {
                                        $stBadge = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                        $stDot = 'bg-[#E0A800]';
                                        $stLabel = 'Menunggu';
                                    }
                                @endphp
                                <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-caption font-semibold {{ $stBadge }} border shadow-2xs">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $stDot }} shrink-0"></span>
                                    <span>{{ $stLabel }}</span>
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-4 px-6 text-center whitespace-nowrap">
                                <a href="{{ route('dinas.applications.show', $app->id) }}" class="inline-flex items-center gap-1.5 px-3.5 h-8 bg-primary text-white hover:bg-primary/90 rounded-lg transition-colors text-xs font-semibold shadow-xs whitespace-nowrap" title="Detail & Verifikasi">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                                    <span>Detail</span>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-10 text-center text-on-surface-variant">
                                <div class="flex flex-col items-center justify-center gap-2">
                                    <span class="material-symbols-outlined text-[36px] text-slate-400">inbox</span>
                                    <p class="text-body-md font-medium text-on-surface">Belum ada pengajuan masuk internal.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
