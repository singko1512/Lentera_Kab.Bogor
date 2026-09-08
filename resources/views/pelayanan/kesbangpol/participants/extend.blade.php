@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
<!-- Page Header -->
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
<div>
<h2 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface mb-2">Pengajuan Surat Izin Rekomendasi</h2>
<p class="text-body-md font-body-md text-on-surface-variant">Kelola data pengajuan surat izin rekomendasi.</p>
</div>
</div>
<!-- Stats/Summary Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
<!-- Stat Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary-container flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Total Menunggu</p>
        <span class="material-symbols-outlined text-primary-container opacity-80" style="font-variation-settings: 'FILL' 1;">pending_actions</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $menungguPersetujuan }}</h3>
    </div>
</div>
<!-- Stat Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-secondary-container flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Disetujui</p>
        <span class="material-symbols-outlined text-secondary-container opacity-80" style="font-variation-settings: 'FILL' 1;">event_available</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $disetujui }}</h3>
    </div>
</div>
<!-- Stat Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-error flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Perlu Perbaikan</p>
        <span class="material-symbols-outlined text-error opacity-80" style="font-variation-settings: 'FILL' 1;">warning</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $ditolak }}</h3>
    </div>
</div>
<!-- Stat Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-surface-tint flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Selesai Periode</p>
        <span class="material-symbols-outlined text-surface-tint opacity-80" style="font-variation-settings: 'FILL' 1;">timer_off</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $selesaiPeriode }}</h3>
    </div>
</div>
</div>
<!-- Data Table Card -->
<div class="bg-surface-container-lowest rounded-xl shadow-level-1 overflow-hidden">
<!-- Table Header Actions -->
<div class="p-6 border-b border-outline-variant/30 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-white">
<h3 class="text-title-lg font-title-lg text-on-surface font-bold">Daftar Pengajuan Surat Izin Rekomendasi</h3>
<div class="flex items-center gap-3 w-full sm:w-auto">
<div class="relative flex-1 sm:flex-none">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[18px]">search</span>
<input class="pl-10 pr-4 py-2.5 text-sm bg-white rounded-lg border border-outline-variant/50 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary w-full sm:w-64 transition-all" placeholder="Cari nama atau dinas..." type="text"/>
</div>
<button class="px-4 py-2.5 bg-white border border-outline-variant/50 text-on-surface-variant rounded-lg font-label-md text-label-md hover:bg-surface-container-low hover:text-primary transition-colors shadow-sm flex items-center gap-2 shrink-0">
<span class="material-symbols-outlined text-[18px]">filter_list</span> Filter
</button>
</div>
</div>
<!-- Table Content -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-[#F1F5F9] border-b border-outline-variant/30 text-label-md font-label-md text-on-surface-variant uppercase">
<tr>
<th class="px-6 py-4 font-semibold">Nama Lengkap</th>
<th class="px-6 py-4 font-semibold">Instansi Asal</th>
<th class="px-6 py-4 font-semibold">Jenis Layanan</th>
<th class="px-6 py-4 font-semibold">Tgl. Pelaksanaan</th>
<th class="px-6 py-4 font-semibold">Status</th>
<th class="px-6 py-4 font-semibold text-right">Aksi</th>
</tr>
</thead>
<tbody class="text-body-md font-body-md text-on-surface">
@forelse($pengajuanPerpanjangan as $pengajuan)
<tr class="border-b border-outline-variant/30 hover:bg-surface-container/30 transition-colors">
<td class="px-6 py-4">
<div class="flex items-center gap-3">
<div class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary-container font-bold text-sm shrink-0">
                                            {{ strtoupper(substr($pengajuan->atas_nama, 0, 2)) }}
                                        </div>
<div>
<p class="font-medium text-on-surface">{{ $pengajuan->atas_nama }}</p>
<p class="text-caption font-caption text-on-surface-variant">Telp: {{ $pengajuan->no_hp ?? '-' }}</p>
</div>
</div>
</td>
<td class="px-6 py-4">{{ $pengajuan->asal_instansi }}</td>
<td class="px-6 py-4">{{ $pengajuan->jenisLayanan->nama ?? '-' }}</td>
<td class="px-6 py-4 text-on-surface-variant">
    @if($pengajuan->tanggal_mulai && $pengajuan->tanggal_selesai)
        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
    @else
        -
    @endif
</td>
<td class="px-6 py-4">
<span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-caption font-label-md uppercase" style="background-color: {{ $pengajuan->statusMaster->warna ?? '#ccc' }}20; color: {{ $pengajuan->statusMaster->warna ?? '#000' }}; border: 1px solid {{ $pengajuan->statusMaster->warna ?? '#ccc' }}">
                                        {{ $pengajuan->statusMaster->nama ?? 'Unknown' }}
                                    </span>
</td>
<td class="px-6 py-4 text-right">
<a href="{{ route('kesbangpol.layanan.show', $pengajuan->id) }}" class="inline-block text-primary-container hover:text-secondary p-1 rounded transition-colors" title="Lihat Detail">
<span class="material-symbols-outlined text-xl">visibility</span>
</a>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-6 py-4 text-center text-on-surface-variant">Belum ada pengajuan.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination -->
<div class="p-4 border-t border-outline-variant/30 flex items-center justify-between bg-surface-bright">
    <div class="w-full">
        {{ $pengajuanPerpanjangan->links() }}
    </div>
</div>
</div>
</div>

@endsection
