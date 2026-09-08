@extends('pelayanan.layouts.app')

@section('title', 'Detail Instansi - LENTERA')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "margin-mobile": "1rem",
                        "stack-sm": "0.5rem",
                        "stack-lg": "2rem",
                        "margin-desktop": "2.5rem",
                        "gutter": "1.5rem",
                        "container-max": "1280px",
                        "stack-md": "1rem"
                    },
                    "fontFamily": {
                        "body-md": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "caption": ["Plus Jakarta Sans"],
                        "title-lg": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "display-lg": ["48px", {"lineHeight": "60px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "caption": ["12px", {"lineHeight": "16px", "fontWeight": "400"}],
                        "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "500"}]
                    }
                }
            }
        }
    </script>
@endsection

@section('content')

<main class="w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg flex flex-col gap-stack-lg">
    <!-- Exit / Back Button -->
    <div class="flex items-center">
        <a href="{{ route('landing.instansi') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-low hover:bg-surface-container text-primary font-label-md text-label-md rounded-lg transition-all border border-outline-variant/30 hover:border-primary shadow-sm hover:shadow">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            <span>Kembali ke Instansi</span>
        </a>
    </div>

<!-- Institution Header -->
<section class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-ambient border-t-4 border-primary-container flex flex-col md:flex-row gap-stack-lg items-start md:items-center justify-between">
    <div class="flex flex-col md:flex-row gap-stack-lg items-start md:items-center w-full">
        <div class="w-24 h-24 bg-surface-container-low rounded-lg flex items-center justify-center shrink-0 border border-outline-variant">
            <img class="w-16 h-16 object-contain" alt="Official logo" src="{{ $instansi->logo ? asset('storage/'.$instansi->logo) : asset('assets/certificate/lambang_kabupaten_bogor.png') }}"/>
        </div>
        <div class="flex flex-col gap-2 w-full">
            <div class="flex items-center justify-between gap-3 flex-wrap">
                <div class="flex flex-col">
                    <h1 class="font-headline-lg-mobile md:font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary uppercase">{{ $instansi->name }}</h1>
                    <p class="font-title-lg text-title-lg text-on-surface-variant">Pemerintah Kabupaten Bogor</p>
                </div>
                @php $badge = $instansi->status_badge; @endphp
                <span class="{{ $badge['bg_class'] }} font-caption text-caption px-3.5 py-1.5 rounded-full font-bold uppercase tracking-wide flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
                    <span>{{ $badge['label'] }}</span>
                </span>
            </div>
            <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed max-w-container-max mt-2">
                {{ $instansi->deskripsi ?? 'Fasilitas pelayanan, riset, dan magang di ' . $instansi->name . '.' }}
            </p>
            <div class="flex flex-wrap items-center gap-x-6 gap-y-2 mt-4 pt-4 border-t border-outline-variant">
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-secondary">location_on</span>
                    <span class="font-caption text-caption">{{ $instansi->alamat ?? 'Alamat belum tersedia' }}</span>
                </div>
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-secondary">mail</span>
                    <span class="font-caption text-caption">{{ $instansi->email ?? 'Email belum tersedia' }}</span>
                </div>
                <div class="flex items-center gap-2 text-on-surface-variant">
                    <span class="material-symbols-outlined text-sm text-secondary">call</span>
                    <span class="font-caption text-caption">{{ $instansi->telepon ?? 'Telepon belum tersedia' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Internship Quota Overview -->
<section class="grid grid-cols-2 md:grid-cols-4 gap-stack-md">
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient flex flex-col gap-2 hover-lift border-t-[3px] border-transparent hover:border-secondary transition-all">
        <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Total Kuota</p>
        <div class="flex items-baseline gap-2">
            <span class="font-display-lg text-display-lg text-primary">{{ $totalKuota }}</span>
            <span class="font-body-md text-body-md text-outline">Orang</span>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient flex flex-col gap-2 hover-lift border-t-[3px] border-transparent hover:border-secondary transition-all">
        <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Peserta Diterima</p>
        <div class="flex items-baseline gap-2">
            <span class="font-display-lg text-display-lg text-primary">{{ $totalDiterima }}</span>
            <span class="font-body-md text-body-md text-outline">Orang</span>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient flex flex-col gap-2 hover-lift border-t-[3px] border-transparent hover:border-secondary transition-all">
        <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Slot Tersedia</p>
        <div class="flex items-baseline gap-2">
            <span class="font-display-lg text-display-lg {{ $slotTersedia > 0 ? 'text-secondary' : 'text-outline' }}">{{ $slotTersedia }}</span>
            <span class="font-body-md text-body-md text-outline">Orang</span>
        </div>
    </div>
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient flex flex-col gap-2 hover-lift border-t-[3px] border-transparent hover:border-secondary transition-all">
        <p class="font-caption text-caption text-on-surface-variant uppercase tracking-wider">Status</p>
        <div class="flex items-center h-full">
            <span class="text-secondary font-headline-md text-headline-md">{{ $slotTersedia > 0 ? 'Membuka' : 'Ditutup' }}</span>
        </div>
    </div>
</section>

<div class="grid-cols-1 gap-stack-lg">
    <div class="flex flex-col gap-stack-lg">
        <section class="flex flex-col gap-stack-md mt-stack-lg">
            <h2 class="font-headline-md text-headline-md text-primary">Bidang (Unit Kerja) Tersedia</h2>
            
            @if($instansi->bidang->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md">
                    @foreach($instansi->bidang as $bidang)
                    @php
                        // Get the active rekrutmen for this bidang
                        $rekrutmen = $instansi->rekrutmens->where('bidang_id', $bidang->id)->first();
                    @endphp
                    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-ambient flex flex-col gap-4 border border-outline-variant hover-lift transition-all group relative overflow-hidden">
                        @if($rekrutmen)
                            @if($rekrutmen->slot_tersedia > 0)
                                <div class="absolute top-0 right-0 bg-[#E8F5E9] text-[#2E7D32] text-xs font-bold px-3 py-1 rounded-bl-lg z-10 border-b border-l border-[#C8E6C9]">
                                    Sisa Kuota: {{ $rekrutmen->slot_tersedia }}
                                </div>
                            @else
                                <div class="absolute top-0 right-0 bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-bl-lg z-10 border-b border-l border-blue-200">
                                    Kuota Penuh
                                </div>
                            @endif
                        @endif
                        <div class="flex justify-between items-start mt-2">
                            <h3 class="font-title-lg text-title-lg text-primary-container group-hover:text-secondary transition-colors">{{ $bidang->name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-secondary bg-surface-container p-2 rounded-full">work</span>
                            </div>
                        </div>
                        <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">Bidang atau unit kerja {{ $bidang->name }} di lingkungan {{ $instansi->name }}.</p>
                        
                        @if($rekrutmen)
                            <div class="mt-auto pt-4 border-t border-outline-variant">
                                <div class="grid grid-cols-2 gap-4 mb-4">
                                    <div class="flex flex-col">
                                        <span class="font-caption text-caption text-outline uppercase tracking-wider">Total Kuota</span>
                                        <span class="font-body-md text-body-md font-semibold text-on-background">{{ $rekrutmen->kuota }} Orang</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-caption text-caption text-outline uppercase tracking-wider">Slot Tersedia</span>
                                        <span class="font-body-md text-body-md font-bold {{ $rekrutmen->slot_tersedia > 0 ? 'text-secondary' : 'text-outline' }}">{{ $rekrutmen->slot_tersedia }} Orang</span>
                                    </div>
                                </div>
                                
                                @if($rekrutmen->magangApplications->count() > 0)
                                <div class="mb-4">
                                    <span class="font-caption text-caption text-outline uppercase tracking-wider block mb-2">Peserta Magang Diterima</span>
                                    <ul class="space-y-2">
                                        @foreach($rekrutmen->magangApplications as $app)
                                        <li class="flex flex-col bg-surface-container py-1.5 px-3 rounded text-sm">
                                            <span class="font-semibold text-on-surface">{{ $app->user->nama ?? 'Peserta' }}</span>
                                            <span class="text-xs text-on-surface-variant">{{ $app->permohonanLayanan->instansi_asal ?? '-' }}</span>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($rekrutmen->slot_tersedia > 0)
                                    <a href="{{ route('magang.apply', $rekrutmen->id) }}" class="w-full py-2 bg-primary-container text-on-primary font-label-md text-label-md rounded-lg hover:bg-primary transition-colors text-center inline-block">Daftar Sekarang</a>
                                @else
                                    <button disabled class="w-full py-2 bg-outline-variant text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed text-center inline-block">Kuota Penuh</button>
                                @endif
                            </div>
                        @else
                            <div class="mt-auto pt-4 border-t border-outline-variant">
                                <button disabled class="w-full py-2 bg-surface-container text-on-surface-variant font-label-md text-label-md rounded-lg cursor-not-allowed text-center inline-block">Belum ada lowongan</button>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="bg-surface-container-lowest rounded-xl p-8 shadow-ambient text-center border border-outline-variant/50">
                    <span class="material-symbols-outlined text-4xl text-outline mb-2">work_off</span>
                    <p class="font-body-md text-body-md text-on-surface-variant">Belum ada data unit kerja/bidang untuk instansi ini.</p>
                </div>
            @endif
        </section>
    </div>
</div>

</main>
@endsection
