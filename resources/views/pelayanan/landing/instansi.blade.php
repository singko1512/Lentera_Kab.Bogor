@extends('pelayanan.layouts.app')

@section('title', 'Instansi Tujuan Magang - LENTERA')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
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
                      "stack-lg": "2rem",
                      "gutter": "1.5rem",
                      "container-max": "1280px",
                      "stack-md": "1rem",
                      "margin-mobile": "1rem",
                      "stack-sm": "0.5rem",
                      "margin-desktop": "2.5rem"
              },
              "fontFamily": {
                      "headline-md": [
                              "Plus Jakarta Sans"
                      ],
                      "display-lg": [
                              "Plus Jakarta Sans"
                      ],
                      "label-md": [
                              "Plus Jakarta Sans"
                      ],
                      "headline-lg": [
                              "Plus Jakarta Sans"
                      ],
                      "caption": [
                              "Plus Jakarta Sans"
                      ],
                      "title-lg": [
                              "Plus Jakarta Sans"
                      ],
                      "body-lg": [
                              "Plus Jakarta Sans"
                      ],
                      "body-md": [
                              "Plus Jakarta Sans"
                      ],
                      "headline-lg-mobile": [
                              "Plus Jakarta Sans"
                      ]
              },
              "fontSize": {
                      "headline-md": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "fontWeight": "600"
                              }
                      ],
                      "display-lg": [
                              "48px",
                              {
                                      "lineHeight": "60px",
                                      "letterSpacing": "-0.02em",
                                      "fontWeight": "700"
                              }
                      ],
                      "label-md": [
                              "14px",
                              {
                                      "lineHeight": "20px",
                                      "fontWeight": "500"
                              }
                      ],
                      "headline-lg": [
                              "32px",
                              {
                                      "lineHeight": "40px",
                                      "fontWeight": "700"
                              }
                      ],
                      "caption": [
                              "12px",
                              {
                                      "lineHeight": "16px",
                                      "fontWeight": "400"
                              }
                      ],
                      "title-lg": [
                              "20px",
                              {
                                      "lineHeight": "28px",
                                      "fontWeight": "600"
                              }
                      ],
                      "body-lg": [
                              "18px",
                              {
                                      "lineHeight": "28px",
                                      "fontWeight": "400"
                              }
                      ],
                      "body-md": [
                              "16px",
                              {
                                      "lineHeight": "24px",
                                      "fontWeight": "400"
                              }
                      ],
                      "headline-lg-mobile": [
                              "24px",
                              {
                                      "lineHeight": "32px",
                                      "fontWeight": "700"
                              }
                      ]
              }
      },
          },
        }
      </script>

@endsection

@section('content')


<!-- Main Content -->
<main class="flex-grow w-full max-w-container-max mx-auto px-margin-desktop py-stack-lg">
<!-- Breadcrumb -->
<nav aria-label="Breadcrumb" class="flex items-center gap-2 mb-stack-lg text-on-surface-variant font-label-md text-label-md">
<a class="hover:text-primary transition-colors" href="{{ route('home') }}">Beranda</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-on-surface font-medium">Instansi Tujuan Magang</span>
</nav>
<!-- Page Header -->
<header class="mb-stack-lg">
<h1 class="font-display-lg text-display-lg text-on-surface mb-stack-sm md:font-headline-lg-mobile md:text-headline-lg-mobile lg:font-display-lg lg:text-display-lg">Instansi Tujuan Magang</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl">Pilih instansi dan bidang magang yang sesuai dengan minat serta ketersediaan kuota.</p>
</header>
<!-- Search and Filter Bar -->
<section class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 mb-stack-lg border border-outline-variant/30">
    <form action="{{ route('landing.instansi') }}" method="GET" class="flex flex-col gap-4">
        <!-- Preserve current filter if search or sort is submitted -->
        <input type="hidden" name="filter" value="{{ request('filter', 'semua') }}">
        
        <!-- Top Row: Search and Sort -->
        <div class="flex flex-col md:flex-row gap-4 w-full justify-between items-stretch md:items-center">
            <!-- Search -->
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
                <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-lg text-body-md font-body-md focus:border-secondary focus:ring-1 focus:ring-secondary focus:outline-none transition-all placeholder:text-outline" placeholder="Cari nama instansi..." type="text">
            </div>
            <!-- Sorting -->
            <div class="relative group">
                <select name="sort" onchange="this.form.submit()" class="w-full md:w-64 appearance-none bg-surface-container-lowest border border-outline-variant rounded-lg py-3 pl-4 pr-10 text-body-md font-body-md text-on-surface focus:border-secondary focus:ring-1 focus:ring-secondary focus:outline-none cursor-pointer" style="background-image: none !important;">
                    <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama Instansi A-Z</option>
                    <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Urutkan: Nama Instansi Z-A</option>
                    <option value="kuota_terbanyak" {{ request('sort') == 'kuota_terbanyak' ? 'selected' : '' }}>Urutkan: Kuota Terbanyak</option>
                </select>
                <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
            </div>
            <button type="submit" class="hidden">Search</button>
        </div>
    </form>
    
    <!-- Bottom Row: Filter Buttons -->
    <div class="flex flex-wrap gap-2 items-center w-full pt-4 mt-4 border-t border-outline-variant/20">
        @php
            $currentFilter = request('filter', 'semua');
            $activeClass = 'bg-primary-container text-on-primary border-primary-container shadow-sm';
            $inactiveClass = 'bg-surface-container-lowest text-on-surface-variant border-outline-variant hover:bg-surface-container-low';
        @endphp
        
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'semua']) }}" class="px-4 py-2 rounded-full font-label-md text-label-md border transition-colors {{ $currentFilter == 'semua' ? $activeClass : $inactiveClass }}">
            Semua Instansi
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'tersedia']) }}" class="px-4 py-2 rounded-full font-label-md text-label-md border transition-colors {{ $currentFilter == 'tersedia' ? $activeClass : $inactiveClass }}">
            Kuota Tersedia
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'penuh']) }}" class="px-4 py-2 rounded-full font-label-md text-label-md border transition-colors {{ $currentFilter == 'penuh' ? $activeClass : $inactiveClass }}">
            Kuota Penuh
        </a>
        <a href="{{ request()->fullUrlWithQuery(['filter' => 'tidak_tersedia']) }}" class="px-4 py-2 rounded-full font-label-md text-label-md border transition-colors {{ $currentFilter == 'tidak_tersedia' ? $activeClass : $inactiveClass }}">
            Tidak Tersedia
        </a>
    </div>
</section>
<!-- Directory Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
@foreach($instansis as $instansi)
<a class="group block bg-surface-container-lowest rounded-xl p-6 shadow-ambient hover:shadow-hover hover:-translate-y-1 border border-surface-container-high hover:border-primary transition-all duration-300 relative overflow-hidden flex flex-col h-full" href="{{ route('landing.instansi_detail', $instansi->id) }}">
<div class="flex justify-between items-start mb-4">
  <div class="w-12 h-12 bg-surface-container flex items-center justify-center rounded-lg border border-outline-variant/20">
    <span class="material-symbols-outlined text-[28px] text-primary group-hover:scale-110 transition-transform">
        @if(str_contains(strtolower($instansi->name), 'pendidikan') || str_contains(strtolower($instansi->name), 'sekolah')) school
        @elseif(str_contains(strtolower($instansi->name), 'kesehatan') || str_contains(strtolower($instansi->name), 'rsud') || str_contains(strtolower($instansi->name), 'puskesmas')) medical_services
        @elseif(str_contains(strtolower($instansi->name), 'komputer') || str_contains(strtolower($instansi->name), 'informasi') || str_contains(strtolower($instansi->name), 'komunikasi')) computer
        @elseif(str_contains(strtolower($instansi->name), 'sosial')) people
        @else domain @endif
    </span>
  </div>
  <div class="font-caption text-caption text-secondary uppercase tracking-wider bg-secondary/10 px-3 py-1 rounded font-bold text-center">
      {{ str_contains(strtolower($instansi->name), 'kecamatan') ? 'KECAMATAN' : 'INSTANSI PERANGKAT DAERAH' }}
  </div>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface group-hover:text-primary mb-2 transition-colors line-clamp-2">{{ $instansi->name }}</h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-6 flex-grow line-clamp-3">
    {{ $instansi->deskripsi ?? 'Fasilitas pelayanan, riset, dan magang di ' . $instansi->name . '.' }}
</p>
<div class="mt-auto flex items-center justify-between pt-4 border-t border-surface-container-high">
  @php $badge = $instansi->status_badge; @endphp
  <div class="flex items-center gap-1 px-3 py-1 {{ $badge['bg_class'] }} rounded-md font-label-md text-caption font-bold">
    <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
    <span>{{ $badge['label'] }}</span>
  </div>
  <div class="py-2 px-4 bg-surface-container-low text-primary font-label-md text-label-md rounded-md hover:bg-primary hover:text-white transition-colors text-center inline-block">Lihat Detail</div>
</div>
</a>
@endforeach
</div>
<div class="mt-8 flex justify-center w-full">
    {{ $instansis->links('pagination::tailwind') }}
</div>
</main>
<!-- Footer -->





@endsection






