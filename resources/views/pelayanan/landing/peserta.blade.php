@extends('pelayanan.layouts.app')

@section('title', 'Daftar Peserta Magang - Bangga Online')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline": "#737780",
                        "on-surface-variant": "var(--tw-on-surface-variant)",
                        "on-tertiary-fixed": "#161c22",
                        "surface-container": "var(--tw-surface-container)",
                        "primary-fixed-dim": "#a7c8ff",
                        "outline-variant": "var(--tw-outline-variant)",
                        "on-primary": "#ffffff",
                        "surface-container-low": "var(--tw-surface-container-low)",
                        "tertiary-container": "#2e343a",
                        "on-surface": "var(--tw-on-surface)",
                        "background": "var(--tw-background)",
                        "tertiary-fixed-dim": "#c1c7cf",
                        "primary-container": "#003366",
                        "secondary-fixed-dim": "#acc7ff",
                        "on-tertiary": "#ffffff",
                        "on-secondary-fixed": "#001a40",
                        "on-tertiary-fixed-variant": "#41474e",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-lowest": "var(--tw-surface-container-lowest)",
                        "surface-bright": "#f8f9ff",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-tint": "#3a5f94",
                        "on-primary-fixed-variant": "#1f477b",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#003370",
                        "secondary": "#115cb9",
                        "surface-variant": "var(--tw-surface-variant)",
                        "on-error": "#ffffff",
                        "on-primary-fixed": "#001b3c",
                        "on-error-container": "#93000a",
                        "secondary-container": "#659dfe",
                        "inverse-primary": "#a7c8ff",
                        "on-primary-container": "#799dd6",
                        "surface-dim": "#cbdbf5",
                        "on-tertiary-container": "#969ca4",
                        "tertiary-fixed": "#dde3eb",
                        "tertiary": "#191f25",
                        "surface": "var(--tw-background)",
                        "on-background": "var(--tw-on-surface)",
                        "inverse-surface": "#213145",
                        "secondary-fixed": "#d7e2ff",
                        "error": "#ba1a1a",
                        "primary": "var(--tw-primary)",
                        "surface-container-high": "#dce9ff",
                        "primary-fixed": "#d5e3ff",
                        "on-secondary-fixed-variant": "#004491",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-desktop": "2.5rem",
                        "gutter": "1.5rem",
                        "stack-md": "1rem",
                        "margin-mobile": "1rem",
                        "container-max": "1280px",
                        "stack-lg": "2rem",
                        "stack-sm": "0.5rem"
                    },
                    "fontFamily": {
                        "body-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "title-lg": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "caption": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "60px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "title-lg": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "700" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                        "caption": ["12px", { "lineHeight": "16px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>

@endsection

@section('content')


<!-- Main Content -->
<main class="flex-grow w-full max-w-container-max mx-auto px-margin-desktop py-stack-lg mt-8">
<!-- Back to Home Button -->
<div class="mb-stack-lg">
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800 text-primary font-semibold text-sm rounded-xl transition-all border border-outline-variant/40 hover:border-primary shadow-xs group">
        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:-translate-x-1">arrow_back</span>
        <span>Kembali ke Beranda</span>
    </a>
</div>

<!-- Header Section -->
<div class="mb-stack-lg">
<h1 class="font-headline-lg text-headline-lg md:font-display-lg md:text-display-lg text-on-surface mb-stack-sm">Peserta Magang Diterima</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl">Daftar peserta yang telah resmi diterima dalam program magang di lingkungan Pemerintah Kabupaten Bogor.</p>
</div>
<!-- Filters & Search -->
<form method="GET" action="{{ route('landing.peserta') }}" id="filter-peserta-form">
<div class="bg-surface-container-lowest p-gutter rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border-t-4 border-primary mb-stack-lg">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-stack-md">
        
        <!-- Cari Peserta -->
        <div class="md:col-span-4 flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1.5" for="search">
                <span class="material-symbols-outlined text-[18px] text-primary">person_search</span>
                <span>Cari Peserta</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <span class="material-symbols-outlined text-outline text-[20px]">search</span>
                </div>
                <input name="search" value="{{ $search }}" class="bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary block w-full ps-10 p-2.5 transition-all shadow-xs" id="search" placeholder="Cari nama peserta, institusi, jurusan..." type="text"/>
                @if(!empty($search))
                    <button type="button" onclick="document.getElementById('search').value=''; document.getElementById('filter-peserta-form').submit();" class="absolute inset-y-0 end-0 flex items-center pe-3 text-outline hover:text-red-500 transition-colors" title="Hapus pencarian">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                @endif
            </div>
        </div>

        <!-- Tujuan Dinas (Searchable Dropdown) -->
        <div class="md:col-span-4 flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-primary">apartment</span>
                <span>Tujuan Dinas</span>
            </label>
            
            <div class="searchable-dropdown relative" id="dropdown-dinas">
                <input type="hidden" name="dinas_id" value="{{ $dinasId }}" class="searchable-input-val">
                <button type="button" class="searchable-trigger w-full flex items-center justify-between text-left bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary p-2.5 transition-all shadow-xs cursor-pointer" aria-haspopup="listbox" aria-expanded="false">
                    <span class="searchable-label truncate font-medium">
                        {{ $selectedDinasName ?? 'Semua Dinas' }}
                    </span>
                    <span class="material-symbols-outlined text-outline text-[20px] transition-transform duration-200 chevron-icon">expand_more</span>
                </button>

                <div class="searchable-menu hidden absolute z-50 mt-1 w-full min-w-[280px] bg-white dark:bg-[#0f243d] border border-outline-variant/60 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden">
                    <div class="p-2 border-b border-outline-variant/20 dark:border-slate-700/60 bg-surface-container-low/50 dark:bg-slate-900/40">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                            <input type="text" class="searchable-filter-input w-full bg-white dark:bg-[#152d4b] border border-outline-variant/50 dark:border-slate-600 rounded-lg pl-8 pr-3 py-1.5 text-xs text-on-surface placeholder-outline-variant focus:outline-none focus:ring-1 focus:ring-secondary" placeholder="Ketik untuk mencari dinas..." autocomplete="off">
                        </div>
                    </div>
                    <ul class="searchable-list max-h-56 overflow-y-auto py-1 text-xs text-on-surface" role="listbox">
                        <li class="searchable-item px-3 py-2 cursor-pointer hover:bg-secondary/10 dark:hover:bg-blue-900/40 flex items-center justify-between transition-colors font-semibold text-primary {{ empty($dinasId) ? 'bg-secondary/10' : '' }}" data-value="" data-label="Semua Dinas">
                            <span>Semua Dinas</span>
                            @if(empty($dinasId))
                                <span class="material-symbols-outlined text-secondary text-[16px]">check</span>
                            @endif
                        </li>
                        @foreach($semuaDinas as $dn)
                        @php $dName = \App\Models\Dinas::formatTitleCaseKbbi($dn->name); @endphp
                        <li class="searchable-item px-3 py-2 cursor-pointer hover:bg-secondary/10 dark:hover:bg-blue-900/40 flex items-center justify-between transition-colors {{ (string)$dinasId === (string)$dn->id ? 'bg-secondary/10 font-bold text-secondary' : '' }}" data-value="{{ $dn->id }}" data-label="{{ $dName }}">
                            <span class="truncate" title="{{ $dName }}">{{ $dName }}</span>
                            @if((string)$dinasId === (string)$dn->id)
                                <span class="material-symbols-outlined text-secondary text-[16px]">check</span>
                            @endif
                        </li>
                        @endforeach
                        <li class="searchable-no-match hidden px-3 py-4 text-center text-outline text-xs">
                            Tidak ada dinas yang cocok
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Instansi Pendidikan (Searchable Dropdown) -->
        <div class="md:col-span-4 flex flex-col gap-1.5">
            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[18px] text-primary">school</span>
                <span>Instansi Pendidikan</span>
            </label>
            
            <div class="searchable-dropdown relative" id="dropdown-instansi">
                <input type="hidden" name="instansi_asal" value="{{ $instansiAsal }}" class="searchable-input-val">
                <button type="button" class="searchable-trigger w-full flex items-center justify-between text-left bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-2 focus:ring-secondary focus:border-secondary p-2.5 transition-all shadow-xs cursor-pointer" aria-haspopup="listbox" aria-expanded="false">
                    <span class="searchable-label truncate font-medium">
                        {{ !empty($instansiAsal) ? $instansiAsal : 'Semua Instansi Pendidikan' }}
                    </span>
                    <span class="material-symbols-outlined text-outline text-[20px] transition-transform duration-200 chevron-icon">expand_more</span>
                </button>

                <div class="searchable-menu hidden absolute z-50 mt-1 w-full min-w-[280px] bg-white dark:bg-[#0f243d] border border-outline-variant/60 dark:border-slate-700 rounded-xl shadow-2xl overflow-hidden">
                    <div class="p-2 border-b border-outline-variant/20 dark:border-slate-700/60 bg-surface-container-low/50 dark:bg-slate-900/40">
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-2.5 top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                            <input type="text" class="searchable-filter-input w-full bg-white dark:bg-[#152d4b] border border-outline-variant/50 dark:border-slate-600 rounded-lg pl-8 pr-3 py-1.5 text-xs text-on-surface placeholder-outline-variant focus:outline-none focus:ring-1 focus:ring-secondary" placeholder="Ketik untuk mencari instansi..." autocomplete="off">
                        </div>
                    </div>
                    <ul class="searchable-list max-h-56 overflow-y-auto py-1 text-xs text-on-surface" role="listbox">
                        <li class="searchable-item px-3 py-2 cursor-pointer hover:bg-secondary/10 dark:hover:bg-blue-900/40 flex items-center justify-between transition-colors font-semibold text-primary {{ empty($instansiAsal) ? 'bg-secondary/10' : '' }}" data-value="" data-label="Semua Instansi Pendidikan">
                            <span>Semua Instansi Pendidikan</span>
                            @if(empty($instansiAsal))
                                <span class="material-symbols-outlined text-secondary text-[16px]">check</span>
                            @endif
                        </li>
                        @foreach($semuaInstansi as $inst)
                        <li class="searchable-item px-3 py-2 cursor-pointer hover:bg-secondary/10 dark:hover:bg-blue-900/40 flex items-center justify-between transition-colors {{ $instansiAsal === $inst ? 'bg-secondary/10 font-bold text-secondary' : '' }}" data-value="{{ $inst }}" data-label="{{ $inst }}">
                            <span class="truncate" title="{{ $inst }}">{{ $inst }}</span>
                            @if($instansiAsal === $inst)
                                <span class="material-symbols-outlined text-secondary text-[16px]">check</span>
                            @endif
                        </li>
                        @endforeach
                        <li class="searchable-no-match hidden px-3 py-4 text-center text-outline text-xs">
                            Tidak ada instansi yang cocok
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Row 2: Filter Periode Tahun & Bulan + Summary & Reset Button -->
        <div class="md:col-span-12 border-t border-outline-variant/20 pt-3 mt-1 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 flex-wrap">
                <!-- Filter Tahun -->
                <div class="flex items-center gap-2">
                    <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1.5 whitespace-nowrap" for="filter-year">
                        <span class="material-symbols-outlined text-[18px] text-amber-500">calendar_today</span>
                        <span>Tahun:</span>
                    </label>
                    <div class="relative w-full sm:w-36">
                        <select name="year" id="filter-year" onchange="this.form.submit()" class="bg-surface border border-outline-variant text-on-surface text-xs font-semibold rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-secondary focus:border-secondary block w-full appearance-none [background-image:none] cursor-pointer shadow-xs">
                            <option value="all" {{ ($year === 'all' || empty($year)) ? 'selected' : '' }}>Semua Tahun</option>
                            @foreach($availableYears as $yr)
                                <option value="{{ $yr }}" {{ (string)$year === (string)$yr ? 'selected' : '' }}>{{ $yr }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[18px]">expand_more</span>
                    </div>
                </div>

                <!-- Filter Bulan -->
                <div class="flex items-center gap-2">
                    <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center gap-1.5 whitespace-nowrap" for="filter-month">
                        <span class="material-symbols-outlined text-[18px] text-amber-500">event_repeat</span>
                        <span>Bulan:</span>
                    </label>
                    <div class="relative w-full sm:w-44">
                        <select name="month" id="filter-month" onchange="this.form.submit()" class="bg-surface border border-outline-variant text-on-surface text-xs font-semibold rounded-lg pl-3 pr-8 py-2 focus:ring-2 focus:ring-secondary focus:border-secondary block w-full appearance-none [background-image:none] cursor-pointer shadow-xs">
                            <option value="all" {{ ($month === 'all' || empty($month)) ? 'selected' : '' }}>Semua Bulan</option>
                            @php
                                $months = [
                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                ];
                            @endphp
                            @foreach($months as $num => $name)
                                <option value="{{ $num }}" {{ (string)$month === (string)$num ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-2.5 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-[18px]">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- Active Filter Badges & Reset Button -->
            @php
                $hasActiveFilter = !empty($search) || !empty($dinasId) || !empty($instansiAsal) || ($year && $year !== 'all') || ($month && $month !== 'all');
            @endphp
            <div class="flex items-center gap-3 shrink-0 flex-wrap">
                @if($hasActiveFilter)
                    <a href="{{ route('landing.peserta') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/60 rounded-lg border border-red-200 dark:border-red-900/60 transition-colors shadow-xs">
                        <span class="material-symbols-outlined text-[16px]">filter_alt_off</span>
                        <span>Reset Filter</span>
                    </a>
                @endif
                <span class="text-xs text-on-surface-variant font-medium">
                    Total: <strong class="text-on-surface font-bold">{{ $pesertas->total() }}</strong> Peserta Diterima
                </span>
            </div>
        </div>

    </div>
</div>
</form>

<!-- Data Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] overflow-hidden border border-outline-variant/30">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse min-w-[1000px]">
<thead class="bg-surface-container-low text-on-surface-variant font-label-md text-xs uppercase tracking-wider border-b border-outline-variant/30">
<tr>
<th class="px-6 py-4 whitespace-nowrap min-w-[200px]" scope="col">Nama Peserta</th>
<th class="px-6 py-4 whitespace-nowrap min-w-[220px]" scope="col">Asal Lembaga & Jurusan</th>
<th class="px-6 py-4 whitespace-nowrap min-w-[260px]" scope="col">Tujuan Dinas & Bidang</th>
<th class="px-6 py-4 whitespace-nowrap min-w-[160px]" scope="col">Tanggal Diterima</th>
<th class="px-6 py-4 whitespace-nowrap min-w-[160px]" scope="col">Tanggal Berakhir</th>
<th class="px-6 py-4 text-center whitespace-nowrap min-w-[130px]" scope="col">Status</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md text-on-surface">
@forelse($pesertas as $peserta)
@php
    $rawDinas = $peserta->dinas->name ?? '-';
    $dinasFormatted = $rawDinas !== '-' ? \App\Models\Dinas::formatTitleCaseKbbi($rawDinas) : '-';

    $rawBidang = $peserta->bidang->nama ?? ($peserta->rekrutmen?->bidang?->name ?? '-');
    if ($rawBidang !== '-') {
        $teksBidang = preg_match('/^bidang\b/i', trim($rawBidang)) ? $rawBidang : 'Bidang ' . $rawBidang;
        $bidangFormatted = \App\Models\Dinas::formatTitleCaseKbbi($teksBidang);
    } else {
        $bidangFormatted = '-';
    }
@endphp
<tr class="hover:bg-surface-container-low/40 transition-colors">
<td class="px-6 py-4 align-middle whitespace-nowrap">
    <div class="flex flex-col">
        <span class="font-semibold text-sm text-on-surface leading-tight">{{ $peserta->user->name }}</span>
        <span class="text-[11px] text-on-surface-variant mt-0.5">Peserta Magang</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-normal min-w-[200px]">
    <div class="flex flex-col">
        <span class="font-semibold text-sm text-on-surface leading-tight">{{ $peserta->instansi_asal }}</span>
        <span class="text-xs text-on-surface-variant mt-0.5 font-normal leading-snug">{{ $peserta->jurusan }}</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-normal min-w-[240px]">
    <div class="flex flex-col">
        <span class="font-semibold text-sm text-primary leading-tight">{{ $dinasFormatted }}</span>
        @if($bidangFormatted !== '-')
        <div class="mt-1">
            <span class="inline-block px-2.5 py-0.5 rounded-md bg-surface-container-high/80 text-on-surface-variant text-[11px] font-medium border border-outline-variant/30">
                {{ $bidangFormatted }}
            </span>
        </div>
        @endif
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap">
    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-surface-container border border-outline-variant/30 text-xs font-semibold text-on-surface">
        <span>{{ $peserta->tanggal_mulai ? $peserta->tanggal_mulai->format('d M Y') : '-' }}</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap">
    <div class="inline-flex items-center px-3 py-1.5 rounded-lg bg-surface-container border border-outline-variant/30 text-xs font-semibold text-on-surface">
        <span>{{ $peserta->tanggal_selesai ? $peserta->tanggal_selesai->format('d M Y') : '-' }}</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap text-center">
    @php
        $isSelesai = strtolower((string)($peserta->status ?? '')) === 'selesai' || ($peserta->tanggal_selesai && $peserta->tanggal_selesai->isPast() && !$peserta->tanggal_selesai->isToday());
    @endphp
    @if($isSelesai)
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2] font-bold text-xs uppercase tracking-wide">
            <span class="w-1.5 h-1.5 rounded-full bg-[#C62828]"></span>
            Selesai
        </span>
    @else
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] font-bold text-xs uppercase tracking-wide">
            <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span>
            Aktif
        </span>
    @endif
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
    <div class="flex flex-col items-center justify-center gap-2">
        <span class="material-symbols-outlined text-[48px] text-outline-variant">search_off</span>
        <p class="font-medium text-sm">Tidak ada peserta yang cocok dengan kriteria pencarian.</p>
    </div>
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 border-t border-outline-variant/30 bg-surface-container-low/30 flex justify-center">
    {{ $pesertas->links() }}
</div>
</div>
</main>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.searchable-dropdown');

    dropdowns.forEach(dropdown => {
        const trigger = dropdown.querySelector('.searchable-trigger');
        const menu = dropdown.querySelector('.searchable-menu');
        const searchInput = dropdown.querySelector('.searchable-filter-input');
        const options = dropdown.querySelectorAll('.searchable-item');
        const noMatch = dropdown.querySelector('.searchable-no-match');
        const hiddenInput = dropdown.querySelector('.searchable-input-val');
        const chevron = trigger ? trigger.querySelector('.chevron-icon') : null;
        const form = dropdown.closest('form');

        if (!trigger || !menu) return;

        // Toggle dropdown open/close
        trigger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');

            // Close other dropdowns
            dropdowns.forEach(other => {
                if (other !== dropdown) {
                    other.querySelector('.searchable-menu')?.classList.add('hidden');
                    const otherChevron = other.querySelector('.chevron-icon');
                    if (otherChevron) otherChevron.style.transform = '';
                }
            });

            if (isOpen) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = '';
            } else {
                menu.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
                if (searchInput) {
                    searchInput.value = '';
                    filterOptions('');
                    setTimeout(() => searchInput.focus(), 60);
                }
            }
        });

        // Filter options based on input text
        function filterOptions(query) {
            const cleanQuery = query.toLowerCase().trim();
            let matchCount = 0;

            options.forEach(opt => {
                const label = (opt.getAttribute('data-label') || '').toLowerCase();
                if (!cleanQuery || label.includes(cleanQuery)) {
                    opt.classList.remove('hidden');
                    matchCount++;
                } else {
                    opt.classList.add('hidden');
                }
            });

            if (noMatch) {
                if (matchCount === 0) {
                    noMatch.classList.remove('hidden');
                } else {
                    noMatch.classList.add('hidden');
                }
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                filterOptions(this.value);
            });
            searchInput.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }

        // Option select
        options.forEach(opt => {
            opt.addEventListener('click', function(e) {
                e.stopPropagation();
                const val = this.getAttribute('data-value');
                if (hiddenInput) hiddenInput.value = val;
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = '';
                if (form) form.submit();
            });
        });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        dropdowns.forEach(dropdown => {
            const menu = dropdown.querySelector('.searchable-menu');
            const chevron = dropdown.querySelector('.chevron-icon');
            if (menu && !menu.classList.contains('hidden') && !dropdown.contains(e.target)) {
                menu.classList.add('hidden');
                if (chevron) chevron.style.transform = '';
            }
        });
    });

    // Close on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            dropdowns.forEach(dropdown => {
                dropdown.querySelector('.searchable-menu')?.classList.add('hidden');
                const chevron = dropdown.querySelector('.chevron-icon');
                if (chevron) chevron.style.transform = '';
            });
        }
    });
});
</script>
@endsection


