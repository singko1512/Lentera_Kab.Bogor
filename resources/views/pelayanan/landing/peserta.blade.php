@extends('pelayanan.layouts.app')

@section('title', 'Daftar Peserta Magang - LENTERA')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex items-center gap-2 mb-stack-lg text-on-surface-variant font-label-md text-label-md">
<a class="hover:text-primary transition-colors" href="{{ route('home') }}">Beranda</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-on-surface font-medium">Peserta Magang Diterima</span>
</nav>

<!-- Header Section -->
<div class="mb-stack-lg">
<h1 class="font-headline-lg text-headline-lg md:font-display-lg md:text-display-lg text-on-surface mb-stack-sm">Peserta Magang Diterima</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl">Daftar peserta yang telah resmi diterima dalam program magang di lingkungan Pemerintah Kabupaten Bogor.</p>
</div>
<!-- Filters & Search -->
<form method="GET" action="{{ route('landing.peserta') }}">
<div class="bg-surface-container-lowest p-gutter rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border-t-4 border-primary mb-stack-lg">
<div class="grid grid-cols-1 md:grid-cols-12 gap-stack-md items-end">
<div class="md:col-span-5 flex flex-col gap-stack-sm">
<label class="font-label-md text-label-md text-on-surface" for="search">Cari Peserta</label>
<div class="relative">
<div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
<span class="material-symbols-outlined text-outline">search</span>
</div>
<input name="search" value="{{ $search }}" class="bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full ps-10 p-2.5" id="search" placeholder="Cari nama peserta, institusi, jurusan..." type="text"/>
</div>
</div>
<div class="md:col-span-3 flex flex-col gap-stack-sm">
<label class="font-label-md text-label-md text-on-surface" for="dinas">Tujuan Dinas</label>
<select name="dinas_id" onchange="this.form.submit()" class="bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5" id="dinas">
<option value="">Semua Dinas</option>
@foreach($semuaDinas as $dn)
<option value="{{ $dn->id }}" {{ $dinasId == $dn->id ? 'selected' : '' }}>{{ \App\Models\Dinas::formatTitleCaseKbbi($dn->name) }}</option>
@endforeach
</select>
</div>
<div class="md:col-span-4 flex flex-col gap-stack-sm">
<label class="font-label-md text-label-md text-on-surface" for="instansi">Instansi Pendidikan</label>
<select name="instansi_asal" onchange="this.form.submit()" class="bg-surface border border-outline-variant text-on-surface text-sm rounded-lg focus:ring-secondary focus:border-secondary block w-full p-2.5" id="instansi">
<option value="">Semua Instansi Pendidikan</option>
@foreach($semuaInstansi as $inst)
<option value="{{ $inst }}" {{ $instansiAsal == $inst ? 'selected' : '' }}>{{ $inst }}</option>
@endforeach
</select>
</div>
<button type="submit" class="hidden">Filter</button>
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
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold text-xs flex items-center justify-center shrink-0 border border-primary/20">
            {{ strtoupper(substr($peserta->user->name ?? 'P', 0, 2)) }}
        </div>
        <div class="flex flex-col">
            <span class="font-semibold text-sm text-on-surface leading-tight">{{ $peserta->user->name }}</span>
            <span class="text-[11px] text-on-surface-variant mt-0.5">Peserta Magang</span>
        </div>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-normal min-w-[220px]">
    <div class="flex flex-col">
        <div class="flex items-center gap-1.5 font-semibold text-sm text-on-surface">
            <span class="material-symbols-outlined text-[16px] text-secondary shrink-0">school</span>
            <span>{{ $peserta->instansi_asal }}</span>
        </div>
        <div class="text-xs text-on-surface-variant mt-1 pl-5 font-normal leading-snug">
            {{ $peserta->jurusan }}
        </div>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-normal min-w-[260px]">
    <div class="flex flex-col">
        <div class="flex items-center gap-1.5 font-semibold text-sm text-primary">
            <span class="material-symbols-outlined text-[16px] text-primary shrink-0">account_balance</span>
            <span class="leading-tight">{{ $dinasFormatted }}</span>
        </div>
        @if($bidangFormatted !== '-')
        <div class="mt-1 pl-5">
            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-surface-container-high/80 text-on-surface-variant text-[11px] font-medium border border-outline-variant/30">
                <span class="material-symbols-outlined text-[13px] text-secondary">domain_verification</span>
                {{ $bidangFormatted }}
            </span>
        </div>
        @endif
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap">
    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container border border-outline-variant/30 text-xs font-semibold text-on-surface">
        <span class="material-symbols-outlined text-[15px] text-primary">calendar_month</span>
        <span>{{ $peserta->tanggal_mulai ? $peserta->tanggal_mulai->format('d M Y') : '-' }}</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap">
    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-surface-container border border-outline-variant/30 text-xs font-semibold text-on-surface">
        <span class="material-symbols-outlined text-[15px] text-on-surface-variant">event_available</span>
        <span>{{ $peserta->tanggal_selesai ? $peserta->tanggal_selesai->format('d M Y') : '-' }}</span>
    </div>
</td>
<td class="px-6 py-4 align-middle whitespace-nowrap text-center">
    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] font-bold text-xs uppercase tracking-wide">
        <span class="w-1.5 h-1.5 rounded-full bg-[#2E7D32]"></span>
        {{ $peserta->status->nama ?? 'Diterima' }}
    </span>
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


