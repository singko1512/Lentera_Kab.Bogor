@extends('pelayanan.layouts.app')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@600;700&amp;display=swap" rel="stylesheet"/>
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
        /* Hero Action Buttons Styling & Shimmer Animation */
        .hero-btn-primary {
            background: linear-gradient(135deg, #115cb9 0%, #1d4ed8 50%, #2563eb 100%);
            box-shadow: 0 10px 25px -5px rgba(29, 78, 216, 0.5), inset 0 1px 1px rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .hero-btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.35), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hero-btn-primary:hover::before {
            left: 100%;
        }

        .hero-btn-primary:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 16px 32px -6px rgba(29, 78, 216, 0.65), 0 0 20px rgba(59, 130, 246, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .hero-btn-secondary {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        .hero-btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.25), transparent);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hero-btn-secondary:hover::before {
            left: 100%;
        }

        .hero-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-4px) scale(1.03);
            border-color: rgba(255, 255, 255, 0.6);
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25), 0 0 15px rgba(255, 255, 255, 0.2);
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
    <!-- Button 1: Data Layanan -->
    <a href="#analytics-section" class="hero-btn-primary group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-white font-semibold text-base transition-all duration-300 relative overflow-hidden shadow-lg">
        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12">monitoring</span>
        <span>Data Layanan</span>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:translate-y-1">arrow_downward</span>
    </a>

    <!-- Button 2: Peserta Magang -->
    <a href="{{ route('landing.peserta') }}" class="hero-btn-secondary group inline-flex items-center gap-2.5 px-7 py-3.5 rounded-full text-white font-semibold text-base transition-all duration-300 relative overflow-hidden">
        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 group-hover:scale-110">groups</span>
        <span>Peserta Magang</span>
        <span class="material-symbols-outlined text-[18px] transition-transform duration-300 group-hover:translate-x-1.5">arrow_forward</span>
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
                <button onclick="toggleLayananDropdown(event)" type="button" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-primary text-white font-label-md text-label-md font-semibold rounded-xl hover:bg-secondary transition-all shadow-md hover:shadow-lg cursor-pointer">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    <span>Tambah Layanan Baru</span>
                    <span class="material-symbols-outlined text-[18px]">expand_more</span>
                </button>

                <!-- Dropdown Menu List Service Forms -->
                <div id="dropdown-layanan-menu" class="hidden absolute right-0 top-full mt-2 w-80 rounded-xl bg-white shadow-2xl border border-outline-variant/30 py-2 z-[100] transition-all duration-200">
                    <div class="px-4 py-2 text-[11px] font-bold uppercase tracking-wider text-outline border-b border-outline-variant/20 mb-1">
                        Pilih Form Jenis Layanan:
                    </div>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('penelitian_pt')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">school</span>
                        <span>Penelitian (Perguruan Tinggi)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('penelitian_instansi')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">business</span>
                        <span>Penelitian (Instansi / Lembaga)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkl_mahasiswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">work</span>
                        <span>KKL / PKL / Magang (Mahasiswa)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkn_mahasiswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">groups</span>
                        <span>KKN Mahasiswa</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('kkl_siswa')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">backpack</span>
                        <span>KKL / PKL / Magang (Siswa Sekolah)</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('pelaksanaan_kegiatan')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors">
                        <span class="material-symbols-outlined text-primary text-[20px]">event</span>
                        <span>Pelaksanaan Kegiatan</span>
                    </a>
                    <a href="javascript:void(0)" onclick="closeLayananDropdown(); openModal('perpanjangan')" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium text-gray-800 hover:bg-surface-container-low hover:text-primary transition-colors border-t border-outline-variant/20 mt-1 pt-2">
                        <span class="material-symbols-outlined text-primary text-[20px]">update</span>
                        <span>Perpanjangan Izin Rekomendasi</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        @if(isset($userApplications) && $userApplications->count() > 0)
        <div class="overflow-x-auto rounded-xl border border-outline-variant/30">
            <table class="w-full text-left border-collapse">
                <thead class="bg-surface-container-low text-on-surface font-label-md text-label-md">
                    <tr>
                        <th class="p-4 pl-6">No / Tgl Pengajuan</th>
                        <th class="p-4">Jenis Layanan</th>
                        <th class="p-4">Instansi / Topik</th>
                        <th class="p-4 text-center">Status</th>
                        <th class="p-4 pr-6 text-center">Aksi / Dokumen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 font-body-md text-body-md text-on-surface">
                    @foreach($userApplications as $index => $app)
                    <tr class="hover:bg-surface-container-low/40 transition-colors">
                        <td class="p-4 pl-6">
                            <div class="font-bold text-on-surface">#{{ $app->id }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $app->created_at ? $app->created_at->format('d M Y, H:i') : '-' }}</div>
                        </td>
                        <td class="p-4 font-semibold text-primary">
                            {{ $app->jenisLayanan->nama ?? $app->jenis_permohonan ?? 'Permohonan Rekomendasi' }}
                        </td>
                        <td class="p-4">
                            <div class="font-medium text-gray-800">{{ $app->asal_instansi ?? '-' }}</div>
                            <div class="text-xs text-on-surface-variant truncate max-w-xs">{{ $app->judul_kegiatan ?? '-' }}</div>
                        </td>
                        <td class="p-4 text-center">
                            @php
                                $statusKode = strtolower(optional($app->statusMaster)->kode ?? 'proses');
                                $statusNama = optional($app->statusMaster)->nama ?? 'Dalam Proses';
                            @endphp
                            @if($statusKode == 'disetujui' || $statusKode == 'selesai')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] rounded-full text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    <span>{{ $statusNama }}</span>
                                </span>
                            @elseif($statusKode == 'ditolak')
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2] rounded-full text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">cancel</span>
                                    <span>{{ $statusNama }}</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-50 text-blue-700 border border-blue-200 rounded-full text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">pending</span>
                                    <span>{{ $statusNama }}</span>
                                </span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-center">
                            @if($app->file_surat_keluaran)
                                <a href="{{ asset('storage/' . $app->file_surat_keluaran) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-primary text-white rounded-lg text-xs font-semibold hover:bg-secondary transition-all shadow-xs">
                                    <span class="material-symbols-outlined text-[16px]">download</span>
                                    <span>Unduh Surat</span>
                                </a>
                            @else
                                <span class="text-xs text-on-surface-variant italic">Menunggu verifikasi</span>
                            @endif
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
</section>
@endauth
<!-- Jenis Pelayanan Surat Izin Rekomendasi Section -->
<section id="services-section" class="max-w-container-max mx-auto px-margin-desktop pt-16 pb-4 relative z-10 w-full overflow-hidden">
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
<button onclick="openModal('penelitian_pt')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="Penelitian (Instansi / Lembaga Lainnya)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">business</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Penelitian (Instansi / Lembaga Lainnya)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan pengajuan surat izin rekomendasi kegiatan penelitian bagi instansi atau lembaga lainnya.</p>
<button onclick="openModal('penelitian_instansi')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="KKL / PKL / Magang (Mahasiswa)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">work</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKL / PKL / Magang (Mahasiswa)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Informasi persyaratan dan pengajuan surat izin rekomendasi untuk kegiatan KKL, PKL, dan magang mahasiswa.</p>
<button onclick="openModal('kkl_mahasiswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="KKN Mahasiswa" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">groups</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKN Mahasiswa</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan pengajuan surat izin rekomendasi untuk pelaksanaan program Kuliah Kerja Nyata.</p>
<button onclick="openModal('kkn_mahasiswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="KKL / PKL / Magang (Siswa Sekolah)" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">backpack</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">KKL / PKL / Magang (Siswa Sekolah)</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Informasi persyaratan dan pengajuan surat izin rekomendasi untuk kegiatan KKL, PKL, atau magang bagi siswa sekolah.</p>
<button onclick="openModal('kkl_siswa')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="Pelaksanaan Kegiatan" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">event</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Pelaksanaan Kegiatan</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pelayanan surat izin rekomendasi untuk pelaksanaan kegiatan yang membutuhkan persetujuan sesuai ketentuan.</p>
<button onclick="openModal('pelaksanaan_kegiatan')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
<div data-service="Perpanjangan Izin Rekomendasi" class="service-card cursor-pointer bg-surface-container-lowest p-6 rounded-xl shadow-[0_2px_10px_rgba(0,0,0,0.04)] border border-outline-variant/30 reveal-element hover-card-trigger flex flex-col gap-4 flex-shrink-0 w-full sm:w-[calc(50%-0.75rem)] lg:w-[calc(33.333%-1rem)]" style="scroll-snap-align: start;">
<div class="w-12 h-12 bg-surface-container rounded-lg flex items-center justify-center text-primary mb-2">
<span class="material-symbols-outlined">update</span>
</div>
<h3 class="font-title-lg text-title-lg text-on-surface">Perpanjangan Izin Rekomendasi</h3>
<p class="font-body-md text-body-md text-on-surface-variant flex-grow">Pengajuan perpanjangan masa berlaku surat izin rekomendasi yang telah diterbitkan sebelumnya.</p>
<button onclick="openModal('perpanjangan')" class="mt-4 w-full py-3 bg-surface-container-low text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container transition-colors font-medium">Lihat Persyaratan</button>
</div>
</div>
</section>

@guest
<!-- Timeline Section -->
<section id="timeline-section" class="max-w-container-max mx-auto px-margin-desktop pt-8 pb-8 w-full">
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

<!-- Statistics Section (SMART Setjen DPR RI Style) -->
<section id="statistics-section" class="max-w-container-max mx-auto px-margin-desktop pt-8 pb-12 relative z-20 w-full">
<div class="flex flex-col gap-2 mb-10 pb-6 border-b border-outline-variant/30">
    <h2 class="font-headline-lg text-headline-lg text-on-surface">
        Data Layanan Pendaftaran
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
        <!-- Section Header -->
        <div class="flex items-center justify-start border-b border-outline-variant/20 pb-6">
            <!-- Service Selector Dropdown -->
            <div class="flex flex-col gap-2 min-w-[320px]">
                <label for="service-selector" class="font-label-md text-label-md text-on-surface-variant font-bold uppercase tracking-wider">Pilih Jenis Layanan:</label>
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

        <!-- Charts Grid -->
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

            <!-- Right Card: Bar Chart (Weekly/Daily Submissions) -->
            <div class="lg:col-span-3 bg-surface-container-low/40 rounded-xl p-6 border border-outline-variant/20 flex flex-col gap-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex flex-col gap-1">
                        <h3 class="font-title-lg text-title-lg text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-secondary text-[22px]">bar_chart</span>
                            Aktivitas Pendaftaran Harian
                        </h3>
                        <p class="font-caption text-caption text-on-surface-variant">Jumlah pendaftar harian selama 7 hari terakhir.</p>
                    </div>
                    <!-- Today Badge -->
                    <div class="bg-secondary/10 border border-secondary/20 rounded-lg px-4 py-2 flex items-center gap-2 text-secondary">
                        <span class="material-symbols-outlined text-[20px]">today</span>
                        <div class="flex flex-col">
                            <span class="font-caption text-[10px] font-bold uppercase tracking-wider leading-none">Pengguna Hari Ini</span>
                            <span id="today-count-badge" class="font-label-md text-label-md font-bold leading-none mt-1">0 Orang</span>
                        </div>
                    </div>
                </div>

                <!-- Chart Area -->
                <div class="flex-grow min-h-[280px] flex items-center justify-center">
                    <div id="bar-chart-container" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>
</section>
@endguest

<!-- Instansi Tujuan Magang -->
<section id="instansi-section" class="max-w-container-max mx-auto px-margin-desktop py-16 w-full">
<div class="flex justify-between items-end mb-stack-lg">
<div class="flex flex-col gap-2">
<h2 class="font-headline-lg text-headline-lg text-on-surface">Instansi Tujuan Magang</h2>
<p class="font-body-md text-body-md text-on-surface-variant">Pilih instansi yang sesuai dengan bidang studi dan minat Anda.</p>
</div>
<a class="font-label-md text-label-md text-secondary hover:underline flex items-center gap-1" href="{{ route('landing.instansi') }}">Lihat Semua <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 stagger-container">
@foreach($featuredInstansis as $instansi)
<a class="group block bg-surface-container-lowest rounded-xl p-6 shadow-level-1 shadow-level-1-hover border-t-4 border-outline-variant hover:border-primary transition-colors duration-300 relative overflow-hidden flex flex-col h-full border border-x-outline-variant/30 border-b-outline-variant/30 reveal-element hover-card-trigger" href="{{ route('landing.instansi_detail', $instansi->id) }}">
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
<p class="font-body-md text-body-md text-on-surface-variant mb-6 flex-grow line-clamp-3">
    {{ $instansi->deskripsi ?? 'Fasilitas pelayanan, riset, dan magang di ' . $instansi->name . '.' }}
</p>
<div class="mt-auto flex items-center justify-between pt-4 border-t border-surface-container-high">
  @php $badge = $instansi->status_badge; @endphp
  <div class="flex items-center gap-1 px-3 py-1.5 {{ $badge['bg_class'] }} rounded-lg font-label-md text-caption font-bold">
    <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
    <span>{{ $badge['label'] }}</span>
  </div>
  <div class="py-2 px-4 bg-surface-container-low text-primary font-label-md text-label-md rounded-md hover:bg-surface-container transition-colors text-center inline-block">Lihat Detail</div>
</div>
</a>
@endforeach
</div>
</section>
<!-- Peserta Magang Diterima -->
<section class="max-w-container-max mx-auto px-margin-desktop py-16 w-full">
<div class="flex flex-col gap-8">
<div class="flex justify-between items-end mb-stack-lg"><div class="flex flex-col gap-2"><h2 class="font-headline-lg text-headline-lg text-on-surface">Peserta Magang Diterima</h2><p class="font-body-md text-body-md text-on-surface-variant">Daftar peserta yang telah diterima untuk mengikuti program magang di lingkungan Pemerintah Kabupaten Bogor.</p></div><a class="font-label-md text-label-md text-secondary hover:underline flex items-center gap-1" href="{{ route('landing.peserta') }}">Lihat Selengkapnya <span class="material-symbols-outlined text-[16px]">arrow_forward</span></a></div>

<div class="bg-surface-container-lowest rounded-xl shadow-ambient overflow-hidden border border-surface-container-high">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
    <tr class="bg-surface-container-low border-b border-surface-container-high">
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Asal Lembaga & Jurusan</th>
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tujuan Dinas & Bidang</th>
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tanggal Diterima</th>
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Tanggal Berakhir</th>
        <th class="px-6 py-4 font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Status</th>
    </tr>
</thead>
<tbody class="divide-y divide-surface-container-high">
    @forelse($pesertas as $peserta)
    <tr class="hover:bg-surface-container-low/50 transition-colors">
        <td class="px-6 py-4">
            <span class="font-label-md text-on-surface">{{ $peserta->user->name }}</span>
        </td>
        <td class="px-6 py-4 text-body-md whitespace-normal min-w-[200px]">
            <div class="text-on-surface">{{ $peserta->instansi_asal }}</div>
            <div class="text-on-surface-variant text-sm mt-1">{{ $peserta->jurusan }}</div>
        </td>
        <td class="px-6 py-4 text-body-md whitespace-normal min-w-[200px]">
            <div class="text-on-surface">{{ $peserta->dinas->name ?? '-' }}</div>
            <div class="text-on-surface-variant text-sm mt-1">{{ $peserta->bidang->nama ?? '-' }}</div>
        </td>
        <td class="px-6 py-4 text-body-md text-on-surface-variant">{{ $peserta->tanggal_mulai ? $peserta->tanggal_mulai->format('d M Y') : '-' }}</td>
        <td class="px-6 py-4 text-body-md text-on-surface-variant">{{ $peserta->tanggal_selesai ? $peserta->tanggal_selesai->format('d M Y') : '-' }}</td>
        <td class="px-6 py-4">
            <span class="px-3 py-1 rounded-full bg-[#DCFCE7] text-[#15803d] dark:bg-emerald-950/40 dark:text-emerald-400 font-label-md text-caption uppercase">{{ $peserta->status->nama ?? 'Diterima' }}</span>
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
@if(isset($userMagang) && $userMagang)
<!-- Jadwal Masuk Anak Magang di Bidang (Untuk User Diterima) -->
<section id="jadwal-magang-section" class="max-w-container-max mx-auto px-margin-desktop py-12 w-full">
    <div class="bg-surface-container-lowest rounded-2xl shadow-level-2 border border-outline-variant/30 overflow-hidden reveal-scale">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-primary via-primary-container to-secondary p-8 text-white relative overflow-hidden">
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
                    <span class="text-base font-bold text-on-surface mt-1">
                        {{ $userMagang->tanggal_mulai ? $userMagang->tanggal_mulai->format('d M Y') : 'Mulai Baru' }}
                    </span>
                    <span class="text-xs text-on-surface-variant font-medium">
                        s.d. {{ $userMagang->tanggal_selesai ? $userMagang->tanggal_selesai->format('d M Y') : 'Selesai' }}
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
                    <span class="text-base font-bold text-on-surface mt-1">{{ count($bidangParticipants) }} Peserta</span>
                    <span class="text-xs text-on-surface-variant font-medium">Bidang {{ $userMagang->bidang->name ?? ($userMagang->rekrutmen->bidang->name ?? 'Terkait') }}</span>
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
                            <th class="p-4 text-center">Jadwal Masuk</th>
                            <th class="p-4 text-center">Status Kehadiran Hari Ini</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10 text-sm">
                        @forelse($bidangParticipants as $p)
                        @php
                            $todayAbsensi = $p->absensis->first();
                        @endphp
                        <tr class="hover:bg-surface-container-low/40 transition-colors {{ $p->user_id === auth()->id() ? 'bg-primary/5 font-semibold' : '' }}">
                            <td class="p-4 pl-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-primary/10 text-primary font-bold flex items-center justify-center text-xs">
                                        {{ strtoupper(substr($p->user->name ?? 'P', 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-on-surface font-bold">
                                            {{ $p->user->name ?? '-' }}
                                            @if($p->user_id === auth()->id())
                                                <span class="ml-1 text-[10px] px-2 py-0.5 bg-primary text-white rounded-full font-bold">Anda</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-on-surface-variant">{{ $p->user->email ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-on-surface-variant">
                                {{ $p->permohonanLayanan->asal_instansi ?? ($p->user->asal_instansi ?? 'Umum') }}
                            </td>
                            <td class="p-4 text-center text-on-surface">
                                <span class="px-2.5 py-1 bg-surface-container rounded-md font-mono text-xs font-semibold">08.00 - 16.00 WIB</span>
                            </td>
                            <td class="p-4 text-center">
                                @if($todayAbsensi)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 rounded-full text-xs font-bold">
                                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                        Hadir ({{ \Carbon\Carbon::parse($todayAbsensi->waktu_masuk)->format('H:i') }} WIB)
                                    </span>
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
                            <td colspan="4" class="p-6 text-center text-on-surface-variant">
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
        if (e) e.stopPropagation();
        const menu = document.getElementById('dropdown-layanan-menu');
        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    function closeLayananDropdown() {
        const menu = document.getElementById('dropdown-layanan-menu');
        if (menu) {
            menu.classList.add('hidden');
        }
    }

    document.addEventListener('click', function(e) {
        const menu = document.getElementById('dropdown-layanan-menu');
        if (menu && !menu.contains(e.target)) {
            menu.classList.add('hidden');
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

        function renderCharts(serviceName) {
            const data = serviceStats[serviceName];
            if (!data) return;

            // Highlight the card in the service list
            highlightServiceCard(serviceName);

            // Update stats summary in HTML
            document.getElementById('stat-registered-val').textContent = Number(data.registered).toLocaleString('id-ID');
            document.getElementById('stat-accepted-val').textContent = Number(data.accepted).toLocaleString('id-ID');
            document.getElementById('today-count-badge').textContent = `${data.today} Orang`;

            // Donut Chart Config
            const donutOptions = {
                series: [data.registered - data.accepted, data.accepted],
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
                colors: ['#f59e0b', '#10b981'], // Amber and Emerald Green
                legend: {
                    show: true,
                    position: 'bottom',
                    labels: {
                        colors: 'var(--tw-on-surface)'
                    }
                },
                dataLabels: {
                    enabled: true,
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
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    y: {
                        formatter: function (value) {
                            return value + " Berkas";
                        }
                    }
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '52%', // Thicker ring to fit labels
                            labels: {
                                show: true,
                                total: {
                                    show: true,
                                    label: 'Total Terdaftar',
                                    color: 'var(--tw-on-surface-variant)',
                                    fontSize: '12px',
                                    fontWeight: '600',
                                    formatter: function (w) {
                                        return data.registered.toLocaleString('id-ID');
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

            // Bar Chart Config (highlight last column which represents Today)
            const colors = Array(6).fill('#818cf8'); // soft indigo for previous days
            colors.push('#10b981'); // Emerald Green for Today

            const barOptions = {
                series: [{
                    name: 'Jumlah Pengguna',
                    data: data.weekly_series
                }],
                chart: {
                    type: 'bar',
                    height: 280,
                    fontFamily: 'Plus Jakarta Sans, sans-serif',
                    toolbar: {
                        show: false
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },
                colors: colors,
                plotOptions: {
                    bar: {
                        borderRadius: 4,
                        columnWidth: '50%',
                        distributed: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                xaxis: {
                    categories: dayNames,
                    labels: {
                        style: {
                            colors: Array(7).fill('var(--tw-on-surface-variant)'),
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: 'var(--tw-on-surface-variant)'
                        },
                        formatter: function (val) {
                            return Math.round(val);
                        }
                    }
                },
                grid: {
                    borderColor: 'var(--tw-outline-variant)',
                    opacity: 0.1,
                    strokeDashArray: 4,
                    yaxis: {
                        lines: {
                            show: true
                        }
                    }
                },
                tooltip: {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    y: {
                        formatter: function (val) {
                            return val + " Pengguna";
                        }
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

        // Initialize selector & charts
        const serviceSelector = document.getElementById('service-selector');
        if (serviceSelector) {
            renderCharts(serviceSelector.value);

            serviceSelector.addEventListener('change', function(e) {
                renderCharts(e.target.value);
            });
        }

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
                    renderCharts(serviceName);
                    // Smooth scroll to analytics section
                    const targetSection = document.getElementById('analytics-section');
                    if (targetSection) {
                        targetSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        });
    });
</script>
@endsection















