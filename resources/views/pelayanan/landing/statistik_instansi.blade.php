@extends('pelayanan.layouts.app')

@section('title', 'Statistik Pendaftaran Instansi - Bangga Online Kab Bogor')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    primary: "var(--tw-primary, #115cb9)",
                    secondary: "var(--primary, #115cb9)",
                    "on-surface": "var(--tw-on-surface, #0b1c30)",
                    "on-surface-variant": "var(--tw-on-surface-variant, #43474f)",
                    "surface-container-lowest": "var(--tw-surface-container-lowest, #ffffff)",
                    "surface-container-low": "var(--tw-surface-container-low, #eff4ff)",
                    "outline-variant": "var(--tw-outline-variant, #c3c6d1)",
                }
            }
        }
    }
</script>

<style>
    /* ── Styling Khusus Dashboard Statistik & Tema Terang/Gelap Ultra Kontras ── */
    .talas-header-divider {
        width: 80px;
        height: 4px;
        background: #c59b27;
        margin: 10px auto 0;
        border-radius: 9999px;
    }
    
    /* Card Container */
    .stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        transition: background-color 0.25s, border-color 0.25s;
    }
    html.dark .stat-card {
        background: #0f2238;
        border-color: #1e3a5f;
        box-shadow: 0 4px 25px -2px rgba(0, 0, 0, 0.35);
    }

    /* Table Styles */
    .table-custom-header th {
        background-color: #334155;
        color: #f8fafc !important;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.05em;
        padding: 12px 14px;
        border: none;
    }
    html.dark .table-custom-header th {
        background-color: #162b45;
        color: #f1f5f9 !important;
    }

    .table-instansi-row {
        background-color: #ffffff;
        color: #1e293b;
        border-bottom: 1px solid #e2e8f0;
        transition: background-color 0.15s;
    }
    .table-instansi-row:hover {
        background-color: #f8fafc;
    }
    html.dark .table-instansi-row {
        background-color: #0f2238;
        color: #f8fafc;
        border-bottom: 1px solid #1a3352;
    }
    html.dark .table-instansi-row:hover {
        background-color: #152d49;
    }

    /* Nama Instansi High Contrast */
    .instansi-title {
        color: #0f172a;
        font-weight: 700;
        font-size: 0.85rem;
    }
    html.dark .instansi-title {
        color: #ffffff !important;
        font-weight: 700;
    }

    .instansi-email {
        color: #64748b;
        font-size: 0.75rem;
    }
    html.dark .instansi-email {
        color: #94a3b8 !important;
    }

    /* Badges */
    .badge-singkatan {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
        font-weight: 600;
    }
    html.dark .badge-singkatan {
        background-color: #1e293b;
        color: #e2e8f0;
        border-color: #334155;
    }

    .badge-tipe-dinas {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    html.dark .badge-tipe-dinas {
        background-color: rgba(217, 119, 6, 0.22);
        color: #fcd34d !important;
        border-color: rgba(217, 119, 6, 0.5);
    }

    .badge-tipe-kec {
        background-color: #f3e8ff;
        color: #6b21a8;
        border: 1px solid #e9d5ff;
    }
    html.dark .badge-tipe-kec {
        background-color: rgba(168, 85, 247, 0.22);
        color: #d8b4fe !important;
        border-color: rgba(168, 85, 247, 0.5);
    }

    /* Action Buttons */
    .btn-action-tool {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        transition: all 0.15s;
        cursor: pointer;
    }
    .btn-action-tool:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    html.dark .btn-action-tool {
        background: #152c48;
        border-color: #1e3a5f;
        color: #e2e8f0;
    }
    html.dark .btn-action-tool:hover {
        background: #1c3b63;
        color: #ffffff;
    }

    /* Search Input */
    .stat-search-input {
        background-color: #ffffff;
        color: #0f172a;
        border: 1px solid #cbd5e1;
    }
    html.dark .stat-search-input {
        background-color: #152c48 !important;
        color: #ffffff !important;
        border-color: #1e3a5f !important;
    }
    html.dark .stat-search-input::placeholder {
        color: #64748b !important;
    }

    /* Filter Tabs */
    .filter-tab-container {
        background-color: #f1f5f9;
        border: 1px solid #cbd5e1;
    }
    html.dark .filter-tab-container {
        background-color: #152c48;
        border-color: #1e3a5f;
    }

    .filter-tab-active {
        background-color: #0f172a !important;
        color: #ffffff !important;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }
    html.dark .filter-tab-active {
        background-color: #2563eb !important;
        color: #ffffff !important;
    }

    .filter-tab-inactive {
        color: #475569;
    }
    .filter-tab-inactive:hover {
        color: #0f172a;
    }
    html.dark .filter-tab-inactive {
        color: #94a3b8;
    }
    html.dark .filter-tab-inactive:hover {
        color: #ffffff;
    }

    /* ApexCharts Y-Axis & X-Axis High Contrast */
    .apexcharts-text tspan {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* Print */
    @media print {
        .no-print, header, footer, .site-nav {
            display: none !important;
        }
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .stat-card {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
        }
    }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-slate-50/80 dark:bg-[#0b1c30] text-slate-900 dark:text-slate-100 py-8 px-4 sm:px-6 lg:px-8 transition-colors duration-200">
    <div class="max-w-7xl mx-auto flex flex-col gap-8">
        
        <!-- Navigasi Kembali ke Beranda -->
        <div class="flex items-center no-print">
            <a href="{{ route('home') }}#analytics-section" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold bg-white dark:bg-[#10243d] text-primary dark:text-slate-200 border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all shadow-xs group">
                <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:-translate-x-1">arrow_back</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>

        <!-- Header Dashboard Title (Aesthetika Talas Dashboard) -->
        <div class="text-center my-2">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-100 dark:bg-amber-950/80 text-amber-900 dark:text-amber-300 border border-amber-300 dark:border-amber-700/80 mb-2">
                <span class="material-symbols-outlined text-[16px]">monitoring</span>
                Dashboard Analitik Pemerintah Kabupaten Bogor
            </span>
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold uppercase tracking-wide text-slate-900 dark:text-white">
                Statistik Pendaftaran Instansi
            </h1>
            <div class="talas-header-divider"></div>
            <p class="text-slate-600 dark:text-slate-300 text-xs sm:text-sm mt-3 max-w-2xl mx-auto font-medium">
                Rekapitulasi dan perbandingan jumlah pendaftar magang dan riset pada seluruh Perangkat Daerah (Kedinasan) dan Kecamatan di Lingkungan Pemerintah Kabupaten Bogor.
            </p>
        </div>

        <!-- 4 Quick Summary KPI Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 no-print">
            <div class="stat-card p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Instansi</span>
                    <span class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-300 material-symbols-outlined text-[20px]">account_balance</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $summary['total_instansi'] }}</span>
                    <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500 dark:text-slate-400 font-medium">
                        <span>{{ $summary['total_kedinasan'] }} Dinas/Badan</span>
                        <span>•</span>
                        <span>{{ $summary['total_kecamatan'] }} Kecamatan</span>
                    </div>
                </div>
            </div>

            <div class="stat-card p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Pendaftar</span>
                    <span class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/50 text-emerald-600 dark:text-emerald-300 material-symbols-outlined text-[20px]">group</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl sm:text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ number_format($summary['total_pendaftar'], 0, ',', '.') }}</span>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Pemohon terdaftar di sistem</p>
                </div>
            </div>

            <div class="stat-card p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Berkas Diterima</span>
                    <span class="p-2 rounded-lg bg-indigo-50 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300 material-symbols-outlined text-[20px]">verified</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl sm:text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">{{ number_format($summary['total_diterima'], 0, ',', '.') }}</span>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Permohonan disetujui / selesai</p>
                </div>
            </div>

            <div class="stat-card p-5 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sedang Diproses</span>
                    <span class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/50 text-amber-600 dark:text-amber-300 material-symbols-outlined text-[20px]">pending</span>
                </div>
                <div class="mt-4">
                    <span class="text-2xl sm:text-3xl font-extrabold text-amber-600 dark:text-amber-400">{{ number_format($summary['total_proses'], 0, ',', '.') }}</span>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1 font-medium">Verifikasi berkas & seleksi</p>
                </div>
            </div>
        </div>

        <!-- 2 Side-by-Side Horizontal Bar Charts Grid (Left: Kedinasan, Right: Kecamatan) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            
            <!-- Left Card: Grafik Pendaftaran Perangkat Daerah / Kedinasan -->
            <div class="stat-card p-6 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-slate-700/80 pb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                            Pendaftaran Perangkat Daerah
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Dinas, Badan, Sekretariat, dan RSUD Kabupaten Bogor</p>
                    </div>
                    <!-- Legend Pill (Talas Dashboard Style) -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/60 border border-amber-300 dark:border-amber-700 text-xs font-bold text-amber-800 dark:text-amber-300 self-start sm:self-auto">
                        <span class="w-3 h-3 rounded bg-amber-500 inline-block"></span>
                        <span>Jumlah Pendaftar</span>
                    </div>
                </div>

                <!-- Scrollable container for full list of Dinas -->
                <div class="overflow-y-auto max-h-[750px] pr-2 custom-scrollbar">
                    <div id="chart-dinas-container" class="w-full"></div>
                </div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 text-right pt-2 border-t border-slate-200 dark:border-slate-700/80 font-medium">
                    Menampilkan {{ count($dinasList) }} Perangkat Daerah
                </div>
            </div>

            <!-- Right Card: Grafik Pendaftaran Kecamatan -->
            <div class="stat-card p-6 flex flex-col gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-200 dark:border-slate-700/80 pb-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="w-3 h-3 rounded-full bg-purple-500 inline-block"></span>
                            Pendaftaran Kecamatan
                        </h2>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 font-medium">Seluruh Kecamatan di Wilayah Kabupaten Bogor</p>
                    </div>
                    <!-- Legend Pill (Talas Dashboard Style) -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-purple-50 dark:bg-purple-950/60 border border-purple-300 dark:border-purple-700 text-xs font-bold text-purple-800 dark:text-purple-300 self-start sm:self-auto">
                        <span class="w-3 h-3 rounded bg-purple-500 inline-block"></span>
                        <span>Jumlah Pendaftar</span>
                    </div>
                </div>

                <!-- Scrollable container for full list of Kecamatan -->
                <div class="overflow-y-auto max-h-[750px] pr-2 custom-scrollbar">
                    <div id="chart-kecamatan-container" class="w-full"></div>
                </div>
                <div class="text-[11px] text-slate-500 dark:text-slate-400 text-right pt-2 border-t border-slate-200 dark:border-slate-700/80 font-medium">
                    Menampilkan {{ count($kecamatanList) }} Kecamatan
                </div>
            </div>

        </div>

        <!-- Bottom Card: DATA REKAPITULASI PENDAFTAR INSTANSI -->
        <div class="stat-card p-6 flex flex-col gap-6">
            <!-- Header Table Card -->
            <div class="text-center sm:text-left border-b border-slate-200 dark:border-slate-700/80 pb-4">
                <h2 class="text-lg sm:text-xl font-extrabold uppercase tracking-wide text-slate-900 dark:text-white">
                    DATA REKAPITULASI PENDAFTAR INSTANSI
                </h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                    Tabel rincian pendaftar, berkas kelulusan, dan status magang setiap instansi Pemerintah Kabupaten Bogor
                </p>
            </div>

            <!-- Table Actions: Export Buttons, Filter & Search -->
            <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-4 no-print">
                <!-- Export & Filter Buttons -->
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="exportToCSV()" class="btn-action-tool flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">file_download</span>
                        Excel
                    </button>
                    <button type="button" onclick="printTable()" class="btn-action-tool flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                        PDF / Print
                    </button>

                    <span class="h-5 w-[1px] bg-slate-300 dark:bg-slate-700 mx-1 hidden sm:inline-block"></span>

                    <!-- Filter Tabs -->
                    <div class="filter-tab-container inline-flex rounded-lg p-0.5 text-xs font-semibold">
                        <button type="button" onclick="filterTable('all')" id="filter-all-btn" class="filter-tab filter-tab-active px-3 py-1.5 rounded-md transition-all font-bold">
                            Semua ({{ count($tableData) }})
                        </button>
                        <button type="button" onclick="filterTable('Perangkat Daerah')" id="filter-dinas-btn" class="filter-tab filter-tab-inactive px-3 py-1.5 rounded-md transition-all font-medium">
                            Kedinasan ({{ count($dinasList) }})
                        </button>
                        <button type="button" onclick="filterTable('Kecamatan')" id="filter-kec-btn" class="filter-tab filter-tab-inactive px-3 py-1.5 rounded-md transition-all font-medium">
                            Kecamatan ({{ count($kecamatanList) }})
                        </button>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="flex items-center gap-2 w-full lg:w-auto">
                    <label for="table-search" class="text-xs font-bold text-slate-700 dark:text-slate-300 whitespace-nowrap">Search:</label>
                    <div class="relative w-full lg:w-64">
                        <input type="text" id="table-search" onkeyup="handleSearch()" placeholder="Cari nama dinas / kecamatan..." class="stat-search-input w-full text-xs rounded-lg px-3 py-2 pr-8 focus:ring-2 focus:ring-primary focus:outline-none transition-all">
                        <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-[18px] pointer-events-none">search</span>
                    </div>
                </div>
            </div>

            <!-- Responsive Table Container -->
            <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700/80 shadow-xs">
                <table id="instansi-table" class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="table-custom-header">
                            <th class="w-12 text-center">#</th>
                            <th>Nama Instansi / Perangkat Daerah</th>
                            <th class="w-32 text-center">Tipe</th>
                            <th class="w-28 text-center cursor-pointer select-none" onclick="sortTable(3)">
                                <div class="flex items-center justify-center gap-1">
                                    <span>Pendaftar</span>
                                    <span class="material-symbols-outlined text-[14px]">swap_vert</span>
                                </div>
                            </th>
                            <th class="w-28 text-center cursor-pointer select-none" onclick="sortTable(4)">
                                <div class="flex items-center justify-center gap-1">
                                    <span>Diterima</span>
                                    <span class="material-symbols-outlined text-[14px]">swap_vert</span>
                                </div>
                            </th>
                            <th class="w-28 text-center">Diproses</th>
                            <th class="w-36 text-center">Status Magang</th>
                            <th class="w-24 text-center no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-slate-200 dark:divide-slate-800">
                        @foreach($tableData as $index => $row)
                        <tr class="table-instansi-row table-row-item" data-type="{{ $row['tipe'] }}" data-name="{{ strtolower($row['name']) }} {{ strtolower($row['singkatan']) }}">
                            <td class="text-center font-bold text-slate-500 dark:text-slate-400 py-3">{{ $index + 1 }}</td>
                            <td class="py-3 px-3">
                                <div class="flex flex-col">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="instansi-title">{{ $row['name'] }}</span>
                                        <span class="badge-singkatan px-2 py-0.5 rounded text-[10px]">{{ $row['singkatan'] }}</span>
                                    </div>
                                    <span class="instansi-email mt-0.5 flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[13px] opacity-70">mail</span>
                                        {{ $row['email'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                @if($row['is_kecamatan'])
                                    <span class="badge-tipe-kec px-2.5 py-1 rounded-full text-[11px] font-bold inline-block">
                                        Kecamatan
                                    </span>
                                @else
                                    <span class="badge-tipe-dinas px-2.5 py-1 rounded-full text-[11px] font-bold inline-block">
                                        Kedinasan
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3 font-extrabold text-sm text-slate-900 dark:text-white" data-value="{{ $row['total_pendaftar'] }}">
                                {{ $row['total_pendaftar'] }}
                            </td>
                            <td class="text-center py-3 font-bold" data-value="{{ $row['diterima'] }}">
                                <span class="px-2.5 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/80 inline-block font-bold">
                                    {{ $row['diterima'] }}
                                </span>
                            </td>
                            <td class="text-center py-3 font-bold">
                                <span class="px-2.5 py-0.5 rounded-md bg-amber-100 dark:bg-amber-950/60 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700/80 inline-block font-bold">
                                    {{ $row['proses'] }}
                                </span>
                            </td>
                            <td class="text-center py-3">
                                @if($row['sisa_kuota'] > 0)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950/60 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700/80">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        {{ $row['sisa_kuota'] }} Kuota
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-300 dark:border-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Tidak Ada Kuota
                                    </span>
                                @endif
                            </td>
                            <td class="text-center py-3 no-print">
                                <a href="{{ route('landing.instansi_detail', $row['id']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-[11px] font-bold bg-primary/10 text-primary dark:text-blue-300 hover:bg-primary hover:text-white dark:hover:bg-blue-600 dark:hover:text-white transition-colors" title="Lihat Profil Instansi">
                                    <span>Detail</span>
                                    <span class="material-symbols-outlined text-[13px]">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination & Info Footer (Talas Dashboard Style) -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-600 dark:text-slate-400 no-print font-medium">
                <div id="table-info">
                    Menampilkan <span id="showing-start" class="font-bold text-slate-800 dark:text-slate-200">1</span> sampai <span id="showing-end" class="font-bold text-slate-800 dark:text-slate-200">10</span> dari <span id="showing-total" class="font-bold text-slate-800 dark:text-slate-200">{{ count($tableData) }}</span> data
                </div>

                <!-- Page Buttons -->
                <div class="flex items-center gap-1 flex-wrap" id="pagination-controls">
                    <!-- Javascript will render pagination buttons here -->
                </div>
            </div>
        </div>

        <!-- Official Footer Reference (Talas Dashboard Style) -->
        <footer class="mt-8 text-center text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400 border-t border-slate-200 dark:border-slate-800 pt-6">
            DINAS KOMUNIKASI DAN INFORMATIKA KABUPATEN BOGOR © {{ date('Y') }}
        </footer>

    </div>
</div>
@endsection

@section('scripts')
<script>
    // Data dari Controller
    const dinasData = @json($chartDinas);
    const kecamatanData = @json($chartKecamatan);

    let chartDinasInstance = null;
    let chartKecInstance = null;

    // Helper: Mendapatkan opsi styling ApexCharts berdasarkan tema (Light / Dark)
    function getChartThemeOptions(isDark, type) {
        const textColor = isDark ? '#f8fafc' : '#1e293b';       // Terang benderang di Dark Mode, gelap di Light Mode
        const labelColor = isDark ? '#e2e8f0' : '#334155';      // Label sumbu Y
        const axisColor = isDark ? '#94a3b8' : '#64748b';       // Label sumbu X
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';
        const barColor = type === 'dinas' ? '#c59b27' : '#a855f7';
        const data = type === 'dinas' ? dinasData : kecamatanData;
        const chartHeight = Math.max(720, data.categories.length * 24);

        return {
            series: [{
                name: 'Jumlah Pendaftar',
                data: data.series
            }],
            chart: {
                type: 'bar',
                height: chartHeight,
                fontFamily: 'Plus Jakarta Sans, sans-serif',
                toolbar: { show: false },
                animations: { enabled: true, easing: 'easeinout', speed: 500 }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    borderRadius: 4,
                    barHeight: '70%'
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: [barColor],
            xaxis: {
                categories: data.categories,
                labels: {
                    style: { colors: axisColor, fontSize: '11px', fontWeight: 600 }
                },
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    style: { colors: labelColor, fontSize: '11px', fontWeight: 700 },
                    maxWidth: 170
                }
            },
            grid: {
                borderColor: gridColor,
                strokeDashArray: 3,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: isDark ? 'dark' : 'light',
                custom: function({ series, seriesIndex, dataPointIndex }) {
                    const count = series[seriesIndex][dataPointIndex];
                    const shortName = data.categories[dataPointIndex] || '';
                    const fullName = (data.full_names && data.full_names[dataPointIndex]) ? data.full_names[dataPointIndex] : shortName;
                    const rank = dataPointIndex + 1;
                    return `
                        <div style="padding: 10px 14px; font-family: 'Plus Jakarta Sans', sans-serif;" class="text-xs shadow-xl rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                <span style="background: ${barColor}; color: white; padding: 1px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">#${rank}</span>
                                <span style="font-weight: 700; font-size: 12px; color: ${barColor};">${shortName}</span>
                            </div>
                            <div style="font-size: 11px; color: #64748b; margin-bottom: 6px; max-width: 240px; line-height: 1.3;">${fullName}</div>
                            <div style="border-top: 1px solid rgba(148, 163, 184, 0.2); padding-top: 6px; display: flex; justify-content: space-between; gap: 12px; font-weight: 600;">
                                <span style="color: #64748b;">Jumlah Pendaftar:</span>
                                <span style="font-weight: 700; color: ${barColor};">${count} Orang</span>
                            </div>
                        </div>
                    `;
                }
            }
        };
    }

    // Fungsi Render / Re-render Charts
    function updateChartsTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        
        if (chartDinasInstance) {
            chartDinasInstance.updateOptions(getChartThemeOptions(isDark, 'dinas'));
        } else {
            chartDinasInstance = new ApexCharts(document.querySelector("#chart-dinas-container"), getChartThemeOptions(isDark, 'dinas'));
            chartDinasInstance.render();
        }

        if (chartKecInstance) {
            chartKecInstance.updateOptions(getChartThemeOptions(isDark, 'kecamatan'));
        } else {
            chartKecInstance = new ApexCharts(document.querySelector("#chart-kecamatan-container"), getChartThemeOptions(isDark, 'kecamatan'));
            chartKecInstance.render();
        }
    }

    // Initial Execution
    document.addEventListener('DOMContentLoaded', function() {
        updateChartsTheme();
        initTablePagination();

        // Observer untuk mendeteksi perubahan tema (Light/Dark) dari navbar secara realtime
        const themeObserver = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                if (mutation.attributeName === 'class') {
                    updateChartsTheme();
                    renderTablePage();
                }
            }
        });
        themeObserver.observe(document.documentElement, { attributes: true });
    });

    // ── Table Pagination & Filter Logic ──
    let currentPage = 1;
    const rowsPerPage = 10;
    let activeFilter = 'all';
    let searchQuery = '';

    function getFilteredRows() {
        const rows = Array.from(document.querySelectorAll('.table-row-item'));
        return rows.filter(row => {
            const matchesType = activeFilter === 'all' || row.getAttribute('data-type') === activeFilter;
            const matchesSearch = !searchQuery || row.getAttribute('data-name').includes(searchQuery.toLowerCase());
            return matchesType && matchesSearch;
        });
    }

    function initTablePagination() {
        renderTablePage();
    }

    function renderTablePage() {
        const allRows = Array.from(document.querySelectorAll('.table-row-item'));
        const filtered = getFilteredRows();
        const total = filtered.length;
        const totalPages = Math.ceil(total / rowsPerPage) || 1;

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        // Sembunyikan semua row
        allRows.forEach(r => r.style.display = 'none');

        // Tampilkan row pada page saat ini
        const start = (currentPage - 1) * rowsPerPage;
        const end = Math.min(start + rowsPerPage, total);

        for (let i = start; i < end; i++) {
            filtered[i].style.display = '';
            filtered[i].querySelector('td:first-child').textContent = (i + 1);
        }

        // Update Info Text
        document.getElementById('showing-start').textContent = total > 0 ? (start + 1) : 0;
        document.getElementById('showing-end').textContent = end;
        document.getElementById('showing-total').textContent = total;

        // Render Pagination Buttons
        renderPaginationButtons(totalPages);
    }

    function renderPaginationButtons(totalPages) {
        const container = document.getElementById('pagination-controls');
        container.innerHTML = '';

        if (totalPages <= 1) return;

        // First
        const firstBtn = createPageBtn('First', currentPage === 1, () => { currentPage = 1; renderTablePage(); });
        container.appendChild(firstBtn);

        // Prev
        const prevBtn = createPageBtn('Prev', currentPage === 1, () => { currentPage--; renderTablePage(); });
        container.appendChild(prevBtn);

        // Page numbers (max 5 buttons)
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        const isDark = document.documentElement.classList.contains('dark');

        for (let p = startPage; p <= endPage; p++) {
            const pBtn = document.createElement('button');
            pBtn.type = 'button';
            pBtn.textContent = p;
            
            if (p === currentPage) {
                pBtn.className = 'px-3 py-1.5 rounded-md font-bold text-xs shadow-xs text-white ' + (isDark ? 'bg-blue-600' : 'bg-slate-900');
            } else {
                pBtn.className = 'px-3 py-1.5 rounded-md border text-xs font-semibold ' + 
                    (isDark ? 'bg-[#152c48] text-slate-200 border-[#1e3a5f] hover:bg-[#1d3d63]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100');
            }
            
            pBtn.onclick = () => { currentPage = p; renderTablePage(); };
            container.appendChild(pBtn);
        }

        // Next
        const nextBtn = createPageBtn('Next', currentPage === totalPages, () => { currentPage++; renderTablePage(); });
        container.appendChild(nextBtn);

        // Last
        const lastBtn = createPageBtn('Last', currentPage === totalPages, () => { currentPage = totalPages; renderTablePage(); });
        container.appendChild(lastBtn);
    }

    function createPageBtn(label, disabled, onClick) {
        const btn = document.createElement('button');
        const isDark = document.documentElement.classList.contains('dark');
        btn.type = 'button';
        btn.textContent = label;
        btn.disabled = disabled;
        
        if (disabled) {
            btn.className = 'px-2.5 py-1.5 rounded-md text-xs font-semibold cursor-not-allowed opacity-50 border ' + 
                (isDark ? 'bg-[#10243d] text-slate-500 border-[#1a3352]' : 'bg-slate-100 text-slate-400 border-slate-200');
        } else {
            btn.className = 'px-2.5 py-1.5 rounded-md text-xs font-semibold cursor-pointer border ' + 
                (isDark ? 'bg-[#152c48] text-slate-200 border-[#1e3a5f] hover:bg-[#1d3d63]' : 'bg-white text-slate-700 border-slate-200 hover:bg-slate-100');
        }
        
        btn.onclick = onClick;
        return btn;
    }

    function filterTable(type) {
        activeFilter = type;
        currentPage = 1;

        // Reset class filter
        document.querySelectorAll('.filter-tab').forEach(b => {
            b.className = 'filter-tab filter-tab-inactive px-3 py-1.5 rounded-md transition-all font-medium';
        });

        const activeBtn = type === 'all' 
            ? document.getElementById('filter-all-btn')
            : (type === 'Perangkat Daerah' ? document.getElementById('filter-dinas-btn') : document.getElementById('filter-kec-btn'));
        
        if (activeBtn) {
            activeBtn.className = 'filter-tab filter-tab-active px-3 py-1.5 rounded-md transition-all font-bold';
        }

        renderTablePage();
    }

    function handleSearch() {
        searchQuery = document.getElementById('table-search').value.trim();
        currentPage = 1;
        renderTablePage();
    }

    // ── Table Sorting by Column ──
    let sortAsc = false;
    function sortTable(colIndex) {
        const tbody = document.getElementById('table-body');
        const rows = Array.from(tbody.querySelectorAll('.table-row-item'));
        sortAsc = !sortAsc;

        rows.sort((a, b) => {
            const valA = parseInt(a.children[colIndex].getAttribute('data-value') || 0, 10);
            const valB = parseInt(b.children[colIndex].getAttribute('data-value') || 0, 10);
            return sortAsc ? valA - valB : valB - valA;
        });

        rows.forEach(r => tbody.appendChild(r));
        renderTablePage();
    }

    // ── Export Functions ──
    function exportToCSV() {
        const filtered = getFilteredRows();
        let csv = 'No,Nama Instansi,Singkatan,Tipe,Total Pendaftar,Berkas Diterima,Sedang Diproses,Sisa Kuota,Status Magang\n';
        
        filtered.forEach((row, i) => {
            const name = `"${row.querySelector('.instansi-title').textContent.trim()}"`;
            const singkatan = `"${row.querySelector('.badge-singkatan').textContent.trim()}"`;
            const type = `"${row.getAttribute('data-type')}"`;
            const total = row.querySelector('td:nth-child(4)').getAttribute('data-value');
            const diterima = row.querySelector('td:nth-child(5)').getAttribute('data-value');
            const proses = row.querySelector('td:nth-child(6)').textContent.trim();
            const kuota = `"${row.querySelector('td:nth-child(7)').textContent.trim()}"`;
            csv += `${i + 1},${name},${singkatan},${type},${total},${diterima},${proses},${kuota}\n`;
        });

        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement("a");
        const url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", `statistik_instansi_kab_bogor_${new Date().toISOString().slice(0,10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function printTable() {
        window.print();
    }
</script>
@endsection
