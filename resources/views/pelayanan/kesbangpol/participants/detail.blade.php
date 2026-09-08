@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto">
<!-- Breadcrumb & Back Button -->
<div class="mb-6 flex flex-col gap-4">
<nav aria-label="Breadcrumb" class="flex items-center text-label-md font-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">Dashboard</a>
<span class="mx-2 text-outline-variant">/</span>
<a class="hover:text-primary transition-colors" href="#">Penempatan Peserta</a>
<span class="mx-2 text-outline-variant">/</span>
<span class="text-on-surface font-semibold">Detail Peserta</span>
</nav>
<div class="flex items-center gap-4 mt-2">
<button onclick="history.back()" aria-label="Kembali" class="flex items-center justify-center w-10 h-10 rounded-full border border-outline-variant/50 bg-white hover:bg-surface-container hover:shadow-sm transition-all text-primary shrink-0">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
</button>
<h2 class="text-headline-md font-headline-md text-on-surface md:text-headline-lg tracking-tight hidden md:block">Detail Penempatan Peserta</h2>
</div>
</div>
<!-- Summary Header Card -->
<div class="bg-white rounded-2xl shadow-soft p-6 mb-8 border border-outline-variant/30 relative overflow-hidden">
<div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
<div class="flex items-center gap-6">
<div class="w-20 h-20 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
<span class="material-symbols-outlined text-4xl text-primary">person</span>
</div>
<div>
<h3 class="text-headline-md font-headline-md text-on-surface font-bold mb-1">Ahmad Fauzi</h3>
<p class="text-body-md font-body-md text-on-surface-variant flex items-center gap-2">
<span class="material-symbols-outlined text-[18px]">school</span>
                                    Universitas Indonesia
                                </p>
</div>
</div>
<div class="flex flex-col md:items-end gap-3">
<div class="inline-flex items-center gap-2 px-4 py-2 bg-[#D1FAE5] text-[#065F46] rounded-full text-label-md font-label-md font-bold">
<span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                DITERIMA
                            </div>
<p class="text-caption font-caption text-on-surface-variant">Diterima pada: <span class="font-semibold text-on-surface">12 Oktober 2023</span></p>
</div>
</div>
</div>

<!-- Main Layout Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Left Column: Primary Details -->
<div class="lg:col-span-2 flex flex-col gap-8">
<!-- Data Peserta Section -->
<div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
<h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
<span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">contact_page</span>
                        Data Peserta
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
<div class="flex flex-col gap-1">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Ahmad Fauzi</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Asal Instansi</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Universitas Indonesia</span>
</div>
<div class="flex flex-col gap-1 md:col-span-2">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Program Studi / Jurusan</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Ilmu Administrasi Negara</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Nomor Telepon</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">0812-3456-7890</span>
</div>
<div class="flex flex-col gap-1">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Email</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">ahmad.fauzi@ui.ac.id</span>
</div>
</div>
</div>
<!-- Dokumen Pengajuan Section -->
<div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
<h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
<span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">folder_open</span> Dokumen
</h3>
<div class="bg-surface-container-low rounded-xl border border-outline-variant/40 overflow-hidden divide-y divide-outline-variant/40">
<!-- Doc 1 -->
<div class="p-4 flex items-center justify-between hover:bg-white hover:shadow-sm transition-all group">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">description</span>
</div>
<div>
<p class="text-body-md font-body-md text-on-surface font-semibold">Surat Pengantar Institusi</p>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D1FAE5] text-[#065F46] uppercase tracking-wide mt-1">Valid</span>
</div>
</div>
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Lihat Dokumen">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
<!-- Doc 2 -->
<div class="p-4 flex items-center justify-between hover:bg-white hover:shadow-sm transition-all group">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">badge</span>
</div>
<div>
<p class="text-body-md font-body-md text-on-surface font-semibold">Kartu Tanda Penduduk (KTP)</p>
<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-[#D1FAE5] text-[#065F46] uppercase tracking-wide mt-1">Valid</span>
</div>
</div>
<button class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Lihat Dokumen">
<span class="material-symbols-outlined text-[20px]">visibility</span>
</button>
</div>
</div>
</div>
</div>

<!-- Right Column: Secondary Details -->
<div class="flex flex-col gap-8">
<!-- Informasi Penempatan Section -->
<div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
<h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
<span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">location_on</span>
                        Informasi Penempatan
                    </h3>
<div class="flex flex-col gap-4">
<div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Dinas Tujuan</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Bakesbangpol Kabupaten Bogor</span>
</div>
<div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Bidang Magang</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">Bidang Kewaspadaan Nasional</span>
</div>
<div class="grid grid-cols-2 gap-4">
<div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Mulai</span>
<span class="text-body-md font-body-md text-on-surface font-semibold">01 Nov 2023</span>
</div>
<div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
<span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Selesai</span>
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
