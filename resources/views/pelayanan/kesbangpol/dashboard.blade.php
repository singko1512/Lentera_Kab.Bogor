@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
<!-- Welcome Section -->
<section class="bg-white rounded-2xl p-8 shadow-soft relative overflow-hidden group flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
<div class="relative z-10">
<h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-on-surface mb-3 tracking-tight">Selamat Datang, <span class="text-primary">Admin Kesbangpol</span></h1>
<p class="text-body-md font-body-md text-on-surface-variant max-w-2xl leading-relaxed">
    Kelola pendaftaran, verifikasi, dan manajemen akun instansi dinas di lingkungan Pemerintah Kabupaten Bogor.
</p>
</div>
<div class="relative z-10 shrink-0">
    <a href="{{ route('kesbangpol.dinas.index') }}" class="inline-flex items-center gap-2.5 px-6 py-3.5 bg-primary text-white font-label-md font-bold rounded-xl hover:bg-secondary transition-all shadow-md hover:shadow-lg">
        <span class="material-symbols-outlined text-[20px]">admin_panel_settings</span>
        <span>Kelola Akun Dinas</span>
        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
    </a>
</div>
<!-- Decorative element -->
<div class="absolute right-0 -top-10 h-[150%] w-1/3 opacity-[0.03] group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-700 pointer-events-none">
<svg class="w-full h-full" viewbox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
<path d="M47.7,-57.2C59.4,-44.6,64.8,-26.6,66.9,-8.5C69,9.5,67.8,27.7,58.7,42.5C49.6,57.3,32.6,68.7,13.6,71.8C-5.5,74.9,-26.6,69.7,-42.6,57.1C-58.5,44.5,-69.3,24.4,-70.7,4C-72,-16.3,-63.8,-36.3,-50.2,-49C-36.5,-61.6,-17.5,-67,0.1,-67.1C17.7,-67.2,35.9,-69.8,47.7,-57.2Z" fill="#115cb9" transform="translate(100 100)"></path>
</svg>
</div>
</section>

<!-- Statistics Grid (Bento Style) -->
<section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
<!-- Card 1 -->
<div class="bg-white rounded-2xl p-6 shadow-soft card-hover flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    <div class="flex justify-between items-start relative z-10">
        <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Total Pengajuan</p>
        <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
            <span class="material-symbols-outlined icon-filled text-[20px]">fiber_new</span>
        </div>
    </div>
    <div class="mt-auto relative z-10">
        <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $totalPermohonan }}</h3>
    </div>
</div>
<!-- Card 2 -->
<div class="bg-white rounded-2xl p-6 shadow-soft card-hover flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#F59E0B]/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    <div class="flex justify-between items-start relative z-10">
        <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Sedang Diproses</p>
        <div class="w-10 h-10 rounded-full bg-[#F59E0B]/10 flex items-center justify-center text-[#F59E0B] group-hover:bg-[#F59E0B] group-hover:text-white transition-colors">
            <span class="material-symbols-outlined icon-filled text-[20px]">sync</span>
        </div>
    </div>
    <div class="mt-auto relative z-10">
        <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $sedangDiproses }}</h3>
    </div>
</div>
<!-- Card 3 -->
<div class="bg-white rounded-2xl p-6 shadow-soft card-hover flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#10B981]/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    <div class="flex justify-between items-start relative z-10">
        <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Peserta Disetujui</p>
        <div class="w-10 h-10 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981] group-hover:bg-[#10B981] group-hover:text-white transition-colors">
            <span class="material-symbols-outlined icon-filled text-[20px]">verified</span>
        </div>
    </div>
    <div class="mt-auto relative z-10">
        <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $selesai }}</h3>
    </div>
</div>
<!-- Card 4 -->
<div class="bg-white rounded-2xl p-6 shadow-soft card-hover flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden group">
    <div class="absolute -right-4 -top-4 w-24 h-24 bg-error/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
    <div class="flex justify-between items-start relative z-10">
        <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Ditolak</p>
        <div class="w-10 h-10 rounded-full bg-error/10 flex items-center justify-center text-error group-hover:bg-error group-hover:text-white transition-colors">
            <span class="material-symbols-outlined icon-filled text-[20px]">cancel</span>
        </div>
    </div>
    <div class="mt-auto relative z-10">
        <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $ditolak }}</h3>
    </div>
</div>
</section>

<!-- Data Table Section -->
<section class="bg-white rounded-2xl shadow-soft overflow-hidden flex flex-col border border-outline-variant/30">
<div class="p-6 border-b border-outline-variant/40 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
<div>
    <h3 class="text-title-lg font-title-lg text-on-surface font-bold">Pengajuan Terbaru</h3>
    <p class="text-caption text-on-surface-variant mt-1">Daftar permohonan magang dan penelitian yang baru masuk.</p>
</div>
<a href="{{ route('kesbangpol.participants.extend') }}" class="bg-primary text-white px-5 py-2.5 rounded-full text-label-md font-label-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
    Lihat Semua
    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</a>
</div>
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-surface-container-low border-b border-outline-variant/40">
<th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Peserta</th>
<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Asal Instansi</th>
<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Jenis Layanan</th>
<th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Tgl Pengajuan</th>
<th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
</tr>
</thead>
<tbody class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant/30">
@forelse($pengajuanTerbarus as $layanan)
<tr class="hover:bg-primary/5 transition-colors group cursor-pointer" onclick="window.location='{{ route('kesbangpol.layanan.show', $layanan->id) }}'">
<td class="p-4 pl-6 font-semibold group-hover:text-primary transition-colors">{{ $layanan->atas_nama }}</td>
<td class="p-4 text-on-surface-variant">{{ $layanan->asal_instansi ?? '-' }}</td>
<td class="p-4 text-on-surface-variant text-sm">{{ $layanan->jenisLayanan->nama ?? '-' }}</td>
<td class="p-4 text-on-surface-variant text-sm">{{ $layanan->created_at->format('d M Y') }}</td>
<td class="p-4 pr-6">
<span class="inline-flex items-center justify-start min-w-[145px] gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold" style="background-color: {{ $layanan->statusMaster->warna ?? '#ccc' }}20; color: {{ $layanan->statusMaster->warna ?? '#000' }}; border: 1px solid {{ $layanan->statusMaster->warna ?? '#ccc' }}">
{{ $layanan->statusMaster->nama ?? 'Unknown' }}
</span>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="p-4 text-center text-on-surface-variant">Belum ada pengajuan baru.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
</section>
</div>

@endsection
