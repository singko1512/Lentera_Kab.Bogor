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
<option value="{{ $dn->id }}" {{ $dinasId == $dn->id ? 'selected' : '' }}>{{ $dn->name }}</option>
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
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left font-body-md text-body-md text-on-surface whitespace-nowrap">
<thead class="font-label-md text-label-md uppercase bg-surface-container-low border-b border-surface-container-high text-on-surface-variant">
<tr>
<th class="px-6 py-4" scope="col">Nama Peserta</th>
<th class="px-6 py-4" scope="col">Asal Instansi & Jurusan</th>
<th class="px-6 py-4" scope="col">Tujuan Dinas & Bidang</th>
<th class="px-6 py-4" scope="col">Tanggal Diterima</th>
<th class="px-6 py-4" scope="col">Tanggal Berakhir</th>
<th class="px-6 py-4" scope="col">Status</th>
</tr>
</thead>
<tbody>
@forelse($pesertas as $peserta)
<tr class="border-b border-surface-container-high hover:bg-surface-container-low transition-colors">
<td class="px-6 py-4 font-medium text-on-surface">
    {{ $peserta->user->name }}
</td>
<td class="px-6 py-4 whitespace-normal min-w-[200px]">
    <div class="text-on-surface">{{ $peserta->instansi_asal }}</div>
    <div class="text-on-surface-variant text-sm mt-1">{{ $peserta->jurusan }}</div>
</td>
<td class="px-6 py-4 whitespace-normal min-w-[200px]">
    <div class="text-on-surface">{{ $peserta->dinas->name ?? '-' }}</div>
    <div class="text-on-surface-variant text-sm mt-1">{{ $peserta->bidang->nama ?? '-' }}</div>
</td>
<td class="px-6 py-4">{{ $peserta->tanggal_mulai ? $peserta->tanggal_mulai->format('d M Y') : '-' }}</td>
<td class="px-6 py-4">{{ $peserta->tanggal_selesai ? $peserta->tanggal_selesai->format('d M Y') : '-' }}</td>
<td class="px-6 py-4">
<span class="bg-[#DCFCE7] text-[#15803d] dark:bg-emerald-950/40 dark:text-emerald-400 font-label-md text-caption uppercase px-2 py-1 rounded">{{ $peserta->status->nama ?? 'DITERIMA' }}</span>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">
    <div class="flex flex-col items-center justify-center">
        <span class="material-symbols-outlined text-[48px] mb-2 text-outline">search_off</span>
        <p>Tidak ada peserta yang cocok dengan kriteria pencarian.</p>
    </div>
</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 border-t border-surface-container-high">
{{ $pesertas->links() }}
</div>
</div>
</main>




@endsection


