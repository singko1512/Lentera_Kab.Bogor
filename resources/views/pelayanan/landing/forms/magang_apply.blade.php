@extends('pelayanan.layouts.app')

@section('title', 'Formulir Pendaftaran Magang - LENTERA')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script>
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
                    "on-tertiary-fixed-variant": "#343d49",
                    "on-secondary-fixed-variant": "#324051",
                    "surface-dim": "#0b1c30",
                    "error-container": "#93000a",
                    "on-surface": "var(--tw-on-surface)",
                    "tertiary": "#a0c9fa",
                    "on-tertiary": "#003259",
                    "surface-tint": "#a7c8ff",
                    "shadow": "#000000",
                    "on-error-container": "#ffdad6",
                    "outline-variant": "var(--tw-outline-variant)",
                    "on-error": "#690005",
                    "primary": "var(--tw-primary)",
                    "inverse-on-surface": "#0b1c30",
                    "on-primary-fixed-variant": "#2b4673",
                    "primary-fixed": "#d3e4fe",
                    "on-primary": "#003061",
                    "inverse-surface": "#eaf1ff",
                    "surface-container-high": "#1c3251",
                    "secondary": "#a2c9fa",
                    "secondary-fixed-dim": "#a2c9fa",
                    "secondary-fixed": "#d3e4fe",
                    "on-secondary": "#023259",
                    "on-secondary-fixed": "#021c38",
                    "tertiary-fixed-dim": "#a0c9fa",
                    "error": "#ffb4ab",
                    "tertiary-container": "#1d4976",
                    "on-primary-fixed": "#001a39",
                    "surface-container-low": "var(--tw-surface-container-low)",
                    "surface-container-highest": "#233957",
                    "inverse-primary": "#455e8c",
                    "on-primary-container": "#d3e4fe",
                    "surface": "var(--tw-background)",
                    "scrim": "#000000",
                    "on-tertiary-container": "#d1e4ff",
                    "tertiary-fixed": "#d1e4ff",
                    "primary-container": "#2b4673",
                    "on-secondary-container": "#d3e4fe",
                    "surface-bright": "#334764",
                    "surface-container-lowest": "var(--tw-surface-container-lowest)",
                    "secondary-container": "#214774",
                    "surface-variant": "var(--tw-surface-variant)"
                },
                "fontFamily": {
                    "body-lg": "Plus Jakarta Sans",
                    "body-sm": "Plus Jakarta Sans",
                    "label-sm": "Plus Jakarta Sans",
                    "title-sm": "Plus Jakarta Sans",
                    "label-md": "Plus Jakarta Sans",
                    "display-sm": "Plus Jakarta Sans",
                    "display-lg": "Plus Jakarta Sans",
                    "body-md": "Plus Jakarta Sans",
                    "display-md": "Plus Jakarta Sans",
                    "title-lg": "Plus Jakarta Sans",
                    "headline-sm": "Plus Jakarta Sans",
                    "headline-md": "Plus Jakarta Sans",
                    "headline-lg": "Plus Jakarta Sans",
                    "label-lg": "Plus Jakarta Sans",
                    "title-md": "Plus Jakarta Sans"
                },
                "spacing": {
                    "stack-sm": "16px",
                    "margin-desktop": "120px",
                    "stack-md": "32px",
                    "stack-lg": "48px",
                    "stack-xl": "64px"
                },
                "boxShadow": {
                    "ambient": "0px 1px 3px 0px rgba(0, 0, 0, 0.1), 0px 1px 2px -1px rgba(0, 0, 0, 0.1)",
                    "hover": "0px 10px 15px -3px rgba(0, 0, 0, 0.1), 0px 4px 6px -4px rgba(0, 0, 0, 0.1)",
                    "level-2": "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)"
                },
                "fontSize": {
                    "display-lg": ["57px", { "lineHeight": "64px", "fontWeight": "400" }],
                    "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "400" }],
                    "title-lg": ["22px", { "lineHeight": "28px", "fontWeight": "400" }],
                    "label-md": ["12px", { "lineHeight": "16px", "fontWeight": "500" }],
                    "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                    "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                    "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }]
                }
            }
        }
    }
</script>
<style>
    .reveal-scale {
        animation: revealScale 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes revealScale {
        from {
            opacity: 0;
            transform: scale(0.97) translateY(10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
@endsection

@section('content')
<main class="flex-grow flex flex-col justify-center py-12 max-w-3xl mx-auto px-margin-desktop w-full">
    <div class="bg-surface-container-lowest border border-outline-variant/30 rounded-2xl shadow-level-2 overflow-hidden reveal-scale">
        
        {{-- Header --}}
        <div class="bg-gradient-to-r from-primary/10 to-transparent p-8 border-b border-outline-variant/30 relative">
            <div class="absolute top-0 right-0 p-8 opacity-10">
                <span class="material-symbols-outlined text-[64px] text-primary">work</span>
            </div>
            <h3 class="font-headline-lg text-headline-lg text-on-surface mb-2 relative z-10">Formulir Pendaftaran Magang</h3>
            <p class="font-body-md text-body-md text-on-surface-variant relative z-10">
                Mendaftar untuk posisi <span class="font-bold text-primary">{{ $rekrutmen->judul }}</span> 
                di <span class="font-bold text-on-surface">{{ $rekrutmen->dinas->name ?? $rekrutmen->dinas->nama ?? 'Dinas Terkait' }}</span>.
            </p>
        </div>

        <div class="p-8">
            {{-- Alert Error --}}
            @if(session('error'))
                <div class="mb-6 p-4 bg-error/10 border border-error/30 rounded-xl flex items-start gap-3">
                    <span class="material-symbols-outlined text-error">error</span>
                    <div class="font-body-md text-on-surface">{{ session('error') }}</div>
                </div>
            @endif

            {{-- Alert Info Rekomendasi Kesbangpol --}}
            <div class="mb-8 p-4 bg-primary/10 border border-primary/20 rounded-xl flex items-start gap-3">
                <span class="material-symbols-outlined text-primary mt-0.5">info</span>
                <div class="font-body-sm text-on-surface-variant">
                    Pengajuan pendaftaran ini akan otomatis melampirkan izin rekomendasi Kesbangpol Anda yang terakhir disetujui.
                </div>
            </div>

            {{-- Form --}}
            <form action="{{ route('magang.submit', $rekrutmen->id) }}" method="POST" id="apply-form">
                @csrf
                <input type="hidden" name="permohonan_layanan_id" value="{{ $permohonanLayanan->id ?? '' }}">

                {{-- Pesan Lamaran --}}
                <div class="mb-6 flex flex-col gap-2">
                    <label class="font-label-md text-label-md font-bold text-on-surface">
                        Pesan Lamaran / Motivasi <span class="text-on-surface-variant font-normal">(Opsional)</span>
                    </label>
                    <textarea name="pesan_lamaran" rows="4" 
                        class="w-full bg-surface border border-outline-variant rounded-xl p-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all placeholder:text-outline/50 text-on-surface" 
                        placeholder="Tuliskan motivasi, keahlian, atau pesan tambahan Anda..."></textarea>
                </div>

                {{-- Tanggal Mulai & Selesai --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md font-bold text-on-surface">Tanggal Mulai Magang <span class="text-error">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">calendar_today</span>
                            <input type="date" name="tanggal_mulai" required 
                                class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-xl text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all text-on-surface cursor-pointer" 
                                value="{{ !empty($permohonanLayanan->tanggal_mulai) ? (is_string($permohonanLayanan->tanggal_mulai) ? \Carbon\Carbon::parse($permohonanLayanan->tanggal_mulai)->format('Y-m-d') : $permohonanLayanan->tanggal_mulai->format('Y-m-d')) : date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="font-label-md text-label-md font-bold text-on-surface">Tanggal Selesai Magang <span class="text-error">*</span></label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">event</span>
                            <input type="date" name="tanggal_selesai" required 
                                class="w-full pl-10 pr-4 py-3 bg-surface border border-outline-variant rounded-xl text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary focus:outline-none transition-all text-on-surface cursor-pointer" 
                                value="{{ !empty($permohonanLayanan->tanggal_selesai) ? (is_string($permohonanLayanan->tanggal_selesai) ? \Carbon\Carbon::parse($permohonanLayanan->tanggal_selesai)->format('Y-m-d') : $permohonanLayanan->tanggal_selesai->format('Y-m-d')) : '' }}">
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center gap-3 pt-6 border-t border-outline-variant/30">
                    <button type="submit" id="submit-btn" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-label-md font-bold rounded-xl shadow-md hover:bg-secondary hover:shadow-lg transition-all w-full md:w-auto">
                        <span class="material-symbols-outlined text-[20px]">send</span>
                        Kirim Pendaftaran
                    </button>
                    <a href="{{ route('landing.instansi_detail', $rekrutmen->dinas_id) }}" 
                       class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-surface-container text-on-surface-variant font-label-md font-bold rounded-xl hover:bg-surface-container-high transition-all w-full md:w-auto">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tglMulai = document.querySelector('input[name="tanggal_mulai"]');
    const tglSelesai = document.querySelector('input[name="tanggal_selesai"]');
    
    function syncDates() {
        if (tglMulai && tglSelesai && tglMulai.value) {
            tglSelesai.min = tglMulai.value;
            if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                tglSelesai.value = tglMulai.value;
            }
        }
    }
    if (tglMulai) {
        tglMulai.addEventListener('change', syncDates);
        tglMulai.addEventListener('input', syncDates);
        syncDates();
    }

    const form = document.getElementById('apply-form');
    if (form) {
        form.addEventListener('submit', function (e) {
            if (tglMulai && tglSelesai && tglMulai.value && tglSelesai.value) {
                if (tglSelesai.value < tglMulai.value) {
                    e.preventDefault();
                    if (typeof Swal !== 'undefined') {
                        Swal.fire('Error', 'Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.', 'error');
                    } else {
                        alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.');
                    }
                    tglSelesai.focus();
                    return;
                }
            }
            const btn = document.getElementById('submit-btn');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = `<span class="material-symbols-outlined animate-spin text-[20px]">sync</span> Mengirim...`;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
            }
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Mengirim Pendaftaran...',
                    text: 'Mohon tunggu sebentar, data pendaftaran Anda sedang diproses.',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }
});
</script>
@endsection
