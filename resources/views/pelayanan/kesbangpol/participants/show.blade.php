@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<!-- Top App Bar (Desktop & Mobile) -->
<header class="h-16 sticky top-0 md:fixed md:top-0 md:right-0 md:left-72 z-40 bg-surface-container-lowest dark:bg-surface-container-low border-b border-outline-variant shadow-sm flex justify-between items-center px-gutter w-full md:w-[calc(100%-18rem)]">
<div class="flex items-center gap-4">
<!-- Mobile Menu Button -->
<button class="md:hidden text-primary">
<span class="material-symbols-outlined">menu</span>
</button>
<h2 class="text-title-lg font-title-lg font-semibold text-on-surface hidden md:block">Admin Kesbangpol</h2>
</div>
<div class="flex items-center gap-4">
<div class="relative hidden sm:block">
<input class="pl-10 pr-4 py-2 bg-surface-container rounded-full text-label-md font-label-md focus:outline-none focus:ring-2 focus:ring-secondary text-on-surface w-64 border-none placeholder:text-on-surface-variant" placeholder="Cari..." type="text"/>
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
</div>
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container-high transition-colors p-2 rounded-full cursor-pointer active:scale-95">
<span class="material-symbols-outlined">notifications</span>
</button>
<button class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container-high transition-colors p-2 rounded-full cursor-pointer active:scale-95 hidden sm:block">
<span class="material-symbols-outlined">help</span>
</button>
<div class="w-8 h-8 rounded-full bg-secondary-container overflow-hidden ml-2 cursor-pointer border-2 border-primary-fixed">
<img alt="Admin Profile" class="w-full h-full object-cover" data-alt="A professional headshot of a government administrative official in modern light mode lighting. High quality corporate style photography. The subject is well lit and looking forward." src="https://lh3.googleusercontent.com/aida-public/AB6AXuCTj90F4p2XF5GYWMt1WRC2NrzOKu67d1UApbpWHsGHAuv1WRyFc5f4AXIMRc05Hy1gUzTxGQ2if4TgNYXpyEwSgsIIZS2ufwsES-EWLlWDf8x0powwzIkkPmWYe5imNWLlJW_GByiI-C2B-21ogtJLZNUhBr23gsztsfPJiA8lvcQDEUE6U2Lbhs7R7P2kPE5qvI8afjdtqcGlYuDZe59PDZocghfugQ_QKo-Ugcxmj2Jp50gi5gq5"/>
</div>
</div>
</header>
<!-- Page Content -->
<div class="p-margin-mobile md:p-margin-desktop md:pt-[calc(2.5rem+4rem)] pt-20 max-w-container-max mx-auto w-full flex-1">
<!-- Breadcrumb -->
<nav class="text-label-md font-label-md text-on-surface-variant mb-6 flex items-center gap-2">
<a class="hover:text-primary transition-colors" href="#">Dashboard</a>
<span class="material-symbols-outlined text-sm">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">Manajemen Peserta</a>
<span class="material-symbols-outlined text-sm">chevron_right</span>
<span class="text-primary font-semibold">Detail Peserta</span>
</nav>
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
<div>
<h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface">Detail Akun Peserta</h1>
<p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola informasi dan status akun peserta magang.</p>
</div>
<button class="bg-primary-container text-on-primary font-label-md text-label-md rounded-lg px-6 py-3 flex items-center gap-2 hover:bg-primary-container/90 transition-colors shadow-sm active:scale-95">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">manage_accounts</span>
                    Kelola Status Akun
                 </button>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
<!-- Left Column: Header Card & Data -->
<div class="lg:col-span-2 space-y-stack-lg">
<!-- Header Card -->
<div class="bg-surface-container-lowest rounded-xl p-6 card-shadow border-t-4 border-primary-container relative overflow-hidden">
<!-- Decorative bg -->
<div class="absolute -right-10 -top-10 w-40 h-40 bg-surface-variant rounded-full opacity-50 pointer-events-none"></div>
<div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 relative z-10">
<div class="w-24 h-24 rounded-full bg-surface-container-high border-4 border-white shadow-sm overflow-hidden shrink-0">
<img alt="Foto Arjuna Olanda Putra" class="w-full h-full object-cover" data-alt="A professional, bright portrait photo of a university student in Indonesia. The student looks optimistic and professional. Light modern government interface aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuC88DAxnlEJow_KDTqs-iTnZbcCBuRldsiae2Q6zR0gOH3NIG1SlrO5KPJP-zCVIN50-Xb3_G0YsdS_uYYy7elhtA0Gis3gVaZecxfANcVqkZOuMvtHfAQzImG_Ir_Ox1vNt6rBNUWFw-Cx0Kuq5Uk8zbc4eDDG17cyj-4r_XUVJkabozpCWoRNwG0uD3B1ZZ0dUiRjB0QLXUqOyiz2ZsqInIIZw-ubc4yyZuWsoekAHVXqDJcnNbT3"/>
</div>
<div class="flex-1 space-y-2">
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
<h2 class="text-headline-md font-headline-md text-on-surface">Arjuna Olanda Putra</h2>
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
                                        Aktif
                                    </span>
</div>
<div class="flex items-center gap-2 text-on-surface-variant text-body-md font-body-md">
<span class="material-symbols-outlined text-sm">school</span>
<span>IPB University</span>
</div>
<div class="flex flex-wrap gap-3 mt-3">
<div class="bg-surface-container rounded-md px-3 py-1.5 flex items-center gap-2 text-label-md font-label-md">
<span class="text-on-surface-variant">Status Pengajuan:</span>
<span class="text-secondary font-semibold">Menunggu Verifikasi</span>
</div>
<div class="bg-surface-container rounded-md px-3 py-1.5 flex items-center gap-2 text-label-md font-label-md">
<span class="text-on-surface-variant">Tahap:</span>
<span class="text-primary-container font-semibold">Kesbangpol</span>
</div>
</div>
</div>
</div>
</div>
<!-- Data Peserta Tab -->
<div class="bg-surface-container-lowest rounded-xl p-6 card-shadow">
<div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
<span class="material-symbols-outlined text-primary-container" style="font-variation-settings: 'FILL' 1;">person</span>
<h3 class="text-title-lg font-title-lg text-on-surface">Data Pribadi &amp; Akademik</h3>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Nama Lengkap</label>
<span class="text-body-md font-body-md text-on-surface font-medium">Arjuna Olanda Putra</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Asal Instansi</label>
<span class="text-body-md font-body-md text-on-surface font-medium">IPB University</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Program Studi</label>
<span class="text-body-md font-body-md text-on-surface font-medium">Ilmu Komputer</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Semester</label>
<span class="text-body-md font-body-md text-on-surface font-medium">6 (Tiga)</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">No. Telepon / WhatsApp</label>
<span class="text-body-md font-body-md text-on-surface font-medium">+62 812-3456-7890</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Email Akun</label>
<span class="text-body-md font-body-md text-on-surface font-medium">arjuna.putra@apps.ipb.ac.id</span>
</div>
</div>
</div>
<!-- Informasi Pengajuan -->
<div class="bg-surface-container-lowest rounded-xl p-6 card-shadow border-t-4 border-secondary">
<div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
<span class="material-symbols-outlined text-secondary" style="font-variation-settings: 'FILL' 1;">assignment</span>
<h3 class="text-title-lg font-title-lg text-on-surface">Informasi Pengajuan Magang Saat Ini</h3>
</div>
<div class="bg-surface-container-low rounded-lg p-5 border border-outline-variant/20">
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div class="space-y-4">
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Nomor Pengajuan</label>
<span class="text-body-md font-body-md text-primary font-mono font-semibold">MAG-2026-08-0142</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Tanggal Mulai Rencana</label>
<span class="text-body-md font-body-md text-on-surface font-medium">01 September 2026</span>
</div>
</div>
<div class="space-y-4">
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Tujuan Penempatan (Harapan)</label>
<span class="text-body-md font-body-md text-on-surface font-medium">Dinas Komunikasi dan Informatika</span>
</div>
<div class="flex flex-col gap-1">
<label class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Durasi</label>
<span class="text-body-md font-body-md text-on-surface font-medium">3 Bulan</span>
</div>
</div>
</div>
<div class="mt-6 flex justify-end">
<a class="text-secondary hover:text-secondary-container text-label-md font-label-md font-semibold flex items-center gap-1 transition-colors" href="#">
                                    Lihat Detail Pengajuan Penuh
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
</a>
</div>
</div>
</div>
</div>
<!-- Right Column: Riwayat Akun -->
<div class="lg:col-span-1 space-y-stack-lg">
<div class="bg-surface-container-lowest rounded-xl p-6 card-shadow h-full border-t-4 border-outline-variant">
<div class="flex items-center gap-2 mb-6 pb-4 border-b border-outline-variant/30">
<span class="material-symbols-outlined text-on-surface-variant">history</span>
<h3 class="text-title-lg font-title-lg text-on-surface">Riwayat Akun</h3>
</div>
<div class="relative pl-6 border-l-2 border-surface-variant space-y-8 pb-4">
<!-- Timeline Item 1 -->
<div class="relative">
<div class="absolute -left-[31px] w-4 h-4 bg-emerald-500 rounded-full border-4 border-surface-container-lowest shadow-sm"></div>
<div class="flex flex-col gap-1">
<span class="text-caption font-caption text-on-surface-variant">23 Agustus 2026, 14:15 WIB</span>
<p class="text-label-md font-label-md text-on-surface font-semibold">Pengajuan Diterima</p>
<p class="text-caption font-caption text-on-surface-variant mt-1 bg-surface rounded p-2 border border-outline-variant/10">Sistem: Status pengajuan berubah menjadi "Menunggu Verifikasi" Kesbangpol.</p>
</div>
</div>
<!-- Timeline Item 2 -->
<div class="relative">
<div class="absolute -left-[31px] w-4 h-4 bg-primary-container rounded-full border-4 border-surface-container-lowest shadow-sm"></div>
<div class="flex flex-col gap-1">
<span class="text-caption font-caption text-on-surface-variant">23 Agustus 2026, 10:35 WIB</span>
<p class="text-label-md font-label-md text-on-surface font-semibold">Profil Dilengkapi</p>
<p class="text-caption font-caption text-on-surface-variant mt-1">Peserta melengkapi data akademik dan mengunggah dokumen persyaratan.</p>
</div>
</div>
<!-- Timeline Item 3 -->
<div class="relative">
<div class="absolute -left-[31px] w-4 h-4 bg-outline-variant rounded-full border-4 border-surface-container-lowest shadow-sm"></div>
<div class="flex flex-col gap-1">
<span class="text-caption font-caption text-on-surface-variant">23 Agustus 2026, 10:30 WIB</span>
<p class="text-label-md font-label-md text-on-surface font-semibold">Akun Dibuat</p>
<div class="mt-1 flex items-center gap-2">
<span class="text-caption font-caption text-on-surface-variant">Status Awal:</span>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-100 text-emerald-800">
                                            Aktif
                                        </span>
</div>
</div>
</div>
<!-- Fade Out Gradient at bottom of timeline -->
<div class="absolute bottom-0 left-[-2px] w-1 h-12 bg-gradient-to-t from-surface-container-lowest to-transparent"></div>
</div>
<button class="w-full mt-6 py-2 border border-outline-variant/50 rounded-lg text-label-md font-label-md text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors flex items-center justify-center gap-2">
                            Muat Lebih Banyak
                            <span class="material-symbols-outlined text-sm">expand_more</span>
</button>
</div>
</div>
</div>
</div>

@endsection
