@extends('pelayanan.layouts.app')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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
                        "container-max": "1280px",
                        "stack-sm": "0.5rem",
                        "gutter": "1.5rem",
                        "margin-desktop": "2.5rem",
                        "stack-md": "1rem",
                        "stack-lg": "2rem",
                        "margin-mobile": "1rem"
                    },
                    "fontFamily": {
                        "body-lg": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "caption": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "title-lg": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "700" }],
                        "caption": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
                        "display-lg": ["48px", { "lineHeight": "60px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "title-lg": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "500" }],
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom Scroll Animation Styles */
        .reveal-element {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s cubic-bezier(0.215, 0.610, 0.355, 1), transform 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
        }
        
        .reveal-element.active {
            opacity: 1;
            transform: translateY(0);
        }

        .reveal-element-left {
            opacity: 0;
            transform: translateX(-40px);
            transition: opacity 0.8s cubic-bezier(0.215, 0.610, 0.355, 1), transform 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
        }
        
        .reveal-element-left.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-element-right {
            opacity: 0;
            transform: translateX(40px);
            transition: opacity 0.8s cubic-bezier(0.215, 0.610, 0.355, 1), transform 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
        }
        
        .reveal-element-right.active {
            opacity: 1;
            transform: translateX(0);
        }

        .reveal-scale {
            opacity: 0;
            transform: scale(0.95);
            transition: opacity 0.8s cubic-bezier(0.215, 0.610, 0.355, 1), transform 0.8s cubic-bezier(0.215, 0.610, 0.355, 1);
        }

        .reveal-scale.active {
            opacity: 1;
            transform: scale(1);
        }

        /* Staggered children transitions */
        .stagger-container {
            display: contents;
        }

        /* Hover animations for cards */
        .hover-card-trigger {
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        }
        
        .hover-card-trigger:hover {
            transform: translateY(-6px) scale(1.02) !important;
            box-shadow: 0 20px 30px rgba(0, 30, 64, 0.08) !important;
            border-color: #115cb9 !important;
        }

        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        /* Hero Action Buttons Advanced Dynamic Styling & Keyframe Animations */
        @keyframes buttonShimmerContinuous {
            0% { transform: translateX(-160%) skewX(-25deg); }
            45%, 100% { transform: translateX(220%) skewX(-25deg); }
        }

        @keyframes heroGlowPulse {
            0%, 100% {
                box-shadow: 0 10px 25px -5px rgba(29, 78, 216, 0.5), 0 0 18px rgba(59, 130, 246, 0.35), inset 0 1px 1px rgba(255, 255, 255, 0.5);
            }
            50% {
                box-shadow: 0 16px 35px -4px rgba(29, 78, 216, 0.75), 0 0 30px rgba(59, 130, 246, 0.6), inset 0 1px 1px rgba(255, 255, 255, 0.75);
            }
        }

        @keyframes iconFloatGentle {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-3px) rotate(-6deg); }
        }

        @keyframes arrowBounceDown {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(4px); }
        }

        @keyframes arrowSlideRight {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(5px); }
        }

        .hero-btn-primary {
            background: linear-gradient(135deg, #0d47a1 0%, #115cb9 50%, #2563eb 100%);
            background-size: 200% 200%;
            animation: heroGlowPulse 3s ease-in-out infinite;
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        .hero-btn-primary .hero-btn-icon-main {
            animation: iconFloatGentle 3s ease-in-out infinite;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hero-btn-primary .hero-btn-icon-arrow {
            animation: arrowBounceDown 2s ease-in-out infinite;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hero-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent);
            animation: buttonShimmerContinuous 3.8s infinite cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .hero-btn-primary:hover {
            transform: translateY(-5px) scale(1.05) !important;
            box-shadow: 0 20px 42px -6px rgba(29, 78, 216, 0.8), 0 0 35px rgba(59, 130, 246, 0.75), inset 0 1px 2px rgba(255, 255, 255, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
        }

        .hero-btn-primary:hover .hero-btn-icon-main {
            transform: scale(1.25) rotate(-15deg) !important;
        }

        .hero-btn-primary:hover .hero-btn-icon-arrow {
            transform: translateY(6px) scale(1.2) !important;
        }

        .hero-btn-primary:active {
            transform: translateY(-2px) scale(0.96) !important;
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2), inset 0 1px 1px rgba(255, 255, 255, 0.35);
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        .hero-btn-secondary .hero-btn-icon-main {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hero-btn-secondary .hero-btn-icon-arrow {
            animation: arrowSlideRight 2s ease-in-out infinite;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .hero-btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: buttonShimmerContinuous 3.8s 1.9s infinite cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25) !important;
            transform: translateY(-5px) scale(1.05) !important;
            border-color: rgba(255, 255, 255, 0.85) !important;
            box-shadow: 0 18px 38px rgba(0, 0, 0, 0.35), 0 0 28px rgba(255, 255, 255, 0.4), inset 0 1px 2px rgba(255, 255, 255, 0.7) !important;
        }

        .hero-btn-secondary:hover .hero-btn-icon-main {
            transform: scale(1.25) rotate(12deg) !important;
        }

        .hero-btn-secondary:hover .hero-btn-icon-arrow {
            transform: translateX(8px) scale(1.2) !important;
        }

        .hero-btn-secondary:active {
            transform: translateY(-2px) scale(0.96) !important;
        }

        /* Dropdown Tambah Layanan Smooth Animation */
        .dropdown-menu-custom {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px) scale(0.96);
            transition: opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.2s;
            pointer-events: none;
        }
        .dropdown-menu-custom.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }
        .dropdown-arrow-rotate {
            transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            display: inline-block;
        }
        .dropdown-arrow-rotate.open {
            transform: rotate(180deg);
        }
    </style>
@endsection

@section('content')


<!-- Main Content Canvas -->
<main class="flex-grow flex flex-col">
@guest
<!-- Hero Section (Hanya untuk Pengunjung / Belum Login) -->
<section class="relative w-full min-h-[600px] flex items-center justify-center overflow-hidden">
<div class="absolute inset-0 z-0">
<img alt="Tugu Pancakarsa Kabupaten Bogor" class="w-full h-full object-cover" src="{{ asset('assets/tugu_pancakarsa.jpg') }}"/>
<div class="absolute inset-0 bg-[#001e40] opacity-80"></div>
</div>
<div class="relative z-10 max-w-4xl mx-auto px-margin-desktop py-24 flex flex-col items-center text-center gap-6 stagger-container">
<h1 class="font-display-lg text-display-lg text-white reveal-element">Mulai Riset/Magang Anda <br> Bersama Kabupaten Bogor</h1>
<p class="font-body-lg text-body-lg text-white/90 hover:text-white transition-colors duration-300 cursor-default max-w-3xl reveal-element">
                        Sistem informasi terintegrasi untuk pendaftaran, pengelolaan, dan pelaksanaan <br>program magang di lingkungan Pemerintah Kabupaten Bogor.
                    </p>

<!-- Hero Action Buttons -->
<div class="flex flex-wrap items-center justify-center gap-4 mt-3 reveal-element">
    <!-- Button 1: Rekap Statistik -->
    <a href="#analytics-section" class="hero-btn-primary group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-white font-semibold text-base relative overflow-hidden shadow-lg">
        <span class="material-symbols-outlined text-[22px] hero-btn-icon-main">monitoring</span>
        <span class="tracking-wide">Rekap Statistik</span>
        <span class="material-symbols-outlined text-[18px] hero-btn-icon-arrow">arrow_downward</span>
    </a>

    <!-- Button 2: Peserta Magang -->
    <a href="{{ route('landing.peserta') }}" class="hero-btn-secondary group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-white font-semibold text-base relative overflow-hidden">
        <span class="material-symbols-outlined text-[22px] hero-btn-icon-main">groups</span>
        <span class="tracking-wide">Peserta Magang</span>
        <span class="material-symbols-outlined text-[18px] hero-btn-icon-arrow">arrow_forward</span>
    </a>
</div>

</div>
</section>
@endguest

@auth
<!-- Section Table Status Permohonan User Logged In -->
<section id="user-applications-section" class="max-w-container-max mx-auto px-margin-desktop pt-10 pb-6 w-full">
    <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-ambient border border-outline-variant/30">
        <!-- Header Table & Dropdown Tambah Layanan -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-6 mb-6 border-b border-outline-variant/20">
            <div>
                <h2 class="font-headline-lg text-headline-lg text-on-surface flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-primary text-[32px]">assignment</span>
                    Status Permohonan Saya
                </h2>
                <p class="font-body-md text-body-md text-on-surface-variant mt-1">
                    Pantau progres permohonan surat izin rekomendasi dan magang Anda secara real-time.
                </p>
            </div>

            <!-- Dropdown Button Tambah Layanan -->
            <div class="relative inline-block text-left shrink-0">
                @if(in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
                <button disabled type="button" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-outline-variant text-on-surface-variant font-label-md text-label-md font-semibold rounded-xl cursor-not-allowed shadow-md" title="Akun Anda {{ ucwords(auth()->user()->status_akun) }}">
                    <span class="material-symbols-outlined text-[20px]">block</span>
                    <span>Akun {{ ucwords(auth()->user()->status_akun) }}</span>
                </button>
                @else
                <button id="btn-dropdown-layanan" onclick="toggleLayananDropdown(event)" type="button" aria-haspopup="true" aria-expanded="false" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white font-label-md text-label-md font-semibold rounded-xl hover:bg-secondary active:scale-95 transition-all duration-150 shadow-md hover:shadow-lg cursor-pointer select-none focus:outline-none">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Tambah Layanan Baru</span>
                    <span id="arrow-dropdown-layanan" class="material-symbols-outlined text-[18px] dropdown-arrow-rotate">expand_more</span>
                </button>

                <!-- Dropdown Menu List Service Forms -->
                <div id="dropdown-layanan-menu" class="dropdown-menu-custom absolute right-0 top-full mt-2 w-80 rounded-2xl bg-white shadow-2xl border border-outline-variant/30 py-2.5 z-[100]">
                    <div class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider text-outline border-b border-outline-variant/20 mb-1">
                        Pilih Form Jenis Layanan:
                    </div>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('penelitian_pt')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">school</span>
                        <span>Penelitian (Perguruan Tinggi)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('penelitian_instansi')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">business</span>
                        <span>Penelitian (Instansi / Lembaga)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkl_mahasiswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">work</span>
                        <span>KKL / PKL / Magang (Mahasiswa)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkn_mahasiswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                        <span>KKN Mahasiswa</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkl_siswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">backpack</span>
                        <span>KKL / PKL / Magang (Siswa Sekolah)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('pelaksanaan_kegiatan')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">event</span>
                        <span>Pelaksanaan Kegiatan</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('perpanjangan')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary rounded-xl mx-1.5 transition-colors border-t border-outline-variant/20 mt-1 pt-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">update</span>
                        <span>Perpanjangan Izin Rekomendasi</span>
                    </a>
                </div>
                @endif
            </div>
        </div>

        @if(auth()->user()->status_akun != 'diblokir')
        <!-- Alur / Step-by-Step Progress Tracker Permohonan -->
        <div id="user-applications-stepper" class="mb-8 p-5 sm:p-6 bg-gradient-to-br from-surface-container-low/70 via-surface-container-lowest to-surface-container-low/40 dark:from-[#0d223a] dark:via-[#0b1c30] dark:to-[#0f2642] rounded-2xl border border-outline-variant/30 dark:border-slate-800 shadow-xs">
            <!-- Header Alur & Dropdown Switcher -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-5 border-b border-outline-variant/20 dark:border-slate-800">
                <div>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary dark:bg-blue-950/70 dark:text-blue-300 dark:border dark:border-blue-800/60 rounded-full text-xs font-bold uppercase tracking-wider mb-1.5">
                        <span class="material-symbols-outlined text-[16px]">route</span>
                        <span>Alur Proses Layanan</span>
                    </div>
                    <h3 class="font-title-lg text-lg sm:text-xl text-on-surface dark:text-slate-100 font-bold">
                        Tahapan Permohonan Surat Izin & Magang
                    </h3>
                    <p class="font-body-md text-xs sm:text-sm text-on-surface-variant dark:text-slate-400 mt-0.5">
                        Pantau posisi permohonan Anda mulai dari registrasi akun hingga penerbitan surat dan pelaksanaan.
                    </p>
                </div>

                @if(isset($userApplications) && $userApplications->count() > 1)
                <div class="flex items-center gap-2 mt-2 sm:mt-0 shrink-0">
                    <label for="stepper-app-select" class="text-xs font-semibold text-on-surface-variant dark:text-slate-400 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px] text-primary dark:text-blue-400">swap_horiz</span>
                        <span>Pilih Permohonan:</span>
                    </label>
                    <select id="stepper-app-select" onchange="changeStepperApplication(this.value)" class="text-xs font-semibold py-1.5 px-3 pr-8 rounded-xl bg-white dark:bg-slate-800 border border-outline-variant/30 dark:border-slate-700 text-on-surface dark:text-slate-100 focus:ring-2 focus:ring-primary/20 focus:border-primary shadow-xs cursor-pointer">
                        @foreach($userApplications as $idx => $a)
                            <option value="{{ $a->id }}" {{ $idx === 0 ? 'selected' : '' }}>
                                #{{ $a->id }} - {{ Str::limit($a->jenisLayanan->nama ?? $a->jenis_permohonan, 26) }} {{ $idx === 0 ? '(Terbaru)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @elseif(isset($userApplications) && $userApplications->count() === 1)
                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-surface-container-low dark:bg-slate-800/80 rounded-xl border border-outline-variant/20 dark:border-slate-700 text-xs font-semibold text-on-surface dark:text-slate-200 shrink-0 self-start sm:self-center">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span>Permohonan #{{ $userApplications->first()->id }}: {{ Str::limit($userApplications->first()->jenisLayanan->nama ?? $userApplications->first()->jenis_permohonan, 28) }}</span>
                </div>
                @endif
            </div>

            @if(isset($userApplications) && $userApplications->count() > 0)
                {{-- Loop untuk setiap permohonan pemohon --}}
                @foreach($userApplications as $index => $app)
                @php
                    $statusKode = strtolower(optional($app->statusMaster)->kode ?? 'proses');
                    $magangApp = $app->magangApplication;
                    $dinasStatus = $magangApp ? strtolower($magangApp->status ?? 'menunggu') : null;
                    $isMagang = (bool)$magangApp;

                    // Deteksi Jenis Layanan untuk Pelaksanaan Dinamis (PKL, Magang, KKN, Riset, dll.)
                    $serviceSlug = strtolower(optional($app->jenisLayanan)->slug ?? '');
                    $serviceName = strtolower(optional($app->jenisLayanan)->nama ?? ($app->jenis_permohonan ?? ''));

                    if ($serviceSlug === 'kkl_siswa' || str_contains($serviceName, 'siswa') || str_contains($serviceName, 'sekolah')) {
                        $pelaksanaanLabel = 'Pelaksanaan PKL';
                        $pelaksanaanSub = 'PKL di ' . ($app->dinas->name ?? 'Dinas');
                        $pelaksanaanIcon = 'backpack';
                    } elseif ($serviceSlug === 'kkn_mahasiswa' || str_contains($serviceName, 'kkn')) {
                        $pelaksanaanLabel = 'Pelaksanaan KKN';
                        $pelaksanaanSub = 'Kegiatan di Wilayah';
                        $pelaksanaanIcon = 'groups';
                    } elseif ($serviceSlug === 'kkl_mahasiswa' || str_contains($serviceName, 'magang') || str_contains($serviceName, 'kkl') || $isMagang) {
                        $pelaksanaanLabel = 'Pelaksanaan Magang';
                        $pelaksanaanSub = 'Presensi di ' . ($app->dinas->name ?? 'Dinas');
                        $pelaksanaanIcon = 'work';
                    } elseif (str_contains($serviceSlug, 'penelitian') || str_contains($serviceName, 'penelitian') || str_contains($serviceName, 'riset')) {
                        $pelaksanaanLabel = 'Pelaksanaan Riset';
                        $pelaksanaanSub = 'Penelitian & Data';
                        $pelaksanaanIcon = 'science';
                    } elseif (str_contains($serviceSlug, 'perpanjangan') || str_contains($serviceName, 'perpanjangan')) {
                        $pelaksanaanLabel = 'Pelaksanaan Lanjutan';
                        $pelaksanaanSub = 'Perpanjangan Izin';
                        $pelaksanaanIcon = 'update';
                    } else {
                        $pelaksanaanLabel = 'Pelaksanaan Kegiatan';
                        $pelaksanaanSub = 'Aktivitas Kegiatan';
                        $pelaksanaanIcon = 'event';
                    }

                    // Pengecekan apakah kegiatan sudah tuntas selesai
                    $isFinished = ($magangApp && $magangApp->status === 'selesai')
                        || ($statusKode === 'selesai')
                        || ($app->tanggal_selesai && $app->tanggal_selesai->isPast() && ($statusKode === 'disetujui' || in_array($dinasStatus, ['diterima', 'aktif'])));

                    // Step 1: Akun Terdaftar (Selalu selesai untuk pemohon login)
                    $s1_state = 'completed';

                    // Step 2: Pengajuan Berkas (Selesai karena permohonan ini sudah diajukan)
                    $s2_state = 'completed';

                    // Default variables for Card Guidance
                    $cardClass = '';
                    $cardIconBg = '';
                    $cardIcon = '';
                    $cardBadgeClass = '';
                    $cardBadgeText = '';
                    $cardMetaText = '';
                    $cardMetaClass = '';
                    $cardTitle = '';
                    $cardTitleClass = '';
                    $cardDescription = '';
                    $cardDescClass = '';
                    $cardNote = null;
                    $cardActionHtml = '';

                    // Step 3, 4, 5, 6 logic with complete Dark Mode contrast support
                    if ($statusKode === 'perlu_revisi') {
                        $s3_state = 'revision';
                        $s4_state = 'pending';
                        $s5_state = 'pending';
                        $s6_state = 'pending';

                        $cardClass = 'border-amber-300 dark:border-amber-800/60 bg-amber-50/90 dark:bg-amber-950/40';
                        $cardIconBg = 'bg-amber-500 text-white';
                        $cardIcon = 'edit_document';
                        $cardBadgeClass = 'bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-200 dark:border dark:border-amber-700/50';
                        $cardBadgeText = 'Tahap 3: Perbaikan Dokumen';
                        $cardMetaText = 'Perlu Tindakan Anda';
                        $cardMetaClass = 'text-amber-700 dark:text-amber-300/80';
                        $cardTitle = 'Permohonan Memerlukan Revisi Dokumen';
                        $cardTitleClass = 'text-amber-950 dark:text-amber-100';
                        $cardDescription = 'Petugas verifikator Bakesbangpol telah memeriksa permohonan Anda dan meminta perbaikan berkas: <strong>"' . e($app->keterangan) . '"</strong>. Silakan klik tombol perbaiki untuk memperbarui dokumen.';
                        $cardDescClass = 'text-amber-900/90 dark:text-amber-200/90';
                        $cardActionHtml = '<button type="button" onclick="openEditModal(\'' . $app->id . '\')" class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl transition shadow-sm cursor-pointer"><span class="material-symbols-outlined text-[18px]">edit</span><span>Perbaiki Dokumen Sekarang</span></button>';
                    } elseif ($statusKode === 'ditolak') {
                        $s3_state = 'rejected';
                        $s4_state = 'pending';
                        $s5_state = 'pending';
                        $s6_state = 'pending';

                        $cardClass = 'border-red-200 dark:border-red-900/60 bg-red-50/90 dark:bg-red-950/40';
                        $cardIconBg = 'bg-red-500 text-white';
                        $cardIcon = 'cancel';
                        $cardBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-200 dark:border dark:border-red-700/50';
                        $cardBadgeText = 'Tahap 3: Tidak Disetujui';
                        $cardMetaText = 'Permohonan Ditolak';
                        $cardMetaClass = 'text-red-700 dark:text-red-300/80';
                        $cardTitle = 'Permohonan Ditolak oleh Bakesbangpol';
                        $cardTitleClass = 'text-red-950 dark:text-red-100';
                        $cardDescription = 'Mohon maaf, berkas permohonan tidak dapat disetujui. Alasan: <strong>"' . e($app->keterangan ?? 'Dokumen tidak memenuhi persyaratan verifikasi.') . '"</strong>. Anda dapat mengajukan permohonan baru dengan dokumen yang memenuhi syarat.';
                        $cardDescClass = 'text-red-900/90 dark:text-red-200/90';
                        $cardActionHtml = '<button type="button" onclick="document.getElementById(\'btn-dropdown-layanan\').click();" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-secondary dark:bg-blue-600 dark:hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition shadow-sm cursor-pointer"><span class="material-symbols-outlined text-[18px]">add</span><span>Ajukan Permohonan Baru</span></button>';
                    } elseif (in_array($statusKode, ['disetujui', 'selesai'])) {
                        $s3_state = 'completed';

                        if ($isMagang) {
                            if ($dinasStatus === 'ditolak') {
                                $s4_state = 'rejected';
                                $s5_state = 'pending';
                                $s6_state = 'pending';

                                $cardClass = 'border-red-200 dark:border-red-900/60 bg-red-50/90 dark:bg-red-950/40';
                                $cardIconBg = 'bg-red-500 text-white';
                                $cardIcon = 'domain_disabled';
                                $cardBadgeClass = 'bg-red-100 text-red-800 dark:bg-red-900/60 dark:text-red-200 dark:border dark:border-red-700/50';
                                $cardBadgeText = 'Tahap 4: Ditolak oleh SKPD Tujuan';
                                $cardMetaText = 'Kuota Penuh';
                                $cardMetaClass = 'text-red-700 dark:text-red-300/80';
                                $cardTitle = 'Penempatan Belum Dapat Diterima Dinas';
                                $cardTitleClass = 'text-red-950 dark:text-red-100';
                                $cardDescription = 'Rekomendasi Bakesbangpol telah disetujui, namun pihak instansi <strong>' . e($app->dinas->name ?? 'Terkait') . '</strong> belum dapat menerima penempatan saat ini. ' . ($magangApp->catatan_admin ? 'Catatan: <strong>"' . e($magangApp->catatan_admin) . '"</strong>.' : '');
                                $cardDescClass = 'text-red-900/90 dark:text-red-200/90';
                                $cardActionHtml = '<button type="button" onclick="document.getElementById(\'btn-dropdown-layanan\').click();" class="inline-flex items-center gap-1.5 px-4 py-2 bg-primary hover:bg-secondary dark:bg-blue-600 dark:hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition shadow-sm cursor-pointer"><span class="material-symbols-outlined text-[18px]">add</span><span>Ajukan ke Dinas Lain</span></button>';
                            } elseif ($dinasStatus === 'menunggu') {
                                $s4_state = 'active';
                                $s5_state = 'pending';
                                $s6_state = 'pending';

                                $cardClass = 'border-indigo-200 dark:border-indigo-900/60 bg-indigo-50/80 dark:bg-indigo-950/40';
                                $cardIconBg = 'bg-indigo-600 text-white';
                                $cardIcon = 'apartment';
                                $cardBadgeClass = 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/60 dark:text-indigo-200 dark:border dark:border-indigo-700/50';
                                $cardBadgeText = 'Tahap 4: Konfirmasi Penerimaan Dinas Tujuan';
                                $cardMetaText = 'Menunggu Konfirmasi SKPD';
                                $cardMetaClass = 'text-indigo-700 dark:text-indigo-300/80';
                                $cardTitle = 'Menunggu Persetujuan dari ' . e($app->dinas->name ?? 'Dinas Terkait');
                                $cardTitleClass = 'text-indigo-950 dark:text-indigo-100';
                                $cardDescription = 'Surat Rekomendasi telah disetujui oleh Bakesbangpol! Saat ini berkas Anda sedang diverifikasi oleh pihak <strong>' . e($app->dinas->name ?? 'Dinas Terkait') . '</strong> untuk validasi kuota dan penerbitan izin penerimaan magang resmi.';
                                $cardDescClass = 'text-indigo-900/90 dark:text-indigo-200/90';
                                $cardNote = 'Setelah disetujui dinas, izin penerimaan resmi diterbitkan dan Anda dapat memulai tahap ' . strtolower($pelaksanaanLabel) . ' serta presensi SIMALAM.';
                                $cardActionHtml = '<a href="#app-row-' . $app->id . '" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-slate-800 hover:bg-surface-container-low dark:hover:bg-slate-700 border border-outline-variant/30 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs font-semibold rounded-xl transition shadow-xs"><span class="material-symbols-outlined text-[16px]">visibility</span><span>Lihat di Tabel</span></a>';
                            } else {
                                // 'diterima', 'aktif', 'selesai'
                                $s4_state = 'completed';

                                if ($isFinished) {
                                    $s5_state = 'completed';
                                    $s6_state = 'completed';

                                    $cardClass = 'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/80 dark:bg-emerald-950/40';
                                    $cardIconBg = 'bg-emerald-600 text-white';
                                    $cardIcon = 'workspace_premium';
                                    $cardBadgeClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 dark:border dark:border-emerald-700/50';
                                    $cardBadgeText = 'Tahap 6: Seluruh Rangkaian Selesai';
                                    $cardMetaText = 'Kegiatan Tuntas Selesai';
                                    $cardMetaClass = 'text-emerald-700 dark:text-emerald-300/80';
                                    $cardTitle = 'Selamat! ' . $pelaksanaanLabel . ' Telah Tuntas Dilaksanakan';
                                    $cardTitleClass = 'text-emerald-950 dark:text-emerald-100';
                                    $cardDescription = 'Seluruh rangkaian ' . strtolower($pelaksanaanLabel) . ' Anda di <strong>' . e($app->dinas->name ?? 'Dinas Terkait') . '</strong> telah selesai dilaksanakan. Surat rekomendasi dan riwayat tuntas tersimpan aman di sistem Bangga Online.';
                                    $cardDescClass = 'text-emerald-900/90 dark:text-emerald-200/90';

                                    $btns = '';
                                    if ($app->file_surat_final) {
                                        $btns .= '<a href="' . route('surat.pdf', ['id' => $app->id, 'download' => 1]) . '" download class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm"><span class="material-symbols-outlined text-[18px]">download</span><span>Unduh Surat Rekomendasi</span></a>';
                                    }
                                    $btns .= '<a href="#app-row-' . $app->id . '" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-slate-800 hover:bg-surface-container-low dark:hover:bg-slate-700 border border-outline-variant/30 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs font-semibold rounded-xl transition shadow-xs"><span class="material-symbols-outlined text-[16px]">history</span><span>Lihat Riwayat</span></a>';
                                    $cardActionHtml = $btns;
                                } else {
                                    // Sedang Berjalan
                                    $s5_state = 'active';
                                    $s6_state = 'pending';

                                    $cardClass = 'border-blue-200 dark:border-blue-800/60 bg-blue-50/80 dark:bg-blue-950/40';
                                    $cardIconBg = 'bg-primary dark:bg-blue-600 text-white';
                                    $cardIcon = 'co_present';
                                    $cardBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-200 dark:border dark:border-blue-700/50';
                                    $cardBadgeText = 'Tahap 5: ' . $pelaksanaanLabel;
                                    $cardMetaText = 'Sedang Berlangsung';
                                    $cardMetaClass = 'text-blue-700 dark:text-blue-300/80';
                                    $cardTitle = $pelaksanaanLabel . ' Sedang Berlangsung di ' . e($app->dinas->name ?? 'Dinas Terkait');
                                    $cardTitleClass = 'text-blue-950 dark:text-blue-100';
                                    $cardDescription = 'Permohonan Anda telah resmi diterima oleh <strong>' . e($app->dinas->name ?? 'Dinas Terkait') . '</strong>. Periode pelaksanaan: <strong>' . ($app->tanggal_mulai ? $app->tanggal_mulai->format('d M Y') : '-') . ' s/d ' . ($app->tanggal_selesai ? $app->tanggal_selesai->format('d M Y') : '-') . '</strong>. Pastikan Anda mengisi presensi online secara rutin di portal SIMALAM.';
                                    $cardDescClass = 'text-blue-900/90 dark:text-blue-200/90';

                                    $btns = '';
                                    if ($app->file_surat_final) {
                                        $btns .= '<a href="' . route('surat.pdf', ['id' => $app->id, 'download' => 1]) . '" download class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm"><span class="material-symbols-outlined text-[18px]">download</span><span>Unduh Surat</span></a>';
                                    }
                                    $btns .= '<a href="' . route('absensi.index') . '" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary hover:bg-secondary dark:bg-blue-600 dark:hover:bg-blue-500 text-white text-xs font-bold rounded-xl transition shadow-sm"><span class="material-symbols-outlined text-[18px]">co_present</span><span>Presensi SIMALAM</span></a>';
                                    $cardActionHtml = $btns;
                                }
                            }
                        } else {
                            // Non-magang: Penelitian PT / Instansi / KKN / Pelaksanaan Kegiatan
                            $s4_state = 'completed'; // Surat Rekomendasi Terbit

                            if ($isFinished) {
                                $s5_state = 'completed';
                                $s6_state = 'completed';

                                $cardClass = 'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/80 dark:bg-emerald-950/40';
                                $cardIconBg = 'bg-emerald-600 text-white';
                                $cardIcon = 'workspace_premium';
                                $cardBadgeClass = 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-200 dark:border dark:border-emerald-700/50';
                                $cardBadgeText = 'Tahap 6: Seluruh Rangkaian Selesai';
                                $cardMetaText = 'Kegiatan Tuntas Selesai';
                                $cardMetaClass = 'text-emerald-700 dark:text-emerald-300/80';
                                $cardTitle = 'Selamat! ' . $pelaksanaanLabel . ' Telah Tuntas Dilaksanakan';
                                $cardTitleClass = 'text-emerald-950 dark:text-emerald-100';
                                $cardDescription = 'Seluruh rangkaian permohonan dan ' . strtolower($pelaksanaanLabel) . ' Anda di <strong>' . e($app->dinas->name ?? $app->tempat_kegiatan ?? 'lokasi tujuan') . '</strong> telah selesai dilaksanakan secara resmi.';
                                $cardDescClass = 'text-emerald-900/90 dark:text-emerald-200/90';

                                if ($app->file_surat_final) {
                                    $cardActionHtml = '<a href="' . route('surat.pdf', ['id' => $app->id, 'download' => 1]) . '" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm"><span class="material-symbols-outlined text-[18px]">download</span><span>Unduh Surat Rekomendasi</span></a>';
                                }
                            } else {
                                // Sedang Berjalan
                                $s5_state = 'active';
                                $s6_state = 'pending';

                                $cardClass = 'border-blue-200 dark:border-blue-800/60 bg-blue-50/80 dark:bg-blue-950/40';
                                $cardIconBg = 'bg-primary dark:bg-blue-600 text-white';
                                $cardIcon = $pelaksanaanIcon;
                                $cardBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-200 dark:border dark:border-blue-700/50';
                                $cardBadgeText = 'Tahap 5: ' . $pelaksanaanLabel;
                                $cardMetaText = 'Sedang Berlangsung';
                                $cardMetaClass = 'text-blue-700 dark:text-blue-300/80';
                                $cardTitle = $pelaksanaanLabel . ' Sedang Berjalan';
                                $cardTitleClass = 'text-blue-950 dark:text-blue-100';
                                $cardDescription = 'Surat rekomendasi izin resmi telah diterbitkan Bakesbangpol Kabupaten Bogor. Saat ini Anda sedang dalam masa ' . strtolower($pelaksanaanLabel) . ' di <strong>' . e($app->dinas->name ?? $app->tempat_kegiatan ?? 'lokasi tujuan') . '</strong> hingga <strong>' . ($app->tanggal_selesai ? $app->tanggal_selesai->format('d M Y') : 'periode berakhir') . '</strong>.';
                                $cardDescClass = 'text-blue-900/90 dark:text-blue-200/90';

                                if ($app->file_surat_final) {
                                    $cardActionHtml = '<a href="' . route('surat.pdf', ['id' => $app->id, 'download' => 1]) . '" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition shadow-sm"><span class="material-symbols-outlined text-[18px]">download</span><span>Unduh Surat Rekomendasi</span></a>';
                                }
                            }
                        }
                    } else {
                        // Menunggu verifikasi / proses
                        $s3_state = 'active';
                        $s4_state = 'pending';
                        $s5_state = 'pending';
                        $s6_state = 'pending';

                        $cardClass = 'border-blue-200 dark:border-blue-900/60 bg-blue-50/80 dark:bg-blue-950/40';
                        $cardIconBg = 'bg-blue-500 text-white dark:bg-blue-600';
                        $cardIcon = 'hourglass_top';
                        $cardBadgeClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/60 dark:text-blue-200 dark:border dark:border-blue-700/50';
                        $cardBadgeText = 'Tahap 3: Verifikasi Dokumen Kesbangpol';
                        $cardMetaText = 'Estimasi 1–3 Hari Kerja';
                        $cardMetaClass = 'text-blue-700 dark:text-blue-300/80';
                        $cardTitle = 'Permohonan Sedang Dalam Antrean Verifikasi';
                        $cardTitleClass = 'text-blue-950 dark:text-blue-100';
                        $cardDescription = 'Permohonan <strong>' . e($app->jenisLayanan->nama ?? $app->jenis_permohonan) . '</strong> (#' . $app->id . ') telah diterima sistem Bangga Online. Petugas verifikator Bakesbangpol Kab. Bogor saat ini sedang meneliti kelengkapan berkas dan dokumen persyaratan.';
                        $cardDescClass = 'text-blue-900/90 dark:text-blue-200/90';
                        $cardNote = 'Dinas Tujuan: <strong>' . e($app->dinas->name ?? $app->tempat_kegiatan ?? '-') . '</strong> &bull; Diajukan pada: <strong>' . ($app->created_at ? $app->created_at->format('d M Y, H:i') : '-') . ' WIB</strong>';
                        $cardActionHtml = '<a href="#app-row-' . $app->id . '" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-white dark:bg-slate-800 hover:bg-surface-container-low dark:hover:bg-slate-700 border border-outline-variant/30 dark:border-slate-700 text-on-surface dark:text-slate-200 text-xs font-semibold rounded-xl transition shadow-xs"><span class="material-symbols-outlined text-[16px]">visibility</span><span>Lihat di Tabel</span></a>';
                    }
                @endphp

                <div id="app-stepper-view-{{ $app->id }}" class="app-stepper-view {{ $index === 0 ? '' : 'hidden' }} pt-5">
                    <!-- Horizontal Stepper Row: 6 Steps -->
                    <div class="w-full overflow-x-auto pb-3 pt-1 -mx-2 px-2 scrollbar-thin">
                        <div class="relative flex items-start justify-between w-full min-w-[780px] xl:min-w-0">
                            
                            <!-- Step 1: Akun Terdaftar -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <!-- Connector to Step 2 -->
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 {{ $s2_state === 'completed' ? 'bg-emerald-500 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-700/80' }} z-0"></div>
                                
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                    <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">1. Akun Terdaftar</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Masuk Sistem</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Selesai
                                </span>
                            </div>

                            <!-- Step 2: Pengajuan Berkas -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <!-- Connector to Step 3 -->
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 {{ $s3_state === 'completed' ? 'bg-emerald-500 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-700/80' }} z-0"></div>

                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                    <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">2. Pengajuan Berkas</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Formulir & Dokumen</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Terkirim
                                </span>
                            </div>

                            <!-- Step 3: Verifikasi Kesbangpol -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <!-- Connector to Step 4 -->
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 {{ $s4_state === 'completed' ? 'bg-emerald-500 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-700/80' }} z-0"></div>

                                @if($s3_state === 'completed')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                        <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">3. Verifikasi Kesbangpol</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Pemeriksaan Berkas</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Disetujui
                                    </span>
                                @elseif($s3_state === 'revision')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-amber-500 text-white shadow-md ring-4 ring-amber-100 dark:ring-amber-950/70 animate-pulse">
                                        <span class="material-symbols-outlined text-[20px]">edit_document</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-amber-700 dark:text-amber-400 mt-2.5 leading-snug">3. Verifikasi Kesbangpol</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Catatan Perbaikan</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-amber-800 dark:text-amber-300 mt-1 px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-950/70 dark:border dark:border-amber-800/60">
                                        <span class="material-symbols-outlined text-[13px]">warning</span> Perlu Revisi
                                    </span>
                                @elseif($s3_state === 'rejected')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-red-500 text-white shadow-md ring-4 ring-red-100 dark:ring-red-950/70">
                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-red-700 dark:text-red-400 mt-2.5 leading-snug">3. Verifikasi Kesbangpol</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Tidak Disetujui</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-red-700 dark:text-red-300 mt-1 px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-950/70 dark:border dark:border-red-800/60">
                                        <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak
                                    </span>
                                @else
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-primary dark:bg-blue-600 text-white shadow-md ring-4 ring-primary/20 dark:ring-blue-500/30">
                                        <span class="material-symbols-outlined text-[20px]">hourglass_top</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-primary dark:text-blue-400 mt-2.5 leading-snug">3. Verifikasi Kesbangpol</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Pemeriksaan Berkas</span>
                                    <span class="inline-flex items-center text-[10px] font-bold text-primary dark:text-blue-300 mt-1 px-2.5 py-0.5 rounded-full bg-primary/10 dark:bg-blue-950/70 dark:border dark:border-blue-800/60">
                                        Tahap Saat Ini
                                    </span>
                                @endif
                            </div>

                            <!-- Step 4: Penerimaan Dinas / Instansi -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <!-- Connector to Step 5 -->
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 {{ $s5_state === 'completed' ? 'bg-emerald-500 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-700/80' }} z-0"></div>

                                @if($s4_state === 'completed')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                        <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">4. Penerimaan Dinas</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">{{ $isMagang ? 'Diterima SKPD' : 'Surat Terbit' }}</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> {{ $isMagang ? 'Diterima' : 'Disetujui' }}
                                    </span>
                                @elseif($s4_state === 'active')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-indigo-600 text-white shadow-md ring-4 ring-indigo-100 dark:ring-indigo-950/70">
                                        <span class="material-symbols-outlined text-[20px]">apartment</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-indigo-700 dark:text-indigo-400 mt-2.5 leading-snug">4. Penerimaan Dinas</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Pengecekan Kuota</span>
                                    <span class="inline-flex items-center text-[10px] font-bold text-indigo-700 dark:text-indigo-300 mt-1 px-2.5 py-0.5 rounded-full bg-indigo-100 dark:bg-indigo-950/70 dark:border dark:border-indigo-800/60">
                                        Menunggu Dinas
                                    </span>
                                @elseif($s4_state === 'rejected')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-red-500 text-white shadow-md ring-4 ring-red-100 dark:ring-red-950/70">
                                        <span class="material-symbols-outlined text-[20px]">close</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-red-700 dark:text-red-400 mt-2.5 leading-snug">4. Penerimaan Dinas</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Tidak Diterima</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-red-700 dark:text-red-300 mt-1 px-2 py-0.5 rounded-full bg-red-50 dark:bg-red-950/70 dark:border dark:border-red-800/60">
                                        <span class="material-symbols-outlined text-[13px]">cancel</span> Ditolak Dinas
                                    </span>
                                @else
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                        <span class="font-bold text-sm">4</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">4. Penerimaan Dinas</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">{{ $isMagang ? 'Validasi SKPD' : 'Surat Terbit' }}</span>
                                    <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Menunggu</span>
                                @endif
                            </div>

                            <!-- Step 5: Pelaksanaan Dinamis (PKL / Magang / KKN / Riset / Kegiatan) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <!-- Connector to Step 6 -->
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 {{ $s6_state === 'completed' ? 'bg-emerald-500 dark:bg-emerald-500' : 'bg-gray-200 dark:bg-slate-700/80' }} z-0"></div>

                                @if($s5_state === 'completed')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                        <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">5. {{ $pelaksanaanLabel }}</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Aktivitas Selesai</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Selesai
                                    </span>
                                @elseif($s5_state === 'active')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-primary dark:bg-blue-600 text-white shadow-md ring-4 ring-primary/20 dark:ring-blue-500/30">
                                        <span class="material-symbols-outlined text-[20px]">{{ $pelaksanaanIcon }}</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-primary dark:text-blue-400 mt-2.5 leading-snug">5. {{ $pelaksanaanLabel }}</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">{{ $pelaksanaanSub }}</span>
                                    <span class="inline-flex items-center text-[10px] font-bold text-primary dark:text-blue-300 mt-1 px-2.5 py-0.5 rounded-full bg-primary/10 dark:bg-blue-950/70 dark:border dark:border-blue-800/60">
                                        Sedang Berjalan
                                    </span>
                                @else
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                        <span class="font-bold text-sm">5</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">5. {{ $pelaksanaanLabel }}</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">{{ $pelaksanaanSub }}</span>
                                    <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Menunggu</span>
                                @endif
                            </div>

                            <!-- Step 6: Selesai -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                @if($s6_state === 'completed')
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                        <span class="material-symbols-outlined text-[20px] font-bold">workspace_premium</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">6. Selesai</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Kegiatan Tuntas</span>
                                    <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                        <span class="material-symbols-outlined text-[13px]">check_circle</span> Tuntas
                                    </span>
                                @else
                                    <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                        <span class="font-bold text-sm">6</span>
                                    </div>
                                    <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">6. Selesai</span>
                                    <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">Tahap Penutupan</span>
                                    <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Tahap Akhir</span>
                                @endif
                            </div>

                        </div>
                    </div>


                </div>
                @endforeach

            @else
                {{-- Empty State: User logged in but no applications submitted yet --}}
                <div id="app-stepper-view-empty" class="pt-5">
                    <div class="w-full overflow-x-auto pb-3 pt-1 -mx-2 px-2 scrollbar-thin">
                        <div class="relative flex items-start justify-between w-full min-w-[780px] xl:min-w-0">
                            
                            <!-- Step 1: Akun Terdaftar (Selesai) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 bg-gray-200 dark:bg-slate-700/80 z-0"></div>
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-emerald-500 text-white shadow-xs ring-4 ring-emerald-100 dark:ring-emerald-950/70">
                                    <span class="material-symbols-outlined text-[20px] font-bold">check</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface dark:text-slate-200 mt-2.5 leading-snug">1. Akun Terdaftar</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Masuk Sistem</span>
                                <span class="inline-flex items-center gap-0.5 text-[10px] font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                                    <span class="material-symbols-outlined text-[13px]">check_circle</span> Selesai
                                </span>
                            </div>

                            <!-- Step 2: Pengajuan Berkas (Aktif / Tahap Saat Ini) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 bg-gray-200 dark:bg-slate-700/80 z-0"></div>
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-primary dark:bg-blue-600 text-white shadow-md ring-4 ring-primary/20 dark:ring-blue-500/30">
                                    <span class="material-symbols-outlined text-[20px]">post_add</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-primary dark:text-blue-400 mt-2.5 leading-snug">2. Pengajuan Berkas</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-400 font-medium mt-0.5">Tambah Layanan Baru</span>
                                <span class="inline-flex items-center text-[10px] font-bold text-primary dark:text-blue-300 mt-1 px-2.5 py-0.5 rounded-full bg-primary/10 dark:bg-blue-950/70 dark:border dark:border-blue-800/60">
                                    Tahap Saat Ini
                                </span>
                            </div>

                            <!-- Step 3: Verifikasi Kesbangpol (Pending) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 bg-gray-200 dark:bg-slate-700/80 z-0"></div>
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                    <span class="font-bold text-sm">3</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">3. Verifikasi Kesbangpol</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">Pemeriksaan Berkas</span>
                                <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Menunggu</span>
                            </div>

                            <!-- Step 4: Penerimaan Dinas (Pending) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 bg-gray-200 dark:bg-slate-700/80 z-0"></div>
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                    <span class="font-bold text-sm">4</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">4. Penerimaan Dinas</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">Validasi SKPD</span>
                                <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Menunggu</span>
                            </div>

                            <!-- Step 5: Pelaksanaan Kegiatan (Pending) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="absolute top-[22px] left-1/2 w-full h-1 -translate-y-1/2 bg-gray-200 dark:bg-slate-700/80 z-0"></div>
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                    <span class="font-bold text-sm">5</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">5. Pelaksanaan Kegiatan</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">Aktivitas & Presensi</span>
                                <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Menunggu</span>
                            </div>

                            <!-- Step 6: Selesai (Pending) -->
                            <div class="flex-1 flex flex-col items-center text-center relative px-1">
                                <div class="relative z-10 w-11 h-11 rounded-full flex items-center justify-center bg-surface-container-low dark:bg-slate-800/90 text-outline-variant/80 dark:text-slate-500 border-2 border-outline-variant/30 dark:border-slate-700">
                                    <span class="font-bold text-sm">6</span>
                                </div>
                                <span class="font-bold text-xs sm:text-sm text-on-surface/70 dark:text-slate-400 mt-2.5 leading-snug">6. Selesai</span>
                                <span class="text-[11px] text-on-surface-variant dark:text-slate-500 font-medium mt-0.5">Kegiatan Tuntas</span>
                                <span class="text-[10px] font-medium text-on-surface-variant/70 dark:text-slate-500 mt-1">Tahap Akhir</span>
                            </div>

                        </div>
                    </div>
                </div>
            @endif
        </div>
        @endif

        <!-- Table Content -->
        @if(auth()->user()->status_akun == 'diblokir')
        <div class="text-center py-10 px-4 bg-red-50/50 rounded-xl border border-dashed border-red-200 flex flex-col items-center justify-center gap-3">
            <div class="w-14 h-14 bg-red-100 text-red-600 rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-[32px]">block</span>
            </div>
            <h3 class="font-title-lg text-title-lg text-red-800 font-semibold">Akses Data Diblokir</h3>
            <p class="font-body-md text-body-md text-red-700/80 max-w-md">
                Riwayat permohonan Anda tidak dapat ditampilkan karena akun Anda telah diblokir.
            </p>
        </div>
        @elseif(isset($userApplications) && $userApplications->count() > 0)
        <div class="overflow-x-auto rounded-xl border border-outline-variant/30 shadow-level-1 bg-surface-container-lowest">
            <table class="w-full text-left border-collapse min-w-[850px]">
                <thead class="bg-surface-container-low text-on-surface font-label-md text-xs uppercase tracking-wider border-b border-outline-variant/30">
                    <tr>
                        <th class="py-4 px-4 pl-6 whitespace-nowrap w-[70px]">No</th>
                        <th class="py-4 px-4 whitespace-nowrap min-w-[180px]">Tanggal / Waktu Pengajuan</th>
                        <th class="py-4 px-4 min-w-[200px]">Jenis Layanan</th>
                        <th class="py-4 px-4 min-w-[280px]">Dinas Tujuan & Topik</th>
                        <th class="py-4 px-4 text-center whitespace-nowrap min-w-[130px]">Keterangan</th>
                        <th class="py-4 px-4 pr-6 text-center whitespace-nowrap min-w-[140px]">Aksi / Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md text-on-surface">
                    @foreach($userApplications as $index => $app)
                    @php
                        $statusKode = strtolower(optional($app->statusMaster)->kode ?? 'proses');
                    @endphp
                    <tr id="app-row-{{ $app->id }}" class="hover:bg-surface-container-low/40 transition-colors">
                        <td class="py-4 px-4 pl-6 align-middle whitespace-nowrap">
                            <span class="font-bold text-sm text-on-surface">#{{ $app->id }}</span>
                        </td>
                        <td class="py-4 px-4 align-middle whitespace-nowrap">
                            <span class="text-xs text-on-surface-variant font-medium">{{ $app->created_at ? $app->created_at->format('d M Y, H:i') : '-' }}</span>
                        </td>
                        <td class="py-4 px-4 align-middle">
                            <span class="font-semibold text-primary text-sm leading-snug block">
                                {{ $app->jenisLayanan->nama ?? $app->jenis_permohonan ?? 'Permohonan Rekomendasi' }}
                            </span>
                        </td>
                        <td class="py-4 px-4 align-middle">
                            <div class="flex items-start gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 mt-0.5">
                                    <span class="material-symbols-outlined text-[18px]">apartment</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-sm text-on-surface leading-snug">
                                        {{ $app->dinas->name ?? $app->tempat_kegiatan ?? '-' }}
                                    </div>
                                    @if(!empty($app->judul_kegiatan) && trim($app->judul_kegiatan) !== '-')
                                        <div class="text-xs text-on-surface-variant mt-1 leading-tight flex items-center gap-1" title="{{ $app->judul_kegiatan }}">
                                            <span class="material-symbols-outlined text-[13px] text-outline shrink-0">topic</span>
                                            <span class="truncate">{{ $app->judul_kegiatan }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4 text-center align-middle">
                            @if($app->keterangan)
                                <div class="text-xs text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-950/40 p-2.5 rounded-lg border border-red-200 dark:border-red-900/60 max-w-xs leading-relaxed font-medium text-left">
                                    {{ $app->keterangan }}
                                </div>
                            @elseif($app->magangApplication && $app->magangApplication->catatan_admin)
                                <div class="text-xs text-gray-700 dark:text-slate-300 bg-gray-50 dark:bg-slate-800/60 p-2.5 rounded-lg border border-gray-200 dark:border-slate-700 max-w-xs leading-relaxed font-medium text-left">
                                    {{ $app->magangApplication->catatan_admin }}
                                </div>
                            @else
                                <span class="text-xs text-on-surface-variant font-medium">-</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 pr-6 text-center align-middle whitespace-nowrap">
                            <div class="flex items-center justify-center gap-1.5 flex-wrap">

                                @if($app->file_surat_final)
                                    <a href="{{ route('surat.pdf', ['id' => $app->id, 'download' => 1]) }}" download class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-secondary transition-all shadow-xs cursor-pointer" title="Unduh Surat Rekomendasi Final (PDF)">
                                        <span class="material-symbols-outlined text-[16px]">download</span>
                                        <span>Unduh Surat</span>
                                    </a>
                                @elseif(in_array($statusKode, ['disetujui', 'selesai', 'diterima', 'aktif']))
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/60" title="Surat sedang diproses/ menunggu penandatanganan">
                                        <span class="material-symbols-outlined text-[15px]">hourglass_top</span>
                                        <span>Surat diproses</span>
                                    </span>
                                @endif

                                @if(in_array($statusKode, ['perlu_revisi', 'menunggu_verifikasi']))
                                    <button type="button" onclick="openEditModal('{{ $app->id }}')" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-600 text-white rounded-lg text-xs font-semibold hover:bg-amber-700 transition-all shadow-xs cursor-pointer" title="Edit / Revisi Permohonan">
                                        <span class="material-symbols-outlined text-[15px]">edit</span>
                                        <span>Edit</span>
                                    </button>
                                @endif

                                @if($statusKode == 'menunggu_verifikasi')
                                    <form action="{{ route('layanan.destroy', $app->id) }}" method="POST" onsubmit="event.preventDefault(); confirmDeletePermohonan(this);" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-600 text-white rounded-lg text-xs font-semibold hover:bg-red-700 transition-all shadow-xs cursor-pointer" title="Hapus Permohonan">
                                            <span class="material-symbols-outlined text-[15px]">delete</span>
                                            <span>Hapus</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-10 px-4 bg-surface-container-low/40 rounded-xl border border-dashed border-outline-variant/60 flex flex-col items-center justify-center gap-3">
            <div class="w-14 h-14 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                <span class="material-symbols-outlined text-[32px]">note_add</span>
            </div>
            <h3 class="font-title-lg text-title-lg text-on-surface font-semibold">Belum Ada Permohonan Surat</h3>
            <p class="font-body-md text-body-md text-on-surface-variant max-w-md">
                Anda belum mengajukan permohonan surat izin rekomendasi. Klik tombol <strong>"+ Tambah Layanan Baru"</strong> di atas untuk langsung memilih jenis layanan yang dibutuhkan.
            </p>
        </div>
        @endif
    </div>

    <!-- Edit Application Modals -->
    @if(isset($userApplications) && $userApplications->count() > 0)
        @foreach($userApplications as $app)
        @php
            $appStatusKode = strtolower(optional($app->statusMaster)->kode ?? 'proses');
        @endphp
        @if(in_array($appStatusKode, ['perlu_revisi', 'menunggu_verifikasi']))
        <div id="edit_modal_{{ $app->id }}" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div id="edit_backdrop_{{ $app->id }}" class="fixed inset-0 bg-black/60 backdrop-blur-xs transition-opacity duration-300 opacity-0 z-10" onclick="closeEditModal('{{ $app->id }}')"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div id="edit_modal_box_{{ $app->id }}" class="relative z-20 inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                    <div class="bg-surface-container-low px-6 py-4 border-b border-outline-variant/20 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-600 text-[24px]">edit_square</span>
                            <h3 class="font-title-lg text-title-lg text-on-surface font-bold">Edit / Revisi Permohonan #{{ $app->id }}</h3>
                        </div>
                        <button type="button" onclick="closeEditModal('{{ $app->id }}')" class="text-on-surface-variant hover:text-on-surface p-1 rounded-lg hover:bg-surface-container transition-colors">
                            <span class="material-symbols-outlined text-[24px]">close</span>
                        </button>
                    </div>

                    <form action="{{ route('layanan.update', $app->id) }}" method="POST" enctype="multipart/form-data" class="p-6 flex flex-col gap-4">
                        @csrf

                        @if($app->keterangan)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-xs text-red-800 flex items-start gap-3">
                            <span class="material-symbols-outlined text-red-600 text-[20px] shrink-0 mt-0.5">error</span>
                            <div>
                                <strong class="font-bold text-red-900 block mb-0.5">Catatan Revisi dari Kesbangpol:</strong>
                                <p class="leading-relaxed">{{ $app->keterangan }}</p>
                            </div>
                        </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Atas Nama Pemohon / Ketua</label>
                                <input type="text" name="atas_nama" value="{{ $app->atas_nama }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5" required>
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Nomor WhatsApp / HP</label>
                                <input type="text" name="no_hp" value="{{ $app->no_hp }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5" required>
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Asal Instansi / Universitas</label>
                                <input type="text" name="asal_instansi" value="{{ $app->asal_instansi }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5" required>
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Lokasi / Dinas Tujuan</label>
                                <input type="text" name="tempat_kegiatan" value="{{ $app->tempat_kegiatan }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5" required>
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-bold text-gray-700 mb-1">Judul / Tema Kegiatan</label>
                                <input type="text" name="judul_kegiatan" value="{{ $app->judul_kegiatan }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5">
                            </div>

                            <div>
                                <label class="block font-bold text-gray-700 mb-1">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->format('Y-m-d') : '' }}" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5">
                            </div>
                        </div>

                        <hr class="my-2 border-gray-200">
                        <h4 class="font-bold text-gray-800 text-xs uppercase tracking-wider">Unggah Ulang Dokumen Lampiran (Opsional jika ingin mengganti file):</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Surat Pengantar Asli (PDF)</label>
                                <input type="file" name="file_surat_pengantar" accept="application/pdf" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">Proposal (PDF)</label>
                                <input type="file" name="file_proposal" accept="application/pdf" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">KTP / Identitas (PDF/JPG/PNG)</label>
                                <input type="file" name="file_ktp" accept="application/pdf,image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                            </div>

                            <div>
                                <label class="block font-semibold text-gray-700 mb-1">KTM / Kartu Pelajar (PDF/JPG/PNG)</label>
                                <input type="file" name="file_ktm" accept="application/pdf,image/*" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-gray-700 mb-1">Dokumen Pendukung Lainnya (PDF)</label>
                                <input type="file" name="file_pendukung" accept="application/pdf" class="block w-full text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-gray-700 mb-1">Catatan / Pesan Revisi untuk Admin (Opsional)</label>
                                <textarea name="catatan_pemohon" rows="2" placeholder="Contoh: Dokumen KTP sudah di-scan ulang dalam format PDF yang jelas." class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 text-xs p-2.5"></textarea>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                            <button type="button" onclick="closeEditModal('{{ $app->id }}')" class="px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 text-xs transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 bg-primary text-white font-semibold rounded-lg hover:bg-secondary text-xs transition-colors shadow-sm flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">send</span>
                                <span>Simpan & Kirim Ulang</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
        @endforeach
    @endif
</section>
@endauth

@guest
<!-- Timeline Section (Alur Tahapan Permohonan Magang) -->
<section id="timeline-section" class="max-w-container-max mx-auto px-margin-desktop pt-16 pb-8 w-full">
<div class="flex flex-col gap-2 mb-10 pb-6 border-b border-outline-variant/30">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">
        Alur Tahapan Permohonan Magang
    </h2>
    <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Informasi tahapan pelaksanaan program magang di lingkungan Pemerintah Kabupaten Bogor.</p>
</div>
<div class="relative">
<!-- Connecting Line -->

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 relative z-10 stagger-container">
<!-- Stage 1 -->
<div class="flex flex-col relative group cursor-pointer reveal-element">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-ambient border border-surface-container-high flex-grow flex flex-col gap-3 group-hover:border-primary group-hover:shadow-hover group-hover:-translate-y-1 transition-all duration-300">
<div class="flex justify-between items-center mb-1">
    <span class="font-label-md text-label-md text-secondary font-bold">01</span>
    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[20px] fill">description</span>
    </div>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Pendaftaran Calon Peserta Magang</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Calon peserta mengajukan pendaftaran program magang sesuai dengan ketentuan yang berlaku.</p>
</div>
</div>
<!-- Stage 2 -->
<div class="flex flex-col relative group cursor-pointer reveal-element">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-ambient border border-surface-container-high flex-grow flex flex-col gap-3 group-hover:border-primary group-hover:shadow-hover group-hover:-translate-y-1 transition-all duration-300">
<div class="flex justify-between items-center mb-1">
    <span class="font-label-md text-label-md text-secondary font-bold">02</span>
    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[20px] fill">search</span>
    </div>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Verifikasi Peserta</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Data dan persyaratan calon peserta melalui proses verifikasi.</p>
</div>
</div>
<!-- Stage 3 -->
<div class="flex flex-col relative group cursor-pointer reveal-element">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-ambient border border-surface-container-high flex-grow flex flex-col gap-3 group-hover:border-primary group-hover:shadow-hover group-hover:-translate-y-1 transition-all duration-300">
<div class="flex justify-between items-center mb-1">
    <span class="font-label-md text-label-md text-secondary font-bold">03</span>
    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[20px] fill">check_circle</span>
    </div>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Penetapan Peserta Magang</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Calon peserta yang memenuhi persyaratan ditetapkan sebagai peserta magang dan memperoleh informasi penempatan.</p>
</div>
</div>
<!-- Stage 4 -->
<div class="flex flex-col relative group cursor-pointer reveal-element">
<div class="bg-surface-container-lowest p-6 rounded-xl shadow-ambient border border-surface-container-high flex-grow flex flex-col gap-3 group-hover:border-primary group-hover:shadow-hover group-hover:-translate-y-1 transition-all duration-300">
<div class="flex justify-between items-center mb-1">
    <span class="font-label-md text-label-md text-secondary font-bold">04</span>
    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center shadow-md group-hover:scale-110 transition-transform duration-300">
        <span class="material-symbols-outlined text-[20px] fill">work</span>
    </div>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Pelaksanaan Program Magang</h3>
<p class="font-body-md text-body-md text-on-surface-variant">Peserta melaksanakan program magang pada Dinas / OPD yang telah ditetapkan sesuai dengan ketentuan yang berlaku.</p>
</div>
</div>
</div>
</div>
</section>

<!-- Jenis Pelayanan Surat Izin Rekomendasi Section -->
<section id="services-section" class="max-w-container-max mx-auto px-margin-desktop pt-8 pb-8 relative z-10 w-full overflow-hidden">
<div class="flex flex-col md:flex-row md:items-end md:justify-between mb-10 gap-4">
    <div class="flex flex-col gap-2">
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Jenis Pelayanan Surat Izin Rekomendasi</h2>
        <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">Silakan pilih jenis pelayanan sesuai dengan kebutuhan Anda.</p>
    </div>
    <!-- Navigation Buttons -->
    <div class="flex gap-2.5">
        <button id="slide-left-btn" class="w-10 h-10 rounded-full border border-outline-variant/60 flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-primary transition-all shadow-sm" aria-label="Slide Left">
            <span class="material-symbols-outlined" style="font-size: 20px;">chevron_left</span>
        </button>
        <button id="slide-right-btn" class="w-10 h-10 rounded-full border border-outline-variant/60 flex items-center justify-center text-on-surface-variant hover:bg-surface-container hover:text-primary transition-all shadow-sm" aria-label="Slide Right">
            <span class="material-symbols-outlined" style="font-size: 20px;">chevron_right</span>
        </button>
    </div>
</div>

<div id="services-scroll-container" class="flex flex-row overflow-x-auto gap-6 pt-4 pb-4 px-1 -mx-1 -mt-8 scrollbar-hide stagger-container scroll-smooth" style="scroll-snap-type: x mandatory;">
<div data-service="Penelitian (Perguruan Tinggi)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">school</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Penelitian (Perguruan Tinggi)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan pengajuan surat izin rekomendasi untuk kegiatan penelitian dari perguruan tinggi.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('penelitian_pt')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="Penelitian (Instansi / Lembaga Lainnya)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">business</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Penelitian (Instansi / Lembaga Lainnya)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan pengajuan surat izin rekomendasi kegiatan penelitian bagi instansi atau lembaga lainnya.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('penelitian_instansi')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="KKL / PKL / Magang (Mahasiswa)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">work</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKL / PKL / Magang (Mahasiswa)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Informasi persyaratan dan pengajuan surat izin rekomendasi untuk kegiatan KKL, PKL, dan magang mahasiswa.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('kkl_mahasiswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="KKN Mahasiswa" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">groups</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKN Mahasiswa</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan pengajuan surat izin rekomendasi untuk pelaksanaan program Kuliah Kerja Nyata.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('kkn_mahasiswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="KKL / PKL / Magang (Siswa Sekolah)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">backpack</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKL / PKL / Magang (Siswa Sekolah)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Informasi persyaratan dan pengajuan surat izin rekomendasi untuk kegiatan KKL, PKL, atau magang bagi siswa sekolah.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('kkl_siswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="Pelaksanaan Kegiatan" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">event</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Pelaksanaan Kegiatan</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan surat izin rekomendasi untuk pelaksanaan kegiatan yang membutuhkan persetujuan sesuai ketentuan.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('pelaksanaan_kegiatan')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
<div data-service="Perpanjangan Izin Rekomendasi" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">update</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Perpanjangan Izin Rekomendasi</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pengajuan perpanjangan masa berlaku surat izin rekomendasi yang telah diterbitkan sebelumnya.</p>
@if(auth()->check() && in_array(auth()->user()->status_akun, ['diblokir', 'dibatasi']))
<button disabled class="mt-4 w-full py-3 bg-outline-variant/50 text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed font-medium">Akun {{ ucwords(auth()->user()->status_akun) }}</button>
@else
<button onclick="openModal('perpanjangan')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
@endif
</div>
</div>
</section>

<!-- Statistics Section (SMART Setjen DPR RI Style) -->
<section id="statistics-section" class="max-w-container-max mx-auto px-margin-desktop pt-8 pb-12 relative z-20 w-full">
<div class="flex flex-col gap-2 mb-10 pb-6 border-b border-outline-variant/30">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">
        Rekap Statistik
    </h2>
    <p class="font-body-md text-body-md text-on-surface-variant">Statistik real-time pendaftaran & status kelulusan.</p>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-stack-md stagger-container">
<!-- Stat Card 1: Instansi Tersedia -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient border-t-4 border-outline-variant hover:border-secondary flex flex-col gap-2 hover:shadow-hover transition-all duration-300 reveal-scale hover-card-trigger">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-tertiary-fixed-dim">account_balance</span>
<span class="font-label-md text-label-md uppercase">Instansi Tersedia</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface hover:text-primary transition-colors duration-300 cursor-default">{{ number_format($stats['dinas_tersedia'], 0, ',', '.') }}</span>
</div>
<!-- Stat Card 2: Total Peserta Terdaftar -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient border-t-4 border-outline-variant hover:border-secondary flex flex-col gap-2 hover:shadow-hover transition-all duration-300 reveal-scale hover-card-trigger">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-secondary">groups</span>
<span class="font-label-md text-label-md uppercase">Total Peserta Terdaftar</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface hover:text-primary transition-colors duration-300 cursor-default">{{ number_format($stats['total_pendaftar'], 0, ',', '.') }}</span>
</div>
<!-- Stat Card 3: Peserta Diterima -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient border-t-4 border-outline-variant hover:border-secondary flex flex-col gap-2 hover:shadow-hover transition-all duration-300 reveal-scale hover-card-trigger">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-secondary-container">how_to_reg</span>
<span class="font-label-md text-label-md uppercase">Peserta Diterima</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface hover:text-primary transition-colors duration-300 cursor-default">{{ number_format($stats['diterima'], 0, ',', '.') }}</span>
</div>
<!-- Stat Card 4: Peserta Aktif -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient border-t-4 border-outline-variant hover:border-secondary flex flex-col gap-2 hover:shadow-hover transition-all duration-300 reveal-scale hover-card-trigger">
<div class="flex items-center gap-2 text-on-surface-variant">
<span class="material-symbols-outlined text-on-surface hover:text-primary transition-colors duration-300 cursor-default-fixed-dim">school</span>
<span class="font-label-md text-label-md uppercase">Peserta Aktif</span>
</div>
<span class="font-headline-lg text-headline-lg text-on-surface hover:text-primary transition-colors duration-300 cursor-default">{{ number_format($stats['aktif'], 0, ',', '.') }}</span>
</div>
</div>
</section>

<!-- Interactive Analytics Section -->
<section id="analytics-section" class="max-w-container-max mx-auto px-margin-desktop pb-16 relative z-20 reveal-scale w-full">
    <div class="bg-surface-container-lowest rounded-xl shadow-ambient border border-outline-variant/30 p-8 flex flex-col gap-8">
        <!-- Section Header: Service Filter Bar -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 border-b border-outline-variant/20 pb-6">
            <div class="max-w-md w-full">
                <!-- Service Selector -->
                <div class="flex flex-col gap-1.5">
                    <label for="service-selector" class="font-label-md text-label-md text-on-surface-variant font-bold uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[18px] text-secondary">category</span>
                        Pilih Jenis Layanan:
                    </label>
                    <div class="relative">
                        <select id="service-selector" class="w-full rounded-lg border-outline-variant/60 bg-surface-container-low text-on-surface font-label-md text-label-md p-3 pr-10 focus:border-secondary focus:ring focus:ring-secondary/20 transition-all appearance-none [background-image:none] cursor-pointer" style="background-image: none;">
                            @foreach(array_keys($chartData) as $service)
                                <option value="{{ $service }}">{{ $service }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
                    </div>
                </div>
            </div>

            <!-- Service Filter Active Badge -->
            <div class="flex items-center gap-3 shrink-0 self-start md:self-end pb-1 text-xs text-on-surface-variant">
                <span id="active-service-badge" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-secondary/10 text-secondary text-[11px] font-semibold border border-secondary/20 whitespace-nowrap shrink-0 shadow-sm">
                    <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse shrink-0"></span>
                    <span id="active-service-text" class="whitespace-nowrap inline-block leading-none">Semua Layanan</span>
                </span>
            </div>
        </div>

        <!-- Charts Grid (Status Kelulusan Berkas & Top 10 Instansi) -->
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Left Card: Donut Chart (Total Registered vs Accepted) -->
            <div class="lg:col-span-2 bg-surface-container-low/40 rounded-xl p-6 border border-outline-variant/20 flex flex-col gap-6">
                <div class="flex flex-col gap-1">
                    <h3 class="font-title-lg text-title-lg text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-secondary text-[22px]">pie_chart</span>
                        Status Kelulusan Berkas
                    </h3>
                    <p class="font-caption text-caption text-on-surface-variant">Rasio berkas pendaftaran terdaftar yang telah diterima.</p>
                </div>
                
                <!-- Chart Area -->
                <div class="flex-grow flex items-center justify-center min-h-[280px] relative">
                    <div id="donut-chart-container" class="w-full"></div>
                </div>

                <!-- Custom Stats Summary Box -->
                <div class="grid grid-cols-2 gap-4 border-t border-outline-variant/20 pt-4">
                    <div class="flex flex-col items-center p-3 bg-surface-container-low rounded-lg border border-outline-variant/10 text-center">
                        <span class="font-caption text-caption text-on-surface-variant font-semibold uppercase">Total Pendaftar</span>
                        <span id="stat-registered-val" class="font-headline-md text-headline-md text-on-surface mt-1">0</span>
                    </div>
                    <div class="flex flex-col items-center p-3 bg-surface-container-low rounded-lg border border-outline-variant/10 text-center">
                        <span class="font-caption text-caption text-secondary font-semibold uppercase">Berkas Diterima</span>
                        <span id="stat-accepted-val" class="font-headline-md text-headline-md text-secondary mt-1">0</span>
                    </div>
                </div>
            </div>

            <!-- Right Card: Bar Chart (Top 10 Instansi Pendaftar Terbanyak) -->
            <div class="lg:col-span-3 bg-surface-container-low/40 rounded-xl p-6 border border-outline-variant/20 flex flex-col gap-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-col gap-1">
                        <h3 class="font-title-lg text-title-lg text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">leaderboard</span>
                            Top 10 Instansi Pendaftar Terbanyak
                        </h3>
                        <p class="font-caption text-caption text-on-surface-variant">10 Perangkat Daerah dan Kecamatan dengan jumlah pendaftar tertinggi.</p>
                    </div>
                    <!-- Right Action: Lihat Selengkapnya Text Link -->
                    <a href="{{ route('landing.statistik_instansi') }}" class="group inline-flex items-center gap-1.5 text-secondary dark:text-primary hover:text-primary dark:hover:text-primary/80 font-bold text-xs sm:text-sm transition-all self-start md:self-auto py-1 hover:underline">
                        <span>Lihat Selengkapnya</span>
                        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
                    </a>
                </div>

                <!-- Chart Area -->
                <div class="flex-grow min-h-[300px] flex items-center justify-center">
                    <div id="bar-chart-container" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Peserta Magang Diterima -->
<section class="max-w-container-max mx-auto px-margin-desktop py-16 w-full">
<div class="flex flex-col gap-8">
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-stack-lg">
    <div class="flex flex-col gap-2">
        <h2 class="font-headline-lg text-headline-lg text-on-surface">Peserta Magang Diterima</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Daftar peserta yang telah diterima untuk mengikuti program magang di lingkungan Pemerintah Kabupaten Bogor.</p>
    </div>
    <a href="{{ route('landing.peserta') }}" class="group inline-flex items-center gap-2 px-4 py-2 bg-surface-container-low hover:bg-surface-container text-primary font-label-md text-label-md font-semibold rounded-lg transition-all border border-outline-variant/30 hover:border-primary shadow-sm hover:shadow">
        <span>Lihat Selengkapnya</span>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
    </a>
</div>

<div class="bg-surface-container-lowest rounded-xl shadow-ambient overflow-hidden border border-outline-variant/30">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse min-w-[1000px]">
<thead>
    <tr class="bg-surface-container-low text-on-surface-variant font-label-md text-xs uppercase tracking-wider border-b border-outline-variant/30">
        <th class="px-6 py-4 whitespace-nowrap min-w-[200px]">Nama Peserta</th>
        <th class="px-6 py-4 whitespace-nowrap min-w-[220px]">Asal Lembaga & Jurusan</th>
        <th class="px-6 py-4 whitespace-nowrap min-w-[260px]">Tujuan Dinas & Bidang</th>
        <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Tanggal Diterima</th>
        <th class="px-6 py-4 whitespace-nowrap min-w-[160px]">Tanggal Berakhir</th>
        <th class="px-6 py-4 text-center whitespace-nowrap min-w-[130px]">Status</th>
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
        <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">
            Belum ada data peserta diterima.
        </td>
    </tr>
    @endforelse
</tbody>
</table>
</div>
</div>
</div>
</section>

<!-- Instansi Tujuan Magang -->
<section id="instansi-section" class="max-w-container-max mx-auto px-margin-desktop py-16 w-full">
<div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-stack-lg">
<div class="flex flex-col gap-2">
<h2 class="font-headline-lg text-headline-lg text-on-surface">Instansi Tujuan Magang</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Pilih instansi tujuan magang yang sesuai dengan minat Anda. Memprioritaskan instansi dengan kuota yang tersedia.</p>
</div>
<a href="{{ route('landing.instansi') }}" class="group inline-flex items-center gap-2 px-4 py-2 bg-surface-container-low hover:bg-surface-container text-primary font-label-md text-label-md font-semibold rounded-lg transition-all border border-outline-variant/30 hover:border-primary shadow-sm hover:shadow">
    <span>Lihat Selengkapnya</span>
    <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-hover:translate-x-1">arrow_forward</span>
</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-container">
@forelse($featuredInstansis as $instansi)
<a class="group block bg-surface-container-lowest rounded-xl p-6 shadow-level-1 shadow-level-1-hover border-t-4 {{ $instansi->sisa_kuota > 0 ? 'border-emerald-500' : 'border-outline-variant' }} hover:border-primary transition-colors duration-300 relative overflow-hidden flex flex-col h-full border border-x-outline-variant/30 border-b-outline-variant/30 reveal-element hover-card-trigger" href="{{ route('landing.instansi_detail', $instansi->hashid) }}">
<div class="flex justify-between items-start mb-4">
  <div class="w-16 h-16 bg-surface-container flex items-center justify-center rounded-lg border border-outline-variant/20">
    <span class="material-symbols-outlined text-[32px] text-primary group-hover:scale-110 transition-transform">
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
<p class="font-body-md text-body-md text-on-surface-variant mb-4 flex-grow line-clamp-3">
    {{ $instansi->deskripsi }}
</p>

<div class="mt-auto flex items-center justify-between pt-4 border-t border-surface-container-high">
  @if($instansi->sisa_kuota > 0)
    <div class="flex items-center gap-1.5 px-3 py-1.5 bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] rounded-lg font-label-md text-caption font-bold">
      <span class="material-symbols-outlined text-[16px]">check_circle</span>
      <span>{{ $instansi->sisa_kuota }} Kuota Tersedia</span>
    </div>
  @else
    @php $badge = $instansi->status_badge; @endphp
    <div class="flex items-center gap-1 px-3 py-1.5 {{ $badge['bg_class'] }} rounded-lg font-label-md text-caption font-bold">
      <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
      <span>{{ $badge['label'] }}</span>
    </div>
  @endif
  <div class="py-2 px-4 bg-surface-container-low text-primary font-label-md text-label-md rounded-md hover:bg-surface-container transition-colors text-center inline-block">Lihat Detail</div>
</div>
</a>
@empty
<div class="col-span-1 md:col-span-2 lg:col-span-3 py-12 px-6 text-center bg-surface-container-lowest rounded-2xl border border-outline-variant/30 flex flex-col items-center justify-center">
    <div class="w-16 h-16 rounded-full bg-secondary/10 flex items-center justify-center text-secondary mb-4">
        <span class="material-symbols-outlined text-3xl">domain_disabled</span>
    </div>
    <h3 class="text-title-lg font-bold text-on-surface mb-2">Saat Ini Belum Ada Kuota Terbuka di Beranda</h3>
    <p class="text-body-md text-on-surface-variant max-w-lg mb-6">
        Instansi tujuan magang saat ini belum membuka slot baru atau kuota sedang terisi. Anda dapat melihat daftar lengkap seluruh instansi Pemerintah Kabupaten Bogor melalui tautan di bawah ini.
    </p>
    <a href="{{ route('landing.instansi') }}" class="px-5 py-2.5 bg-primary text-white rounded-xl font-label-md text-sm font-semibold inline-flex items-center gap-2 hover:opacity-90 transition-opacity">
        <span>Lihat Selengkapnya</span>
        <span class="material-symbols-outlined text-sm">arrow_forward</span>
    </a>
</div>
@endforelse
</div>

</section>
@endguest
@if(isset($userMagang) && $userMagang)
<!-- Jadwal Masuk Anak Magang di Bidang (Untuk User Diterima) -->
<section id="jadwal-magang-section" class="max-w-container-max mx-auto px-margin-desktop py-12 w-full">
    <div class="bg-surface-container-lowest rounded-2xl shadow-level-2 border border-outline-variant/30 overflow-hidden reveal-scale">
        <!-- Header Banner -->
        <div class="bg-[#115cb9] p-8 text-white relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                <span class="material-symbols-outlined text-[200px]">calendar_month</span>
            </div>
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold text-white uppercase tracking-wider w-max">
                        <span class="material-symbols-outlined text-[14px]">verified</span>
                        Status Peserta: Diterima & Aktif
                    </div>
                    <h2 class="text-2xl md:text-3xl font-extrabold text-white">
                        Jadwal Masuk Anak Magang — {{ $userMagang->bidang->name ?? ($userMagang->rekrutmen->bidang->name ?? ($userMagang->dinas->name ?? 'Bidang Penempatan')) }}
                    </h2>
                    <p class="text-white/80 text-sm md:text-base">
                        {{ $userMagang->dinas->name ?? ($userMagang->rekrutmen->dinas->name ?? 'Pemerintah Kabupaten Bogor') }}
                    </p>
                </div>
                
                <!-- Quick Absensi CTA Button -->
                <div class="shrink-0">
                    <a href="{{ route('peserta.dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-white text-primary hover:bg-surface-container font-bold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5">
                        <span class="material-symbols-outlined text-[20px]">fingerprint</span>
                        <span>Absen Hari Ini</span>
                    </a>
                </div>
            </div>
        </div>

        @if(isset($isApproaching3Months) && $isApproaching3Months)
        <!-- Warning Banner: Wajib Perpanjangan Surat Rekomendasi 3 Bulan -->
        <div class="p-6 bg-gradient-to-r from-amber-500/15 via-orange-500/10 to-amber-500/5 border-b border-amber-500/30">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md ring-4 ring-amber-100 dark:ring-amber-950/60 animate-bounce">
                        <span class="material-symbols-outlined text-[26px]">notification_important</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-800 dark:text-amber-400">Pemberitahuan Wajib Perpanjangan Surat</span>
                            <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $daysRemaining3Months <= 0 ? 'bg-red-500 text-white' : 'bg-amber-200 text-amber-900 dark:bg-amber-900/80 dark:text-amber-200' }}">
                                {{ $daysRemaining3Months <= 0 ? 'Batas 3 Bulan Telah Tercapai' : 'Mendekati Batas 3 Bulan (Sisa ' . $daysRemaining3Months . ' Hari)' }}
                            </span>
                        </div>
                        <h3 class="text-base font-extrabold text-on-surface dark:text-white mt-1">
                            Surat Rekomendasi Magang Memerlukan Perpanjangan Izin
                        </h3>
                        <p class="text-xs md:text-sm text-on-surface-variant dark:text-slate-300 mt-0.5 leading-relaxed">
                            Sesuai regulasi Badan Kesbangpol Kab. Bogor, masa berlaku Surat Rekomendasi adalah 3 bulan per periode (Jatuh tempo: <strong>{{ $batas3Bulan ? $batas3Bulan->locale('id')->translatedFormat('d F Y') : '-' }}</strong>). Anda wajib mengajukan perpanjangan izin agar masa magang dan kehadiran presensi tetap sah di instansi tujuan.
                        </p>
                    </div>
                </div>
                <div class="shrink-0 w-full md:w-auto">
                    <a href="{{ route('layanan.create', 'perpanjangan') }}" class="inline-flex items-center justify-center gap-2 w-full md:w-auto px-6 py-3.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[20px]">edit_calendar</span>
                        <span>Ajukan Perpanjangan Sekarang</span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Working Hours & Information Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-8 bg-surface-container-low/30 border-b border-outline-variant/20">
            <!-- Card 1: Working Hours -->
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/20 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px]">schedule</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Jadwal Masuk Kerja</span>
                    <span class="text-base font-bold text-on-surface mt-1">Senin – Jumat</span>
                    <span class="text-xs text-on-surface-variant font-medium">08.00 – 16.00 WIB</span>
                </div>
            </div>

            <!-- Card 2: Period -->
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/20 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px]">date_range</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Periode Magang</span>
                    <span class="text-sm md:text-base font-bold text-on-surface mt-1 whitespace-nowrap">
                        {{ $userMagang->tanggal_mulai ? $userMagang->tanggal_mulai->format('d M Y') : 'Mulai' }} s.d. {{ $userMagang->tanggal_selesai ? $userMagang->tanggal_selesai->format('d M Y') : 'Selesai' }}
                    </span>
                    <span class="text-xs text-on-surface-variant font-medium">
                        Masa Pelaksanaan Kegiatan
                    </span>
                </div>
            </div>

            <!-- Card 3: Total Teammates -->
            <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/20 flex items-start gap-4 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-tertiary/10 text-tertiary flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[26px]">groups</span>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-bold uppercase tracking-wider text-on-surface-variant">Rekan Magang Se-Bidang</span>
                    @php
                        $namaBidang = $userMagang->bidang->name ?? ($userMagang->rekrutmen->bidang->name ?? 'Terkait');
                        $teksBidang = preg_match('/^bidang\b/i', trim($namaBidang)) ? $namaBidang : 'Bidang ' . $namaBidang;
                    @endphp
                    <span class="text-xs text-on-surface-variant font-medium">{{ $teksBidang }}</span>
                </div>
            </div>
        </div>

        <!-- Participant List Table -->
        <div class="p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-on-surface">Daftar & Kehadiran Peserta Magang di Bidang Ini</h3>
                    <p class="text-xs text-on-surface-variant">Informasi presensi hari ini ({{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }})</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container text-on-surface rounded-full text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Update
                </span>
            </div>

            <div class="overflow-x-auto rounded-xl border border-outline-variant/20">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-bold uppercase tracking-wider text-on-surface-variant border-b border-outline-variant/20">
                            <th class="p-4 pl-6">Nama Peserta</th>
                            <th class="p-4">Asal Instansi / Universitas</th>
                            <th class="p-4 text-center">Status Kehadiran Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10 text-sm">
                        @forelse($bidangParticipants as $p)
                        @php
                            $todayAbsensi = $p->absensis->first();
                            $namaPeserta = $p->permohonanLayanan->atas_nama ?? ($p->user->nama ?: ($p->user->name ?? '-'));
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors {{ $p->user_id === auth()->id() ? 'bg-primary/5 font-semibold' : '' }}">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-2">
                                    <span class="text-on-surface font-bold">
                                        {{ $namaPeserta }}
                                    </span>
                                    @if($p->user_id === auth()->id())
                                        <span class="text-[10px] px-2 py-0.5 bg-primary text-white rounded-full font-bold">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                {{ $p->permohonanLayanan->asal_instansi ?? ($p->user->asal_instansi ?? 'Umum') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($todayAbsensi)
                                    @if(in_array(strtolower($todayAbsensi->status), ['hadir', 'wfo', 'wfh']))
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 rounded-full text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                            Hadir
                                        </span>
                                    @elseif(strtolower($todayAbsensi->status) === 'izin')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-950/60 dark:text-blue-300 rounded-full text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">info</span>
                                            Izin
                                        </span>
                                    @elseif(strtolower($todayAbsensi->status) === 'sakit')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-orange-100 text-orange-800 dark:bg-orange-950/60 dark:text-orange-300 rounded-full text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">medical_services</span>
                                            Sakit
                                        </span>
                                    @elseif(strtolower($todayAbsensi->status) === 'alpha')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 text-red-800 dark:bg-red-950/60 dark:text-red-300 rounded-full text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">cancel</span>
                                            Alpha
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-800 dark:bg-gray-800/60 dark:text-gray-300 rounded-full text-xs font-bold">
                                            <span class="material-symbols-outlined text-[14px]">help</span>
                                            {{ ucfirst($todayAbsensi->status) }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300 rounded-full text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px]">schedule</span>
                                        Belum Absen Hari Ini
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="p-6 text-center text-on-surface-variant">
                                Belum ada peserta lain di bidang ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endif
</main>




<!-- MODALS -->


<div id="modal_penelitian_pt" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_penelitian_pt" onclick="closeModal('penelitian_pt')"></div>
    
    <!-- Modal Content Container (With padding top to clear navbar) -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_penelitian_pt" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight">Penelitian / Pengambilan Data / Wawancara / Survey</h3>
                <button type="button" onclick="closeModal('penelitian_pt')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Institusi Pendidikan (Kampus / Universitas / Perguruan Tinggi) yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor dan melampirkan nama peserta. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong> (contoh: 7 Juli s.d 7 September), MAKSIMAL 3 BULAN.</li>
                        <li>KTP dan Kartu Mahasiswa.</li>
                        <li>Proposal Penelitian / Pengambilan Data / Wawancara / Survey.</li>
                        <li>Surat Dari Lokasi Penelitian Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Cap Stempel).</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('penelitian_pt')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'penelitian_pt') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_penelitian_instansi" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_penelitian_instansi" onclick="closeModal('penelitian_instansi')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_penelitian_instansi" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight">Penelitian / Pengambilan Data / Wawancara / Survey</h3>
                <button type="button" onclick="closeModal('penelitian_instansi')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Instansi / Lembaga yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>KTP.</li>
                        <li>Surat Keterangan Penelitian dari DPMPTSP Provinsi Jawa Barat.</li>
                        <li>Surat Keterangan Penelitian dari Dirjen Polpum Kemendagri.</li>
                        <li>Surat Dari Lokasi Kegiatan Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Stempel).</li>
                        <li>Proposal Penelitian / Pengambilan Data / Survey / Pelaksanaan Kegiatan.</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('penelitian_instansi')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'penelitian_instansi') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_kkl_mahasiswa" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_kkl_mahasiswa" onclick="closeModal('kkl_mahasiswa')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_kkl_mahasiswa" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight uppercase">KKL / PKL / MAGANG (MAHASISWA)</h3>
                <button type="button" onclick="closeModal('kkl_mahasiswa')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Institusi Pendidikan (Kampus / Universitas) yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor dan melampirkan nama peserta. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>KTP dan Kartu Mahasiswa.</li>
                        <li>Surat Dari Lokasi PKL / KKL Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Stempel).</li>
                        <li>Proposal Magang / PKL / KKL.</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('kkl_mahasiswa')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'kkl_mahasiswa') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_kkn_mahasiswa" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_kkn_mahasiswa" onclick="closeModal('kkn_mahasiswa')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_kkn_mahasiswa" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight uppercase">KKN MAHASISWA</h3>
                <button type="button" onclick="closeModal('kkn_mahasiswa')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Kampus yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>KTP (Pembimbing &amp; Mahasiswa KKN).</li>
                        <li>Kartu Mahasiswa.</li>
                        <li>Proposal atau Lampiran Kegiatan KKN.</li>
                        <li>Surat Dari Lokasi KKN Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Stempel).</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('kkn_mahasiswa')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'kkn_mahasiswa') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_kkl_siswa" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_kkl_siswa" onclick="closeModal('kkl_siswa')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_kkl_siswa" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight uppercase">PKL / MAGANG (SISWA SEKOLAH)</h3>
                <button type="button" onclick="closeModal('kkl_siswa')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Sekolah yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>KTP Penanggung Jawab / Pembimbing.</li>
                        <li>Kartu Pelajar.</li>
                        <li>Surat Dari Lokasi PKL Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Stempel).</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('kkl_siswa')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'kkl_siswa') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_pelaksanaan_kegiatan" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_pelaksanaan_kegiatan" onclick="closeModal('pelaksanaan_kegiatan')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_pelaksanaan_kegiatan" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight uppercase">PELAKSANAAN KEGIATAN</h3>
                <button type="button" onclick="closeModal('pelaksanaan_kegiatan')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Institusi Pendidikan yang ditujukan Kepada Kepala Badan Kesbangpol Kabupaten Bogor. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>KTP (Pembimbing &amp; Mahasiswa).</li>
                        <li>Kartu Mahasiswa.</li>
                        <li>Proposal / Lampiran Pelaksanaan Kegiatan.</li>
                        <li>Surat Dari Lokasi Pelaksanaan Kegiatan Harus Memiliki Tanda Terima Koordinasi (Paraf/Tandatangan &amp; Stempel).</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('pelaksanaan_kegiatan')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'pelaksanaan_kegiatan') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>

<div id="modal_perpanjangan" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity opacity-0" id="backdrop_perpanjangan" onclick="closeModal('perpanjangan')"></div>
    
    <!-- Modal Content Container -->
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 pt-20 pb-6 overflow-y-auto pointer-events-none">
        <div id="modal_box_perpanjangan" class="relative bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:max-w-lg w-full opacity-0 translate-y-4 sm:scale-95 duration-300 pointer-events-auto border border-gray-100 my-auto">
            <!-- Header -->
            <div class="px-6 py-4 bg-gradient-to-r from-[#1f477b] to-[#115cb9] flex items-center justify-between">
                <h3 class="text-base md:text-lg font-bold text-white leading-tight uppercase">PERPANJANGAN IZIN REKOMENDASI</h3>
                <button type="button" onclick="closeModal('perpanjangan')" class="text-white/80 hover:text-white transition-colors p-1 rounded-lg">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Body -->
            <div class="px-6 py-5 max-h-[55vh] overflow-y-auto space-y-4 text-sm">
                <p class="text-gray-600 font-medium leading-relaxed text-xs md:text-sm">Sebelum melanjutkan pengajuan, mohon pastikan dokumen Anda memenuhi persyaratan berikut:</p>
                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <ul class="list-disc pl-4 space-y-2.5 marker:text-[#115cb9] text-gray-700 text-xs md:text-sm leading-relaxed">
                        <li>Surat Pengantar / Permohonan Asli dari Institusi / Kampus / Sekolah. <strong class="text-gray-900">WAJIB MELAMPIRKAN WAKTU PELAKSANAAN</strong>, MAKSIMAL 3 BULAN.</li>
                        <li>Surat Izin Rekomendasi Sebelumnya yang dikeluarkan oleh Bakesbangpol Kab. Bogor.</li>
                        <li>Surat Rekomendasi harus dilokasi yang sama. Jika ada perubahan lokasi, diharuskan melakukan permohonan pengajuan baru.</li>
                    </ul>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="bg-gray-50/80 px-6 py-4 flex flex-col sm:flex-row justify-end items-center gap-3 border-t border-gray-100">
                <button type="button" onclick="closeModal('perpanjangan')" class="w-full sm:w-auto px-5 py-2.5 rounded-xl font-bold text-gray-700 bg-white border border-gray-300 hover:bg-gray-100 transition-all text-xs md:text-sm">Tutup</button>
                <a href="{{ route('layanan.create', 'perpanjangan') }}" class="w-full sm:w-auto px-6 py-2.5 rounded-xl font-bold text-white shadow-md hover:shadow-lg transition-all text-center text-xs md:text-sm bg-gradient-to-r from-[#1f477b] to-[#115cb9] hover:opacity-95">Lanjutkan untuk Mengajukan</a>
            </div>
        </div>
    </div>
</div>



<!-- Modal Script -->
<script>
    function toggleLayananDropdown(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        }
        const menu = document.getElementById('dropdown-layanan-menu');
        const arrow = document.getElementById('arrow-dropdown-layanan');
        const btn = document.getElementById('btn-dropdown-layanan');
        if (!menu) return;

        const isOpen = menu.classList.contains('show');
        if (isOpen) {
            closeLayananDropdown();
        } else {
            menu.classList.add('show');
            if (arrow) arrow.classList.add('open');
            if (btn) btn.setAttribute('aria-expanded', 'true');
        }
    }

    function closeLayananDropdown() {
        const menu = document.getElementById('dropdown-layanan-menu');
        const arrow = document.getElementById('arrow-dropdown-layanan');
        const btn = document.getElementById('btn-dropdown-layanan');
        if (menu) menu.classList.remove('show');
        if (arrow) arrow.classList.remove('open');
        if (btn) btn.setAttribute('aria-expanded', 'false');
    }

    // Close on click outside
    document.addEventListener('click', function(e) {
        const btn = document.getElementById('btn-dropdown-layanan');
        const menu = document.getElementById('dropdown-layanan-menu');
        if (menu && menu.classList.contains('show')) {
            if (!menu.contains(e.target) && (!btn || !btn.contains(e.target))) {
                closeLayananDropdown();
            }
        }
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLayananDropdown();
        }
    });

    function openModal(id) {
        const modal = document.getElementById('modal_' + id);
        const backdrop = document.getElementById('backdrop_' + id);
        const modalBox = document.getElementById('modal_box_' + id);
        
        // Show container
        modal.classList.remove('hidden');
        
        // Trigger animations
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            modalBox.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            modalBox.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 10);
        
        // Prevent scrolling on body
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal(id) {
        const modal = document.getElementById('modal_' + id);
        const backdrop = document.getElementById('backdrop_' + id);
        const modalBox = document.getElementById('modal_box_' + id);
        
        // Trigger exit animations
        backdrop.classList.add('opacity-0');
        modalBox.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        modalBox.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        
        // Hide container after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    function openEditModal(id) {
        const modal = document.getElementById('edit_modal_' + id);
        const backdrop = document.getElementById('edit_backdrop_' + id);
        const modalBox = document.getElementById('edit_modal_box_' + id);
        if (!modal) {
            console.error('Modal element not found for id:', id);
            return;
        }
        
        modal.classList.remove('hidden');
        modal.style.display = 'block';
        requestAnimationFrame(() => {
            if (backdrop) backdrop.classList.remove('opacity-0');
            if (modalBox) {
                modalBox.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                modalBox.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }
        });
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal(id) {
        const modal = document.getElementById('edit_modal_' + id);
        const backdrop = document.getElementById('edit_backdrop_' + id);
        const modalBox = document.getElementById('edit_modal_box_' + id);
        if (!modal) return;
        
        if (backdrop) backdrop.classList.add('opacity-0');
        if (modalBox) {
            modalBox.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            modalBox.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        }
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
        }, 300);
    }

    function confirmDeletePermohonan(form) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Hapus Permohonan?',
                text: 'Apakah Anda yakin ingin membatalkan dan menghapus permohonan surat ini? Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl border-0 shadow-2xl p-6',
                    title: 'text-lg font-bold text-gray-800',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
            return false;
        } else {
            if (confirm('Apakah Anda yakin ingin membatalkan dan menghapus permohonan surat ini?')) {
                form.submit();
            }
            return false;
        }
    }

    // Scroll and Stagger Animations via Intersection Observer
    document.addEventListener('DOMContentLoaded', () => {
        // Trigger Hero immediately on page load
        setTimeout(() => {
            const heroElements = document.querySelectorAll('.stagger-container .reveal-element');
            heroElements.forEach((el, index) => {
                setTimeout(() => {
                    el.classList.add('active');
                }, index * 100);
            });
        }, 100);

        // Stagger container observer
        const staggerContainers = document.querySelectorAll('.stagger-container');
        staggerContainers.forEach(container => {
            // skip hero container since it is triggered immediately
            if (container.closest('section').classList.contains('relative') && container.closest('section').classList.contains('min-h-[600px]')) {
                return;
            }
            const children = container.querySelectorAll('.reveal-element, .reveal-scale, .reveal-element-left, .reveal-element-right');
            const containerObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        children.forEach((child, index) => {
                            setTimeout(() => {
                                child.classList.add('active');
                            }, index * 100);
                        });
                        containerObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -50px 0px' });
            containerObserver.observe(container);
        });

        // Individual elements observer
        const revealElements = document.querySelectorAll('.reveal-element:not(.stagger-container *), .reveal-scale:not(.stagger-container *), .reveal-element-left:not(.stagger-container *), .reveal-element-right:not(.stagger-container *)');
        const elementObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    elementObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
        
        revealElements.forEach(el => elementObserver.observe(el));

        // Horizontal scrolling for services cards
        const container = document.getElementById('services-scroll-container');
        const leftBtn = document.getElementById('slide-left-btn');
        const rightBtn = document.getElementById('slide-right-btn');

        if (container && leftBtn && rightBtn) {
            const scrollAmount = () => {
                const firstCard = container.querySelector('.hover-card-trigger');
                if (!firstCard) return 350;
                const cardWidth = firstCard.offsetWidth + 24; // card width + gap
                if (window.innerWidth >= 1024) return cardWidth * 3;
                if (window.innerWidth >= 640) return cardWidth * 2;
                return cardWidth;
            };

            leftBtn.addEventListener('click', () => {
                container.scrollBy({ left: -scrollAmount(), behavior: 'smooth' });
            });

            rightBtn.addEventListener('click', () => {
                container.scrollBy({ left: scrollAmount(), behavior: 'smooth' });
            });

            const updateButtonState = () => {
                const isAtStart = container.scrollLeft <= 5;
                const isAtEnd = container.scrollLeft + container.clientWidth >= container.scrollWidth - 5;
                
                leftBtn.style.opacity = isAtStart ? '0.4' : '1';
                leftBtn.style.cursor = isAtStart ? 'default' : 'pointer';
                
                rightBtn.style.opacity = isAtEnd ? '0.4' : '1';
                rightBtn.style.cursor = isAtEnd ? 'default' : 'pointer';
            };

            container.addEventListener('scroll', updateButtonState);
            // Run initially
            setTimeout(updateButtonState, 150);
        }

        // Charts and interactive logic
        const serviceStats = @json($chartData);
        const dayNames = @json($pastDayNames);
        let donutChart = null;
        let barChart = null;

        function highlightServiceCard(serviceName) {
            // Remove active style from all cards
            document.querySelectorAll('.service-card').forEach(card => {
                card.classList.remove('border-[#115cb9]', 'ring-2', 'ring-[#115cb9]/20');
            });
            // Add active style to matching card
            const activeCard = document.querySelector(`.service-card[data-service="${serviceName}"]`);
            if (activeCard) {
                activeCard.classList.add('border-[#115cb9]', 'ring-2', 'ring-[#115cb9]/20');
            }
        }

        let currentServiceData = null;
        const serviceSelector = document.getElementById('service-selector');
        const activeServiceText = document.getElementById('active-service-text');

        // Render Service Charts (Donut & Top 10 Instansi)
        function renderChartsWithData(serviceName, data) {
            if (!data) return;

            highlightServiceCard(serviceName);

            const registered = Number(data.registered || 0);
            const accepted = Number(data.accepted || 0);
            const processOrOther = Math.max(0, registered - accepted);

            // Update stats summary in HTML
            const regEl = document.getElementById('stat-registered-val');
            const accEl = document.getElementById('stat-accepted-val');
            if (regEl) regEl.textContent = registered.toLocaleString('id-ID');
            if (accEl) accEl.textContent = accepted.toLocaleString('id-ID');

            const isDark = document.documentElement.classList.contains('dark');
            const chartLabelColor = isDark ? '#f8fafc' : '#1e293b';
            const chartSubColor = isDark ? '#cbd5e1' : '#64748b';
            const chartGridColor = isDark ? 'rgba(255, 255, 255, 0.08)' : 'rgba(0, 0, 0, 0.06)';

            // Donut Chart Config
            const donutSeries = registered > 0 ? [processOrOther, accepted] : [0, 0];

            const donutOptions = {
                series: donutSeries,
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },
                labels: ['Sedang Diproses / Lainnya', 'Diterima'],
                colors: ['#f59e0b', '#10b981'],
                legend: {
                    show: true,
                    position: 'bottom',
                    labels: {
                        colors: 'var(--tw-on-surface)'
                    }
                },
                dataLabels: {
                    enabled: registered > 0,
                    style: {
                        fontSize: '11px',
                        fontWeight: '700',
                        colors: ['#ffffff']
                    },
                    dropShadow: {
                        enabled: true,
                        top: 1,
                        left: 1,
                        blur: 1,
                        opacity: 0.5
                    },
                    formatter: function (val) {
                        return val.toFixed(1) + "%";
                    }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function (value) {
                            return value + " Berkas";
                        }
                    }
                },
                noData: {
                    text: 'Belum ada berkas terdaftar pada layanan ini',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        color: chartSubColor,
                        fontSize: '12px',
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '52%',
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Terdaftar',
                                    color: 'var(--tw-on-surface-variant)',
                                    fontSize: '12px',
                                    fontWeight: '600',
                                    formatter: function () {
                                        return registered.toLocaleString('id-ID');
                                    }
                                },
                                value: {
                                    color: 'var(--tw-on-surface)',
                                    fontSize: '20px',
                                    fontWeight: '700',
                                    formatter: function (val) {
                                        return val;
                                    }
                                }
                            }
                        }
                    }
                }
            };

            // Bar Chart Config: Top 10 Dinas Terbanyak Pendaftarnya
            const topDinasData = data.top_dinas || { categories: [], full_names: [], series: [] };

            const top10Colors = [
                '#10b981', '#3b82f6', '#6366f1', '#8b5cf6', '#0ea5e9',
                '#06b6d4', '#14b8a6', '#f59e0b', '#84cc16', '#64748b'
            ];

            const barOptions = {
                series: [{
                    name: 'Jumlah Pendaftar',
                    data: topDinasData.series || []
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },
                colors: top10Colors.slice(0, Math.max(1, (topDinasData.series || []).length)),
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        columnWidth: '55%',
                        distributed: true,
                        dataLabels: {
                            position: 'top'
                        }
                    }
                },
                dataLabels: {
                    enabled: true,
                    offsetY: -20,
                    style: {
                        fontSize: '11px',
                        fontWeight: '700',
                        colors: [chartLabelColor]
                    },
                    formatter: function (val) {
                        return val > 0 ? val : '';
                    }
                },
                legend: { show: false },
                noData: {
                    text: 'Belum ada pendaftar pada instansi untuk layanan ini',
                    align: 'center',
                    verticalAlign: 'middle',
                    style: {
                        color: chartSubColor,
                        fontSize: '12px',
                        fontFamily: 'Plus Jakarta Sans, sans-serif'
                    }
                },
                xaxis: {
                    categories: topDinasData.categories || [],
                    labels: {
                        rotate: -35,
                        rotateAlways: false,
                        style: {
                            colors: Array((topDinasData.categories || []).length).fill(chartLabelColor),
                            fontSize: '11px',
                            fontWeight: 600
                        },
                        trim: true,
                        maxHeight: 75
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: chartSubColor },
                        formatter: function (val) { return Math.round(val); }
                    }
                },
                grid: {
                    borderColor: chartGridColor,
                    opacity: 0.1,
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    custom: function({ series, seriesIndex, dataPointIndex }) {
                        const count = series[seriesIndex][dataPointIndex];
                        const shortName = topDinasData.categories[dataPointIndex] || '';
                        const fullName = (topDinasData.full_names && topDinasData.full_names[dataPointIndex]) 
                            ? topDinasData.full_names[dataPointIndex] 
                            : shortName;
                        const rank = dataPointIndex + 1;
                        return `
                            <div style="padding: 10px 14px; font-family: 'Plus Jakarta Sans', sans-serif;" class="text-xs shadow-xl rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100">
                                <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 4px;">
                                    <span style="background: #10b981; color: white; padding: 1px 6px; border-radius: 4px; font-size: 10px; font-weight: 700;">#${rank}</span>
                                    <span style="font-weight: 700; font-size: 12px; color: #10b981;">${shortName}</span>
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-bottom: 6px; max-width: 240px; line-height: 1.3;">${fullName}</div>
                                <div style="border-top: 1px solid rgba(148, 163, 184, 0.2); padding-top: 6px; display: flex; justify-content: space-between; gap: 12px; font-weight: 600;">
                                    <span style="color: #64748b;">Jumlah Pendaftar:</span>
                                    <span style="font-weight: 700; color: #0f766e;">${count} Orang</span>
                                </div>
                            </div>
                        `;
                    }
                }
            };

            if (donutChart) {
                donutChart.updateOptions(donutOptions);
            } else {
                donutChart = new ApexCharts(document.querySelector("#donut-chart-container"), donutOptions);
                donutChart.render();
            }

            if (barChart) {
                barChart.updateOptions(barOptions);
            } else {
                barChart = new ApexCharts(document.querySelector("#bar-chart-container"), barOptions);
                barChart.render();
            }
        }

        // Apply Service Filter
        async function applyServiceFilter(service) {
            if (activeServiceText) {
                activeServiceText.textContent = service;
            }
            highlightServiceCard(service);

            if (serviceStats && serviceStats[service]) {
                currentServiceData = serviceStats[service];
                renderChartsWithData(service, currentServiceData);
                return;
            }

            try {
                const res = await fetch(`{{ route('landing.chart_data') }}?service=${encodeURIComponent(service)}`, {
                    headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
                });
                const json = await res.json();
                if (json && json.status === 'success' && json.data) {
                    currentServiceData = json.data;
                    renderChartsWithData(service, currentServiceData);
                }
            } catch (err) {
                console.error('Failed to load service chart data:', err);
            }
        }



        // Initialize Services Chart
        if (serviceSelector) {
            currentServiceData = serviceStats[serviceSelector.value];
            renderChartsWithData(serviceSelector.value, currentServiceData);

            serviceSelector.addEventListener('change', function() {
                applyServiceFilter(this.value);
            });
        }

        // Re-render all charts when theme switches between light and dark
        const indexThemeObserver = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                if (mutation.attributeName === 'class') {
                    if (serviceSelector && currentServiceData) {
                        renderChartsWithData(serviceSelector.value, currentServiceData);
                    }
                }
            }
        });
        indexThemeObserver.observe(document.documentElement, { attributes: true });

        // Handle Card Clicks in services list
        document.querySelectorAll('.service-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // If the user clicked the button "Lihat Persyaratan", don't scroll or intercept
                if (e.target.closest('button')) {
                    return;
                }
                const serviceName = this.getAttribute('data-service');
                if (serviceName && serviceSelector) {
                    serviceSelector.value = serviceName;
                    applyServiceFilter(serviceName);
                    // Smooth scroll to analytics section
                    const targetSection = document.getElementById('analytics-section');
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });

        // Dynamic Hero Buttons Click Impulse Ripple Effect
        document.querySelectorAll('.hero-btn-primary, .hero-btn-secondary').forEach(btn => {
            btn.addEventListener('click', function(e) {
                const rect = this.getBoundingClientRect();
                const circle = document.createElement('span');
                const diameter = Math.max(rect.width, rect.height);
                const radius = diameter / 2;

                circle.style.width = circle.style.height = `${diameter}px`;
                circle.style.left = `${e.clientX - rect.left - radius}px`;
                circle.style.top = `${e.clientY - rect.top - radius}px`;
                circle.style.position = 'absolute';
                circle.style.borderRadius = '50%';
                circle.style.backgroundColor = 'rgba(255, 255, 255, 0.45)';
                circle.style.transform = 'scale(0)';
                circle.style.pointerEvents = 'none';
                circle.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';
                circle.style.zIndex = '10';

                this.appendChild(circle);

                requestAnimationFrame(() => {
                    circle.style.transform = 'scale(2.5)';
                    circle.style.opacity = '0';
                });

                setTimeout(() => {
                    circle.remove();
                }, 600);
            });
        });

        // Form Date Validation for Tanggal Mulai dan Selesai
        document.addEventListener('change', function(e) {
            if (e.target && e.target.name === 'tanggal_mulai') {
                const form = e.target.closest('form');
                if (form) {
                    const tglSelesai = form.querySelector('input[name="tanggal_selesai"]');
                    if (tglSelesai) {
                        tglSelesai.min = e.target.value;
                        if (tglSelesai.value && tglSelesai.value < e.target.value) {
                            tglSelesai.value = e.target.value;
                        }
                    }
                }
            }
        });
    });

    // Global switcher for Application Stepper View
    window.changeStepperApplication = function(appId) {
        const select = document.getElementById('stepper-app-select');
        if (select && select.value != appId) {
            select.value = appId;
        }
        document.querySelectorAll('.app-stepper-view').forEach(function(el) {
            el.classList.add('hidden');
        });
        const target = document.getElementById('app-stepper-view-' + appId);
        if (target) {
            target.classList.remove('hidden');
        }
    };
</script>
@endsection















