@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
<!-- Header Section -->
<div class="mb-8">
<h1 class="text-headline-lg font-headline-lg text-on-background mb-2">Riwayat Pengajuan</h1>
<p class="text-body-md font-body-md text-on-surface-variant">Riwayat seluruh pengajuan magang yang telah selesai diproses.</p>
</div>
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-stack-lg">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary-container flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Total Riwayat</p>
        <span class="material-symbols-outlined text-primary-container opacity-80" style="font-variation-settings: 'FILL' 1;">receipt_long</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalRiwayat ?? 0, 0, ',', '.') }}</h3>
    </div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-[#10B981] flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Diterima</p>
        <span class="material-symbols-outlined text-[#10B981] opacity-80" style="font-variation-settings: 'FILL' 1;">check_circle</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalDiterima ?? 0, 0, ',', '.') }}</h3>
    </div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-error flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Ditolak</p>
        <span class="material-symbols-outlined text-error opacity-80" style="font-variation-settings: 'FILL' 1;">cancel</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalDitolak ?? 0, 0, ',', '.') }}</h3>
    </div>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-outline flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Berlaku Habis</p>
        <span class="material-symbols-outlined text-outline opacity-80" style="font-variation-settings: 'FILL' 1;">timer_off</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ number_format($totalBerlakuHabis ?? 0, 0, ',', '.') }}</h3>
    </div>
</div>
</div>
<!-- Main Data Canvas -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] overflow-hidden">
<!-- Filters Bar -->
<form action="{{ route('kesbangpol.history.index') }}" method="GET" class="p-6 border-b border-outline-variant/30 bg-surface-bright flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[250px] flex flex-col gap-stack-sm">
        <label class="text-label-md font-label-md text-on-surface-variant">Pencarian</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
            <input name="search" value="{{ request('search') }}" class="w-full bg-surface border border-outline-variant rounded-lg pl-10 pr-4 py-2.5 text-body-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Cari nama peserta atau instansi..." type="text"/>
        </div>
    </div>
    <div class="flex flex-col gap-stack-sm w-[160px]">
        <label class="text-label-md font-label-md text-on-surface-variant">Status</label>
        <select name="status" onchange="this.form.submit()" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2.5 text-body-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent appearance-none">
            <option value="Semua Status" {{ request('status') == 'Semua Status' ? 'selected' : '' }}>Semua Status</option>
            <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
    </div>
    <div class="flex flex-col gap-stack-sm w-[200px]">
        <label class="text-label-md font-label-md text-on-surface-variant">Tanggal Selesai</label>
        <input name="date" value="{{ request('date') }}" onchange="this.form.submit()" class="w-full bg-surface border border-outline-variant rounded-lg px-3 py-2.5 text-body-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-on-surface-variant" type="date"/>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-primary text-white font-bold rounded-lg text-sm">Cari</button>
</form>
<!-- Table -->
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-[#F1F5F9] border-b border-outline-variant/30">
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Asal Instansi</th>
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Jenis Layanan</th>
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Tgl. Selesai</th>
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider text-center">Status</th>
<th class="px-6 py-4 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-[#E2E8F0]">
                                @forelse($historyPengajuan as $app)
                                <tr class="hover:bg-surface-bright/50 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary font-bold shrink-0">
                                                {{ strtoupper(substr($app->atas_nama, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-body-md font-body-md font-medium text-on-surface">{{ $app->atas_nama }}</p>
                                                <p class="text-caption font-caption text-on-surface-variant">Telp: {{ $app->no_hp ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ $app->asal_instansi }}</td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant max-w-[200px] truncate" title="{{ $app->jenisLayanan->nama ?? '-' }}">
                                        {{ $app->jenisLayanan->nama ?? '-' }}
                                    </td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($app->updated_at)->format('d M Y') }}</td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md text-caption font-label-md" style="background-color: {{ $app->statusMaster->warna ?? '#ccc' }}20; color: {{ $app->statusMaster->warna ?? '#000' }}; border: 1px solid {{ $app->statusMaster->warna ?? '#ccc' }}">
                                            {{ $app->statusMaster->nama ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('kesbangpol.layanan.show', $app->id) }}" class="h-8 w-8 rounded-lg bg-surface-container flex items-center justify-center text-on-surface-variant hover:bg-surface-container-high hover:text-primary transition-colors cursor-pointer" title="Lihat Detail">
                                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-on-surface-variant">Belum ada riwayat pengajuan.</td>
                                </tr>
                                @endforelse
                            </tbody>
</table>
</div>
<!-- Footer / Pagination -->
<div class="p-4 border-t border-outline-variant/30 bg-surface-bright flex items-center justify-between">
    <div class="w-full">
        {{ $historyPengajuan->links() }}
    </div>
</div>
</div>
</div>

@endsection
