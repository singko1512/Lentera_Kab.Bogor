@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
<!-- Page Header -->
<div class="mb-stack-lg">
<h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Pengajuan Magang</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Kelola seluruh pengajuan magang yang masuk dengan mudah.</p>
</div>

<!-- Filters & Search Action Bar -->
<div class="bg-white rounded-2xl shadow-soft p-6 mb-stack-lg flex flex-col lg:flex-row gap-5 items-end lg:items-center justify-between border border-outline-variant/30">
<div class="flex flex-col lg:flex-row gap-5 w-full lg:w-auto">
<!-- Search Field -->
<div class="flex flex-col gap-2 w-full lg:w-64">
<label class="text-label-md font-label-md text-on-surface-variant font-medium">Cari Peserta</label>
<div class="relative group">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline group-focus-within:text-primary transition-colors">search</span>
<input class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-xl focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-md placeholder-outline transition-all" placeholder="Cari nama peserta..." type="text"/>
</div>
</div>
<!-- Filter: Status -->
<div class="flex flex-col gap-2 w-full lg:w-48">
<label class="text-label-md font-label-md text-on-surface-variant font-medium">Status</label>
<select class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-xl focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-md appearance-none transition-all cursor-pointer">
<option>Semua Status</option>
<option>Menunggu Verifikasi</option>
<option>Sedang Diproses</option>
<option>Perlu Perbaikan</option>
<option>Diterima</option>
<option>Ditolak</option>
</select>
</div>

<!-- Filter: Institusi -->
<div class="flex flex-col gap-2 w-full lg:w-48">
<label class="text-label-md font-label-md text-on-surface-variant font-medium">Asal Institusi</label>
<select class="w-full px-4 py-2.5 bg-surface-container-low border border-outline-variant/50 rounded-xl focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary outline-none text-body-md appearance-none transition-all cursor-pointer">
<option>Semua Institusi</option>
<option>Universitas Indonesia</option>
<option>IPB University</option>
</select>
</div>
</div>
<!-- Action Button -->
<button class="bg-primary text-white px-6 py-2.5 rounded-xl font-label-md font-semibold hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all w-full lg:w-auto flex items-center justify-center gap-2">
<span class="material-symbols-outlined text-[18px]">filter_list</span>
    Terapkan Filter
</button>
</div>

<!-- Data Table Card -->
<div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
<div class="overflow-x-auto w-full">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant/40">
<th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Asal Instansi</th>

<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Tgl Pengajuan</th>
<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
<th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-center">Aksi</th>
</tr>
</thead>
<tbody class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant/30">
<!-- Row 1 -->
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer">
<td class="p-4 pl-6">
<div class="font-semibold text-on-surface group-hover:text-primary transition-colors">Budi Santoso</div>
<div class="text-[12px] text-on-surface-variant mt-0.5">NIM: 12345678</div>
</td>
<td class="p-4 text-on-surface-variant">Universitas Pakuan</td>

<td class="p-4 text-on-surface-variant text-sm">12 Okt 2023</td>
<td class="p-4">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#FEF3C7] text-[#92400E]">
    <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
    Menunggu Verifikasi
</span>
</td>
<td class="p-4 pr-6 text-center">
<a href="{{ route('kesbangpol.layanan.show', 1) }}" class="inline-block p-2 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-full transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined">visibility</span>
</a>
</td>
</tr>
<!-- Row 2 -->
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer">
<td class="p-4 pl-6">
<div class="font-semibold text-on-surface group-hover:text-primary transition-colors">Ayu Lestari</div>
<div class="text-[12px] text-on-surface-variant mt-0.5">NIM: 87654321</div>
</td>
<td class="p-4 text-on-surface-variant">IPB University</td>

<td class="p-4 text-on-surface-variant text-sm">10 Okt 2023</td>
<td class="p-4">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#E0F2FE] text-[#075985]">
    <span class="w-1.5 h-1.5 rounded-full bg-[#0EA5E9]"></span>
    Sedang Diproses
</span>
</td>
<td class="p-4 pr-6 text-center">
<a href="{{ route('kesbangpol.layanan.show', 1) }}" class="inline-block p-2 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-full transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined">visibility</span>
</a>
</td>
</tr>
<!-- Row 3 -->
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer">
<td class="p-4 pl-6">
<div class="font-semibold text-on-surface group-hover:text-primary transition-colors">Reza Pratama</div>
<div class="text-[12px] text-on-surface-variant mt-0.5">NIM: 11223344</div>
</td>
<td class="p-4 text-on-surface-variant">Universitas Indonesia</td>

<td class="p-4 text-on-surface-variant text-sm">08 Okt 2023</td>
<td class="p-4">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#FEF3C7] text-[#92400E]">
    <span class="w-1.5 h-1.5 rounded-full bg-[#F59E0B]"></span>
    Perlu Perbaikan
</span>
</td>
<td class="p-4 pr-6 text-center">
<a href="{{ route('kesbangpol.layanan.show', 1) }}" class="inline-block p-2 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-full transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined">visibility</span>
</a>
</td>
</tr>
<!-- Row 4 -->
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer">
<td class="p-4 pl-6">
<div class="font-semibold text-on-surface group-hover:text-primary transition-colors">Siti Nurhaliza</div>
<div class="text-[12px] text-on-surface-variant mt-0.5">NIM: 55667788</div>
</td>
<td class="p-4 text-on-surface-variant">UIN Syarif Hidayatullah</td>

<td class="p-4 text-on-surface-variant text-sm">05 Okt 2023</td>
<td class="p-4">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#D1FAE5] text-[#065F46]">
    <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
    Diterima
</span>
</td>
<td class="p-4 pr-6 text-center">
<a href="{{ route('kesbangpol.layanan.show', 1) }}" class="inline-block p-2 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-full transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined">visibility</span>
</a>
</td>
</tr>
<!-- Row 5 -->
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer">
<td class="p-4 pl-6">
<div class="font-semibold text-on-surface group-hover:text-primary transition-colors">Dimas Anggara</div>
<div class="text-[12px] text-on-surface-variant mt-0.5">NIM: 99887766</div>
</td>
<td class="p-4 text-on-surface-variant">Universitas Gunadarma</td>

<td class="p-4 text-on-surface-variant text-sm">01 Okt 2023</td>
<td class="p-4">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-error-container text-error">
    <span class="w-1.5 h-1.5 rounded-full bg-error"></span>
    Ditolak
</span>
</td>
<td class="p-4 pr-6 text-center">
<a href="{{ route('kesbangpol.layanan.show', 1) }}" class="inline-block p-2 text-on-surface-variant hover:text-primary hover:bg-primary/10 rounded-full transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined">visibility</span>
</a>
</td>
</tr>
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="px-6 py-4 border-t border-outline-variant/40 bg-surface-container-low/50 flex flex-col sm:flex-row items-center justify-between gap-4">
<span class="text-[13px] font-medium text-on-surface-variant">Menampilkan 1-5 dari 45 pengajuan</span>
<div class="flex gap-2">
<button class="p-2 border border-outline-variant/50 rounded-lg hover:bg-white hover:text-primary hover:shadow-sm transition-all disabled:opacity-50 bg-white" disabled="">
<span class="material-symbols-outlined text-[18px]">chevron_left</span>
</button>
<button class="px-3 py-1 border border-primary bg-primary text-white rounded-lg text-sm font-semibold shadow-sm">1</button>
<button class="px-3 py-1 border border-outline-variant/50 bg-white hover:bg-surface-container-low hover:text-primary rounded-lg text-sm font-medium transition-colors">2</button>
<button class="px-3 py-1 border border-outline-variant/50 bg-white hover:bg-surface-container-low hover:text-primary rounded-lg text-sm font-medium transition-colors">3</button>
<button class="p-2 border border-outline-variant/50 rounded-lg hover:bg-white hover:text-primary hover:shadow-sm transition-all bg-white">
<span class="material-symbols-outlined text-[18px]">chevron_right</span>
</button>
</div>
</div>
</div>
</div>

@endsection
