@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<!-- Page Header -->
<div class="mb-stack-lg">
@php
    $dinasName = strtolower(Auth::user()->dinas->nama ?? 'Kesbangpol');
    $ignoreWords = ['dan', 'atau', 'di', 'ke', 'dari'];
    $words = explode(' ', $dinasName);
    foreach ($words as $key => $word) {
        if ($key == 0 || !in_array($word, $ignoreWords)) {
            $words[$key] = ucfirst($word);
        }
    }
    $formattedDinasName = implode(' ', $words);
@endphp
<h2 class="text-headline-lg font-headline-lg text-on-surface mb-2">Peserta Terdaftar {{ $formattedDinasName }}</h2>
<p class="text-body-lg font-body-lg text-on-surface-variant">Daftar peserta yang telah terdaftar di instansi tujuan.</p>
</div>
<!-- Data Table Card (Level 1 Surface) -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/30 overflow-hidden flex flex-col">
<!-- Table Toolbar -->
<div class="p-6 border-b border-outline-variant/30 flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface/50">
<!-- Search Input -->
<div class="relative w-full sm:w-80">
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
<input class="w-full pl-10 pr-4 py-2.5 bg-surface-container-lowest border border-outline-variant rounded-lg text-body-md font-body-md text-on-surface focus:outline-none focus:border-primary-container focus:ring-1 focus:ring-primary-container transition-shadow placeholder:text-outline/70" placeholder="Cari nama peserta atau instansi..." type="text"/>
</div>
<!-- Actions -->
<div class="flex gap-3 w-full sm:w-auto">
<button class="flex items-center justify-center gap-2 px-4 py-2.5 border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface-variant hover:bg-surface-container-low transition-colors w-full sm:w-auto">
<span class="material-symbols-outlined text-[18px]">filter_list</span>
                            Filter
                        </button>
<button class="flex items-center justify-center gap-2 px-4 py-2.5 bg-primary-container text-white rounded-lg text-label-md font-label-md hover:bg-primary-container/90 transition-colors shadow-sm w-full sm:w-auto">
<span class="material-symbols-outlined text-[18px]">download</span>
                            Ekspor CSV
                        </button>
</div>
</div>
<!-- Table Container -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<!-- Table Header -->
<thead class="bg-[#F1F5F9] text-on-surface-variant border-b border-outline-variant/30">
<tr>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Asal Instansi</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Dinas Tujuan</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Bidang</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Tanggal Mulai</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Tanggal Berakhir</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold whitespace-nowrap">Status</th>
<th class="py-4 px-6 text-label-md font-label-md uppercase tracking-wider font-semibold text-right whitespace-nowrap">AKSI</th>
</tr>
</thead>
<!-- Table Body -->
<tbody class="text-body-md font-body-md text-on-surface divide-y divide-[#E2E8F0]">
@forelse($pesertaMenunggu as $peserta)
<tr class="table-row-hover">
<td class="py-4 px-6 font-medium">{{ $peserta->user->nama ?? ($peserta->user->name ?? 'Unknown') }}</td>
<td class="py-4 px-6 text-on-surface-variant">{{ $peserta->user->asal_instansi ?? '-' }}</td>
<td class="py-4 px-6">{{ $peserta->dinas->name ?? (optional(optional($peserta->rekrutmen)->dinas)->name ?? '-') }}</td>
<td class="py-4 px-6 text-on-surface-variant">{{ $peserta->bidang->name ?? (optional(optional($peserta->rekrutmen)->bidang)->name ?? '-') }}</td>
<td class="py-4 px-6 text-on-surface-variant">{{ $peserta->permohonanLayanan && $peserta->permohonanLayanan->tanggal_mulai ? \Carbon\Carbon::parse($peserta->permohonanLayanan->tanggal_mulai)->format('d M Y') : '-' }}</td>
<td class="py-4 px-6 text-on-surface-variant">{{ $peserta->permohonanLayanan && $peserta->permohonanLayanan->tanggal_selesai ? \Carbon\Carbon::parse($peserta->permohonanLayanan->tanggal_selesai)->format('d M Y') : '-' }}</td>
<td class="py-4 px-6">
    @if($peserta->status == 'diterima')
    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-label-md bg-status-success-bg text-status-success-text border border-status-success-text/20">
        <span class="w-1.5 h-1.5 rounded-full bg-status-success-text mr-1.5"></span>
        DITERIMA
    </span>
    @elseif($peserta->status == 'menunggu')
    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-label-md bg-[#FFF3CD] text-[#856404] border border-[#FFEEBA]">
        <span class="w-1.5 h-1.5 rounded-full bg-[#FFC107] mr-1.5"></span>
        MENUNGGU
    </span>
    @elseif($peserta->status == 'ditolak')
    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-label-md bg-[#F8D7DA] text-[#721C24] border border-[#F5C6CB]">
        <span class="w-1.5 h-1.5 rounded-full bg-[#DC3545] mr-1.5"></span>
        DITOLAK
    </span>
    @else
    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-label-md bg-gray-100 text-gray-800 border border-gray-200">
        {{ strtoupper($peserta->status) }}
    </span>
    @endif
</td>
<td class="py-4 px-6 text-right"><a href="{{ route('kesbangpol.placement.show', $peserta->id) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-secondary-container/20 text-secondary hover:bg-secondary-container/30 rounded-lg transition-colors text-label-md font-label-md"><span class="material-symbols-outlined text-[18px]">visibility</span> Lihat Detail </a></td>
</tr>
@empty
<tr>
<td colspan="7" class="py-8 text-center text-on-surface-variant">Belum ada data penempatan peserta.</td>
</tr>
@endforelse
</tbody>
</table>
</div>
<!-- Pagination Footer -->
<div class="px-6 py-4 border-t border-outline-variant/30 flex items-center justify-between bg-surface-container-lowest">
    <div class="w-full">
        {{ $pesertaMenunggu->links() }}
    </div>
</div>
</div>

@endsection

