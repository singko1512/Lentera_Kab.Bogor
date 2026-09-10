@extends('pelayanan.layouts.app')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
    function togglePeserta(value) {
        const fileDaftarPesertaContainer = document.getElementById('container-file-daftar-peserta');
        const atasNamaInput = document.querySelector('input[name="atas_nama"]');
        let labelAtasNama = null;
        
        if (atasNamaInput) {
            labelAtasNama = atasNamaInput.parentElement.querySelector('label');
        }

        if (value.includes('Kelompok')) {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.remove('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.setAttribute('required', 'required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Nama Perwakilan / Ketua Kelompok <span class="text-error">*</span>';
            }
        } else {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.add('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.removeAttribute('required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Atas Nama <span class="text-error">*</span>';
            }
        }
    }
</script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            "colors": {
                    "background": "#f8f9ff",
                    "secondary": "#115cb9",
                    "surface-variant": "#d3e4fe",
                    "on-surface-variant": "#43474f",
                    "tertiary-container": "#2e343a",
                    "error": "#ba1a1a",
                    "on-secondary": "#ffffff",
                    "on-error": "#ffffff",
                    "on-error-container": "#93000a",
                    "tertiary-fixed-dim": "#c1c7cf",
                    "on-secondary-fixed": "#001a40",
                    "on-primary-fixed": "#001b3c",
                    "primary-fixed": "#d5e3ff",
                    "tertiary-fixed": "#dde3eb",
                    "inverse-primary": "#a7c8ff",
                    "on-background": "#0b1c30",
                    "surface-bright": "#f8f9ff",
                    "primary-fixed-dim": "#a7c8ff",
                    "on-tertiary-fixed": "#161c22",
                    "primary-container": "#003366",
                    "on-tertiary-container": "#969ca4",
                    "on-tertiary-fixed-variant": "#41474e",
                    "on-surface": "#0b1c30",
                    "surface-tint": "#3a5f94",
                    "outline": "#737780",
                    "surface-container-highest": "#d3e4fe",
                    "inverse-surface": "#213145",
                    "primary": "#001e40",
                    "on-secondary-container": "#003370",
                    "on-tertiary": "#ffffff",
                    "inverse-on-surface": "#eaf1ff",
                    "on-primary-container": "#799dd6",
                    "secondary-fixed": "#d7e2ff",
                    "outline-variant": "#c3c6d1",
                    "on-secondary-fixed-variant": "#004491",
                    "error-container": "#ffdad6",
                    "secondary-fixed-dim": "#acc7ff",
                    "on-primary-fixed-variant": "#1f477b",
                    "surface-container": "#e5eeff",
                    "secondary-container": "#659dfe",
                    "surface-container-high": "#dce9ff",
                    "on-primary": "#ffffff",
                    "surface-container-lowest": "#ffffff",
                    "surface-container-low": "#eff4ff",
                    "surface": "#f8f9ff",
                    "surface-dim": "#cbdbf5",
                    "tertiary": "#191f25"
            },
            "borderRadius": {
                    "DEFAULT": "0.25rem",
                    "lg": "0.5rem",
                    "xl": "0.75rem",
                    "full": "9999px"
            },
            "spacing": {
                    "gutter": "1.5rem",
                    "margin-desktop": "2.5rem",
                    "stack-lg": "2rem",
                    "margin-mobile": "1rem",
                    "container-max": "1280px",
                    "stack-sm": "0.5rem",
                    "stack-md": "1rem"
            },
            "fontFamily": {
                    "caption": [
                            "Plus Jakarta Sans"
                    ],
                    "body-md": [
                            "Plus Jakarta Sans"
                    ],
                    "headline-lg-mobile": [
                            "Plus Jakarta Sans"
                    ],
                    "headline-lg": [
                            "Plus Jakarta Sans"
                    ],
                    "label-md": [
                            "Plus Jakarta Sans"
                    ],
                    "title-lg": [
                            "Plus Jakarta Sans"
                    ],
                    "display-lg": [
                            "Plus Jakarta Sans"
                    ],
                    "body-lg": [
                            "Plus Jakarta Sans"
                    ],
                    "headline-md": [
                            "Plus Jakarta Sans"
                    ]
            },
            "fontSize": {
                    "caption": [
                            "12px",
                            {
                                    "lineHeight": "16px",
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
                    ],
                    "headline-lg": [
                            "32px",
                            {
                                    "lineHeight": "40px",
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
                    "title-lg": [
                            "20px",
                            {
                                    "lineHeight": "28px",
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
                    "body-lg": [
                            "18px",
                            {
                                    "lineHeight": "28px",
                                    "fontWeight": "400"
                            }
                    ],
                    "headline-md": [
                            "24px",
                            {
                                    "lineHeight": "32px",
                                    "fontWeight": "600"
                            }
                    ]
            }
    },
        },
      }
    
    function togglePeserta(value) {
        const fileDaftarPesertaContainer = document.getElementById('container-file-daftar-peserta');
        const atasNamaInput = document.querySelector('input[name="atas_nama"]');
        let labelAtasNama = null;
        
        if (atasNamaInput) {
            labelAtasNama = atasNamaInput.parentElement.querySelector('label');
        }

        if (value.includes('Kelompok')) {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.remove('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.setAttribute('required', 'required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Nama Perwakilan / Ketua Kelompok <span class="text-error">*</span>';
            }
        } else {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.add('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.removeAttribute('required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Atas Nama <span class="text-error">*</span>';
            }
        }
    }
</script>

@endsection

@section('content')


<!-- Main Content Area -->
<main class="max-w-container-max mx-auto px-margin-desktop py-stack-lg flex flex-col gap-stack-lg">
<!-- Header Section -->
<div class="flex flex-col gap-stack-sm w-full">
<nav aria-label="Breadcrumb" class="flex text-on-surface-variant font-caption text-caption">
<ol class="inline-flex items-center space-x-1 md:space-x-2">
<li class="inline-flex items-center"><a href="{{ route('home') }}" class="hover:text-secondary transition-colors cursor-pointer">Beranda</a></li>
<li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
<li class="inline-flex items-center"><a href="{{ route('home') }}" class="hover:text-secondary transition-colors cursor-pointer">Jenis Pelayanan</a></li>
<li><span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span></li>
<li aria-current="page" class="text-primary font-medium">KKN Mahasiswa</li>
</ol>
</nav>
<h1 class="font-headline-lg text-headline-lg text-primary mt-2">Form Pengajuan KKN Mahasiswa</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl">Lengkapi formulir di bawah ini untuk mengajukan permohonan rekomendasi pelaksanaan program Kuliah Kerja Nyata (KKN) di wilayah Kabupaten Bogor.</p>
</div>
<div class="flex flex-col md:flex-row gap-stack-lg">
<!-- Sidebar (Tahapan Pengajuan) -->
<aside class="w-full md:w-64 flex-shrink-0 flex flex-col gap-stack-md">
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="font-title-lg text-title-lg text-primary mb-stack-sm">Jenis Pelayanan</h3>
<p class="font-body-md text-body-md text-on-surface-variant font-semibold">KKN Mahasiswa</p>
<div class="h-px bg-outline-variant my-stack-md"></div>
<h4 class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-stack-md">Tahapan Pengajuan</h4>
<ol id="stepper-sidebar" class="relative border-l border-outline-variant ml-3 space-y-6">
<li class="pl-6 relative">
<span class="absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-secondary ring-4 ring-surface-container-lowest"></span>
<h5 class="font-label-md text-label-md text-primary font-bold">1. Data Pemohon</h5>
<p class="font-caption text-caption text-secondary">Sedang Berlangsung</p>
</li>
<li class="pl-6 relative">
<span class="absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-surface-variant ring-4 ring-surface-container-lowest border border-outline-variant"></span>
<h5 class="font-label-md text-label-md text-on-surface-variant">2. Informasi Kegiatan</h5>
<p class="font-caption text-caption text-outline">Menunggu</p>
</li>
<li class="pl-6 relative">
<span class="absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-surface-variant ring-4 ring-surface-container-lowest border border-outline-variant"></span>
<h5 class="font-label-md text-label-md text-on-surface-variant">3. Dokumen Persyaratan</h5>
<p class="font-caption text-caption text-outline">Menunggu</p>
</li>
</ol>
</div>
</aside>
<!-- Form Canvas -->
<div class="flex-1 flex flex-col gap-stack-lg">
<form id="layanan-form" class="flex flex-col gap-stack-lg" enctype="multipart/form-data">
<input type="hidden" name="jenis_layanan_slug" value="{{ $jenisLayanan->slug ?? '' }}" />
<!-- Section 1: Data Pemohon -->
<div id="step-1" class="step-content bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border-t-4 border-primary p-6 md:p-8 flex flex-col gap-stack-md">
<h2 class="font-title-lg text-title-lg text-primary flex items-center gap-2 border-b border-surface-variant pb-3">
<span class="material-symbols-outlined" data-icon="person">person</span>
                        1. Data Pemohon
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md mt-2">
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Email <span class="text-error">*</span></label>
<input name="email" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" placeholder="Masukkan alamat email aktif" type="email" required value="{{ auth()->check() ? auth()->user()->email : '' }}">
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Jumlah Peserta <span class="text-error">*</span></label>
<div class="flex gap-4 mt-1">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="jumlah_peserta" value="Individu (1 Orang)" class="text-secondary focus:ring-secondary" checked onchange="togglePeserta(this.value)">
        <span class="font-body-md text-body-md text-on-surface">Individu (1 Orang)</span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="radio" name="jumlah_peserta" value="Kelompok (> 1 Orang)" class="text-secondary focus:ring-secondary" onchange="togglePeserta(this.value)">
        <span class="font-body-md text-body-md text-on-surface">Kelompok (Lebih dari 1 Orang)</span>
    </label>
</div>
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Atas Nama <span class="text-error">*</span></label>
<input name="atas_nama" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" placeholder="Masukkan atas nama" type="text" required value="{{ auth()->check() ? auth()->user()->nama : '' }}">
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Nomor WhatsApp Aktif <span class="text-error">*</span></label>
<input name="no_hp" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" placeholder="Masukkan nomor HP/WhatsApp aktif" type="tel" required value="{{ auth()->check() ? auth()->user()->no_hp : '' }}">
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Asal Institusi Pendidikan <span class="text-error">*</span></label>
<p class="font-caption text-caption text-on-surface-variant">(Kampus / Universitas / Perguruan Tinggi)</p>
<input name="asal_instansi" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" placeholder="Contoh: Universitas Indonesia" type="text" required value="{{ auth()->check() ? auth()->user()->asal_instansi : '' }}">
</div>
</div>
</div>
<div id="step-2" class="step-content hidden bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border-t-4 border-primary p-6 md:p-8 flex flex-col gap-stack-md">
<h2 class="font-title-lg text-title-lg text-primary flex items-center gap-2 border-b border-surface-variant pb-3">
<span class="material-symbols-outlined" data-icon="work">work</span>
                        2. Informasi Kegiatan
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md mt-2">
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Waktu Pelaksanaan KKN <span class="text-error">*</span></label>
<p class="font-caption text-caption text-on-surface-variant">Contoh: 17 Agustus 2021 s.d. 17 Oktober 2021.<br/>Masa berlaku maksimal 3 (tiga) bulan. Apabila kegiatan berlangsung lebih dari 3 (tiga) bulan, wajib mengajukan perpanjangan dengan mencantumkan surat rekomendasi yang telah diterbitkan sebelumnya.</p>
</div>
<div class="flex flex-col gap-stack-sm">
<label class="font-label-md text-label-md text-on-surface">Tanggal Mulai <span class="text-error">*</span></label>
<input name="tanggal_mulai" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" type="date" required value="{{ date('Y-m-d') }}">
</div>
<div class="flex flex-col gap-stack-sm">
<label class="font-label-md text-label-md text-on-surface">Tanggal Selesai <span class="text-error">*</span></label>
<input name="tanggal_selesai" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" type="date" required>
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<label class="font-label-md text-label-md text-on-surface">Tempat Pelaksanaan KKN <span class="text-error">*</span></label>
<input name="tempat_kegiatan" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" placeholder="Masukkan lokasi atau dinas tujuan KKN" type="text" required list="instansi-list">
</div>
</div>
</div>
<!-- Section 3: Dokumen Persyaratan -->
<div id="step-3" class="step-content hidden bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border-t-4 border-primary p-6 md:p-8 flex flex-col gap-stack-md">
<h2 class="font-title-lg text-title-lg text-primary flex items-center gap-2 border-b border-surface-variant pb-3">
<span class="material-symbols-outlined" data-icon="folder_open">folder_open</span>
                        3. Dokumen Persyaratan
                    </h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md mt-2">
<label class="flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden">
<input type="file" name="file_ktp" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">badge</span>
<span class="font-label-md text-label-md text-primary mt-2">Upload KTP</span>
<span class="font-caption text-caption text-outline">Format file: PDF. Wajib *</span>
</label>
<label class="flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden">
<input type="file" name="file_ktm" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">id_card</span>
<span class="font-label-md text-label-md text-primary mt-2">Upload Kartu Mahasiswa</span>
<span class="font-caption text-caption text-outline">Format file: PDF. Wajib *</span>
</label>
<label class="flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden">
<input type="file" name="file_surat_permohonan" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">draft</span>
<span class="font-label-md text-label-md text-primary mt-2">Surat Permohonan yang Ditujukan kepada Kepala Badan Kesbangpol</span>
<span class="font-caption text-caption text-outline">Format file: PDF. Wajib *</span>
</label>
<label class="flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden">
<input type="file" name="file_surat_lokasi" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">assignment</span>
<span class="font-label-md text-label-md text-primary mt-2">Surat dari Lokasi KKN</span>
<span class="font-caption text-caption text-outline">Surat harus memiliki bukti tanda terima koordinasi berupa paraf atau tanda tangan yang dibubuhi cap/stempel. Format file: PDF. Wajib *</span>
</label>
<label class="flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden md:col-span-2">
<input type="file" name="file_proposal" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">menu_book</span>
<span class="font-label-md text-label-md text-primary mt-2">Proposal / Lampiran Kegiatan KKN</span>
<span class="font-caption text-caption text-outline">Format file: PDF. Wajib *</span>
</label>
<label id="container-file-daftar-peserta" class="hidden flex flex-col gap-stack-sm p-4 border-2 border-dashed border-outline-variant rounded-xl bg-surface-bright hover:bg-surface-container-low transition-colors text-center cursor-pointer relative overflow-hidden">
<input type="file" name="file_daftar_peserta" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
<span class="material-symbols-outlined text-4xl text-secondary mx-auto">group</span>
<span class="font-label-md text-label-md text-primary mt-2">Upload Daftar Nama Peserta</span>
<span class="font-caption text-caption text-outline">Format file: PDF. Wajib jika kelompok *</span>
</label>
<div class="flex flex-col gap-stack-sm md:col-span-2 mt-4">
<label class="font-label-md text-label-md text-on-surface">Keterangan</label>
<p class="font-caption text-caption text-on-surface-variant">Diisi apabila terdapat informasi tambahan yang perlu disampaikan.</p>
<textarea name="keterangan" class="rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface min-h-[100px]" placeholder="Tambahkan keterangan lain jika diperlukan"></textarea>
</div>
</div>
</div>
<!-- Actions -->
<div class="flex justify-between items-center w-full mt-stack-sm pt-stack-md border-t border-surface-variant">
    <button type="button" id="btn-prev" class="font-label-md text-label-md text-on-surface-variant bg-surface-container-high px-6 py-3 rounded-lg hover:bg-surface-variant transition-colors hidden" onclick="prevStep()">Kembali</button>
    <div class="ml-auto flex gap-3">
        <button type="button" id="btn-next" class="font-label-md text-label-md bg-primary-container text-on-primary rounded-lg px-8 py-3 hover:opacity-90 transition-opacity flex items-center gap-2 shadow-sm" onclick="nextStep()">
            Lanjutkan
            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
        </button>
        <button type="submit" id="btn-submit" class="font-label-md text-label-md bg-primary text-on-primary rounded-lg px-8 py-3 hover:opacity-90 transition-opacity flex items-center gap-2 shadow-sm hidden">
            Kirim Pengajuan
            <span class="material-symbols-outlined text-[18px]">send</span>
        </button>
    </div>
</div>
</form>

</div>
</div>
</main>






<!-- Success Modal -->
<div id="success-modal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/30 backdrop-blur-sm transition-opacity opacity-0 duration-300" id="success-backdrop"></div>
    
    <div id="success-box" class="relative bg-surface-container-lowest rounded-[24px] p-8 md:p-10 max-w-md w-full text-center shadow-[0_10px_40px_rgba(0,0,0,0.1)] opacity-0 scale-90 transition-all duration-500 ease-out">
        
        <!-- Animated Checkmark -->
        <div class="w-24 h-24 bg-[#e6f4ea] rounded-full flex items-center justify-center mx-auto mb-6 transform transition-transform duration-700 delay-150 scale-0" id="success-icon" style="transition-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);">
            <span class="material-symbols-outlined text-[64px] text-[#137333]">check_circle</span>
        </div>
        
        <h3 class="font-headline-md text-headline-md text-primary mb-3">Pengajuan Berhasil!</h3>
        <p class="font-body-md text-body-md text-on-surface-variant mb-8 leading-relaxed">
            Silahkan tunggu <span class="font-bold text-on-surface">1-3 hari kerja</span> dan nantikan informasinya melalui email atau whatsapp.
        </p>
        
        <a href="{{ route('home') }}" class="inline-block w-full py-3.5 bg-primary text-white font-label-md text-label-md rounded-xl hover:bg-primary-container hover:text-white transition-colors shadow-sm">
            Kembali ke Beranda
        </a>
    </div>
</div>

<!-- Wizard Script -->
<script>
    const storageKey = 'form_step_' + window.location.pathname;
    const formDataKey = 'form_data_' + window.location.pathname;
    let currentStep = parseInt(sessionStorage.getItem(storageKey)) || 1;
    const totalSteps = 3;
    const form = document.getElementById('layanan-form');
    
    function saveFormData() {
        const fd = new FormData(form);
        const dataObj = {};
        for (let [key, value] of fd.entries()) {
            if (!(value instanceof File)) {
                dataObj[key] = value;
            }
        }
        sessionStorage.setItem(formDataKey, JSON.stringify(dataObj));
    }

    function syncDateConstraints() {
        const tglMulai = form.querySelector('[name="tanggal_mulai"]');
        const tglSelesai = form.querySelector('[name="tanggal_selesai"]');
        if (tglMulai && tglSelesai) {
            if (tglMulai.value) {
                tglSelesai.min = tglMulai.value;
                if (tglSelesai.value && tglSelesai.value < tglMulai.value) {
                    tglSelesai.value = tglMulai.value;
                }
            }
        }
    }

    function restoreFormData() {
        const savedData = sessionStorage.getItem(formDataKey);
        if (savedData) {
            try {
                const dataObj = JSON.parse(savedData);
                for (let key in dataObj) {
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input && input.type !== 'file') {
                        if (dataObj[key]) {
                            input.value = dataObj[key];
                        }
                    }
                }
            } catch (e) {}
        }
        const tglMulai = form.querySelector('[name="tanggal_mulai"]');
        if (tglMulai && !tglMulai.value) {
            tglMulai.value = new Date().toISOString().split('T')[0];
        }
        syncDateConstraints();
    }

    form.addEventListener('input', saveFormData);
    form.addEventListener('change', saveFormData);

    document.addEventListener('DOMContentLoaded', () => {
        restoreFormData();
        updateWizard();
        const checkedPeserta = document.querySelector('input[name="jumlah_peserta"]:checked');
        if(checkedPeserta) togglePeserta(checkedPeserta.value);

        const tglMulai = form.querySelector('[name="tanggal_mulai"]');
        if (tglMulai) {
            tglMulai.addEventListener('change', syncDateConstraints);
            tglMulai.addEventListener('input', syncDateConstraints);
        }
    });
    
    function updateWizard() {
        // Hide all steps
        for (let i = 1; i <= totalSteps; i++) {
            document.getElementById('step-' + i).classList.add('hidden');
        }
        // Show current step
        document.getElementById('step-' + currentStep).classList.remove('hidden');
        
        // Update Buttons
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');
        
        if (currentStep === 1) {
            btnPrev.classList.add('hidden');
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        } else if (currentStep === totalSteps) {
            btnPrev.classList.remove('hidden');
            btnNext.classList.add('hidden');
            btnSubmit.classList.remove('hidden');
        } else {
            btnPrev.classList.remove('hidden');
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        }
        
        // Update Sidebar Timeline
        const sidebar = document.getElementById('stepper-sidebar');
        if (sidebar) {
            const listItems = sidebar.querySelectorAll('li');
            listItems.forEach((li, index) => {
                const stepNum = index + 1;
                const dot = li.querySelector('span');
                const title = li.querySelector('h5');
                const subtitle = li.querySelector('p');
                
                if (stepNum < currentStep) {
                    // Completed
                    dot.className = 'absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-secondary ring-4 ring-surface-container-lowest';
                    title.className = 'font-label-md text-label-md text-primary font-bold';
                    subtitle.className = 'font-caption text-caption text-secondary';
                    subtitle.textContent = 'Selesai';
                } else if (stepNum === currentStep) {
                    // Active
                    dot.className = 'absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-secondary ring-4 ring-surface-container-lowest';
                    title.className = 'font-label-md text-label-md text-primary font-bold';
                    subtitle.className = 'font-caption text-caption text-secondary';
                    subtitle.textContent = 'Sedang Berlangsung';
                } else {
                    // Pending
                    dot.className = 'absolute -left-[9px] top-1 flex items-center justify-center w-4 h-4 rounded-full bg-surface-variant ring-4 ring-surface-container-lowest border border-outline-variant';
                    title.className = 'font-label-md text-label-md text-on-surface-variant';
                    subtitle.className = 'font-caption text-caption text-outline';
                    subtitle.textContent = 'Menunggu';
                }
            });
        }
        
        // Scroll to top of form
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    
    function nextStep() {
        // Validate inputs in the current step
        const currentStepEl = document.getElementById('step-' + currentStep);
        const inputs = currentStepEl.querySelectorAll('input, select, textarea');
        let isValid = true;
        
        for (let i = 0; i < inputs.length; i++) {
            if (!inputs[i].checkValidity()) {
                inputs[i].reportValidity();
                isValid = false;
                break;
            }
        }

        const tglMulai = form.querySelector('[name="tanggal_mulai"]');
        const tglSelesai = form.querySelector('[name="tanggal_selesai"]');
        if (isValid && tglMulai && tglSelesai && tglMulai.value && tglSelesai.value) {
            if (tglSelesai.value < tglMulai.value) {
                alert('Tanggal Selesai tidak boleh lebih awal dari Tanggal Mulai.');
                tglSelesai.focus();
                isValid = false;
            }
        }
        
        if (isValid && currentStep < totalSteps) {
            currentStep++;
            sessionStorage.setItem(storageKey, currentStep);
            updateWizard();
        }
    }
    
    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            sessionStorage.setItem(storageKey, currentStep);
            updateWizard();
        }
    }

                // Handle File Input Preview
    document.querySelectorAll('input[type="file"]').forEach(input => {
        const label = input.closest('label');
        if (!label) return;
        const fileNameSpan = label.querySelector('span:nth-of-type(2)');
        const iconSpan = label.querySelector('.material-symbols-outlined');
        
        if (!fileNameSpan || !iconSpan) return;

        const originalText = fileNameSpan.textContent;
        const originalIcon = iconSpan.textContent;

        input.addEventListener('change', function(e) {
            if (this.files && this.files.length > 0) {
                const fileName = this.files[0].name;
                fileNameSpan.textContent = fileName;
                fileNameSpan.classList.remove('text-primary');
                fileNameSpan.classList.add('text-[#137333]', 'font-bold');
                
                iconSpan.textContent = 'check_circle';
                iconSpan.classList.remove('text-secondary');
                iconSpan.classList.add('text-[#137333]');
                
                label.classList.remove('border-dashed', 'border-outline-variant', 'bg-surface-bright');
                label.classList.add('border-solid', 'border-[#137333]', 'bg-[#e6f4ea]');
            } else {
                fileNameSpan.textContent = originalText;
                fileNameSpan.classList.add('text-primary');
                fileNameSpan.classList.remove('text-[#137333]', 'font-bold');
                
                iconSpan.textContent = originalIcon;
                iconSpan.classList.add('text-secondary');
                iconSpan.classList.remove('text-[#137333]');
                
                label.classList.add('border-dashed', 'border-outline-variant', 'bg-surface-bright');
                label.classList.remove('border-solid', 'border-[#137333]', 'bg-[#e6f4ea]');
            }
        });
    });

    // Handle Form Submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btnSubmit = document.getElementById('btn-submit');
        const originalText = btnSubmit.innerHTML;
        btnSubmit.innerHTML = 'Mengirim...';
        btnSubmit.disabled = true;

        const formData = new FormData(form);
        
        fetch('{{ route("layanan.submit") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
            
            if (data.status === 'success') {
                sessionStorage.removeItem(storageKey);
                sessionStorage.removeItem(formDataKey);
                const modal = document.getElementById('success-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        const backdrop = document.getElementById('success-backdrop');
                        if (backdrop) backdrop.classList.replace('opacity-0', 'opacity-100');
                        const box = document.getElementById('success-box');
                        if (box) {
                            box.classList.replace('opacity-0', 'opacity-100');
                            box.classList.replace('scale-90', 'scale-100');
                        }
                        const icon = document.getElementById('success-icon');
                        if (icon) icon.classList.replace('scale-0', 'scale-100');
                    }, 10);
                    setTimeout(() => {
                        window.location.href = "{{ route('home') }}";
                    }, 3500);
                } else {
                    window.location.href = "{{ route('home') }}";
                }
            } else {
                alert(data.message || 'Terjadi kesalahan saat memproses data.');
            }
        })
        .catch(error => {
            btnSubmit.innerHTML = originalText;
            btnSubmit.disabled = false;
            alert('Terjadi kesalahan koneksi.');
            console.error(error);
        });
    });

    function togglePeserta(value) {
        const fileDaftarPesertaContainer = document.getElementById('container-file-daftar-peserta');
        const atasNamaInput = document.querySelector('input[name="atas_nama"]');
        let labelAtasNama = null;
        
        if (atasNamaInput) {
            labelAtasNama = atasNamaInput.parentElement.querySelector('label');
        }

        if (value.includes('Kelompok')) {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.remove('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.setAttribute('required', 'required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Nama Perwakilan / Ketua Kelompok <span class="text-error">*</span>';
            }
        } else {
            if(fileDaftarPesertaContainer) {
                fileDaftarPesertaContainer.classList.add('hidden');
                const fileInput = fileDaftarPesertaContainer.querySelector('input[type="file"]');
                if(fileInput) fileInput.removeAttribute('required');
            }
            if(labelAtasNama) {
                labelAtasNama.innerHTML = 'Atas Nama <span class="text-error">*</span>';
            }
        }
    }
</script>

@endsection








@include('pelayanan.partials.datalist_instansi')
