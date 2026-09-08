@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto">
<!-- Breadcrumb & Back Button -->
<div class="mb-stack-md flex flex-col gap-stack-sm">
<nav aria-label="Breadcrumb" class="flex items-center text-label-md font-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">Dashboard</a>
<span class="mx-2 text-outline-variant">/</span>
<a class="hover:text-primary transition-colors" href="#">Penempatan Peserta</a>
<span class="mx-2 text-outline-variant">/</span>
<span class="text-on-surface font-semibold">Detail Peserta</span>
</nav>
<div class="flex items-center gap-4 mt-2">
<button onclick="history.back()" aria-label="Kembali" class="flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant bg-surface hover:bg-surface-container transition-colors text-primary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
</button>
<h2 class="text-headline-md font-headline-md text-on-surface md:text-headline-lg font-headline-lg hidden md:block">Detail Penempatan Peserta</h2>
</div>
</div>
<!-- Summary Header Card -->
<div class="glass-card rounded-xl p-6 mb-stack-lg border-t-4 border-t-secondary relative overflow-hidden">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
<div class="flex items-start gap-6">
<div class="w-20 h-20 rounded-full bg-surface-container-high border-2 border-primary-container flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
<span class="material-symbols-outlined text-4xl text-primary-container">person</span>
</div>
<div>
<h3 class="text-headline-md font-headline-md text-primary mb-1">Ahmad Fauzi</h3>
<p class="text-body-md font-body-md text-on-surface-variant flex items-center gap-2">
<span class="material-symbols-outlined text-sm">school</span>
                                    Universitas Indonesia
                                </p>
</div>
</div>
<div class="flex flex-col md:items-end gap-3">
<div class="inline-flex items-center gap-2 px-4 py-2 bg-[#dcfce7] text-[#166534] rounded-full text-label-md font-label-md font-bold border border-[#bbf7d0]">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                DITERIMA
                            </div>
<p class="text-caption font-caption text-on-surface-variant">Diterima pada: <span class="font-medium text-on-surface">12 Oktober 2023</span></p>
</div>
</div>
</div>
<!-- Main Layout Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
<!-- Left Column: Primary Details -->
<div class="lg:col-span-2 flex flex-col gap-stack-lg">
<!-- Data Peserta Section -->
<div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6 border-t-4 border-primary-container">
<h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
<span class="material-symbols-outlined text-primary-container">contact_page</span>
                        Data Peserta
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
<div class="flex flex-col gap-1">
<span class="text-label-md font-label-md text-on-surface-variant">Nama Lengkap</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Ahmad Fauzi</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-label-md font-label-md text-on-surface-variant">Asal Instansi</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Universitas Indonesia</span>
</div>
<div class="flex flex-col gap-1 md:col-span-2">
<span class="text-label-md font-label-md text-on-surface-variant">Program Studi / Jurusan</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Ilmu Administrasi Negara</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-label-md font-label-md text-on-surface-variant">Nomor Telepon</span>
<span class="text-body-md font-body-md text-on-surface font-medium">0812-3456-7890</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-label-md font-label-md text-on-surface-variant">Email</span>
<span class="text-body-md font-body-md text-on-surface font-medium">ahmad.fauzi@ui.ac.id</span>
</div>
</div>
</div>
<!-- Dokumen Pengajuan Section -->
<div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6">
<h3 class="text-title-lg font-title-lg text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
<span class="material-symbols-outlined text-primary">folder_open</span> Dokumen
</h3>
<div class="bg-surface rounded-xl border border-outline-variant/30 overflow-hidden divide-y divide-outline-variant/20">
<!-- Doc 1 -->
<div class="p-4 flex items-center justify-between hover:bg-surface-bright transition-colors group">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">description</span>
</div>
<div>
<p class="text-body-md font-body-md text-on-surface font-medium">Surat Pengantar Institusi</p>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-[#dcfce7] text-[#166534] uppercase tracking-wide mt-1">Valid</span>
</div>
</div>
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-surface-container" title="Lihat Dokumen">
<span class="material-symbols-outlined text-sm">visibility</span>
</button>
</div>
<!-- Doc 2 -->
<div class="p-4 flex items-center justify-between hover:bg-surface-bright transition-colors group">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">badge</span>
</div>
<div>
<p class="text-body-md font-body-md text-on-surface font-medium">Kartu Tanda Penduduk (KTP)</p>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-[#dcfce7] text-[#166534] uppercase tracking-wide mt-1">Valid</span>
</div>
</div>
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-surface-container" title="Lihat Dokumen">
<span class="material-symbols-outlined text-sm">visibility</span>
</button>
</div>
</div>
</div>
</div>
<!-- Right Column: Secondary Details -->
<div class="flex flex-col gap-stack-lg">
<!-- Informasi Penempatan Section -->
<div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6">
<h3 class="text-title-lg font-title-lg text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
<span class="material-symbols-outlined text-primary-container">location_on</span>
                        Informasi Penempatan
                    </h3>
<div class="flex flex-col gap-4">
<div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
<span class="text-label-md font-label-md text-on-surface-variant">Dinas Tujuan</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Bakesbangpol Kabupaten Bogor</span>
</div>
<div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
<span class="text-label-md font-label-md text-on-surface-variant">Bidang Magang</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Bidang Kewaspadaan Nasional</span>
</div>
<div class="grid grid-cols-2 gap-4">
<div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
<span class="text-label-md font-label-md text-on-surface-variant">Tanggal Mulai</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">01 Nov 2023</span>
</div>
<div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
<span class="text-label-md font-label-md text-on-surface-variant">Tanggal Selesai</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">31 Jan 2024</span>
</div>
</div>
</div>
</div>
</div>
</div>
<div class="pb-12"></div> <!-- Bottom padding -->
</div>

@endsection
