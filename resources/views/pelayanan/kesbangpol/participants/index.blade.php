@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
<!-- Page Header -->
<div class="mb-8">
<h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-surface mb-2">Manajemen Peserta</h1>
<p class="text-body-md font-body-md text-on-surface-variant max-w-3xl">Kelola akun peserta selama berada dalam proses administrasi Kesbangpol.</p>
</div>
<!-- Summary Cards Row -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
<!-- Card 1 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-secondary flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Peserta Aktif</p>
        <span class="material-symbols-outlined text-secondary opacity-80" style="font-variation-settings: 'FILL' 1;">group</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $aktif }}</h3>
    </div>
</div>
<!-- Card 2 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-primary-container flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Perlu Tindakan</p>
        <span class="material-symbols-outlined text-primary-container opacity-80" style="font-variation-settings: 'FILL' 1;">assignment_late</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $perluTindakan }}</h3>
    </div>
</div>
<!-- Card 3 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-[#F59E0B] flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Dibatasi</p>
        <span class="material-symbols-outlined text-[#F59E0B] opacity-80" style="font-variation-settings: 'FILL' 1;">warning</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $dibatasi }}</h3>
    </div>
</div>
<!-- Card 4 -->
<div class="bg-surface-container-lowest rounded-xl p-6 shadow-level-1 card-hover border-t-4 border-error flex flex-col justify-between h-[130px]">
    <div class="flex justify-between items-start">
        <p class="text-label-md font-label-md text-on-surface-variant uppercase tracking-wider">Diblokir</p>
        <span class="material-symbols-outlined text-error opacity-80" style="font-variation-settings: 'FILL' 1;">block</span>
    </div>
    <div class="mt-auto">
        <h3 class="text-headline-lg font-headline-lg text-on-surface leading-none">{{ $diblokir }}</h3>
    </div>
</div>
</div>
<!-- Toolbar (Search & Filters) -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6 mb-6 flex flex-col lg:flex-row gap-4 items-center justify-between border border-outline-variant/20">
<!-- Search -->
<div class="w-full lg:w-1/3 relative">
<label class="sr-only" for="search-peserta">Cari nama peserta</label>
<span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70">search</span>
<input class="w-full pl-10 pr-4 py-2.5 bg-surface rounded-lg border border-outline-variant/50 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary transition-shadow text-body-md font-body-md placeholder:text-on-surface-variant/50 text-on-surface" id="search-peserta" placeholder="Cari nama peserta..." type="text"/>
</div>
<!-- Filters -->
<div class="w-full lg:w-auto flex flex-col sm:flex-row gap-3">
<div class="relative min-w-[180px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Status Akun</option>
<option value="aktif">Aktif</option>
<option value="dibatasi">Dibatasi</option>
<option value="diblokir">Diblokir</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
<div class="relative min-w-[200px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Status Pengajuan</option>
<option value="menunggu">Menunggu Verifikasi</option>
<option value="diterima">Diterima</option>
<option value="ditolak">Ditolak</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
<div class="relative min-w-[180px]">
<select class="w-full appearance-none bg-surface border border-outline-variant/50 text-on-surface text-label-md font-label-md rounded-lg pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-secondary/50 focus:border-secondary cursor-pointer">
<option value="">Semua Dinas Tujuan</option>
<option value="kominfo">Dinas Kominfo</option>
<option value="kesehatan">Dinas Kesehatan</option>
<option value="pendidikan">Dinas Pendidikan</option>
</select>
<span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant/70 pointer-events-none">arrow_drop_down</span>
</div>
</div>
</div>
<!-- Data Table Card -->
<div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
<tr>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Asal Instansi</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Dinas Tujuan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status Pengajuan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status Akun</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tahap Pengelolaan</th>
<th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
</tr>
</thead>
<tbody class="divide-y divide-[#E2E8F0]">
                                @forelse($participants as $p)
                                <tr class="hover:bg-surface-bright/50 transition-colors group">
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-full bg-secondary-container/20 flex items-center justify-center text-secondary font-bold shrink-0">
                                                {{ strtoupper(substr($p->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="text-body-md font-body-md font-medium text-on-surface">{{ $p->name }}</p>
                                                <p class="text-caption font-caption text-on-surface-variant">{{ $p->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ $p->asal_instansi ?? '-' }}</td>
                                    <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant max-w-[200px] truncate" title="{{ $p->magangApplications->first()->rekrutmen->dinas->nama ?? '-' }}">{{ $p->magangApplications->first()->rekrutmen->dinas->nama ?? '-' }}</td>
                                    <td class="py-4 px-6">
                                        @php $status = $p->magangApplications->first()->status ?? 'Belum Mengajukan'; @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-caption font-medium {{ $status == 'menunggu' ? 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]' : 'bg-surface-variant text-on-surface-variant border-outline-variant/30' }} border">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-caption font-caption font-medium bg-[#D4EDDA] text-[#155724]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#28A745]"></span> Aktif
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-2 text-primary-container">
                                            <span class="material-symbols-outlined text-[18px]">account_balance</span>
                                            <span class="text-body-md font-body-md font-medium">Kesbangpol</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex justify-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('kesbangpol.participants.detail', $p->id) }}" aria-label="Lihat Detail" class="p-2 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors rounded-lg" title="Lihat Detail">
                                                <span class="material-symbols-outlined">visibility</span>
                                            </a>
                                            <button class="px-4 py-2 bg-primary-container text-white text-label-md font-label-md font-medium rounded-lg hover:bg-primary-container/90 transition-colors shadow-sm whitespace-nowrap">
                                                Kelola Akun
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-on-surface-variant">Belum ada peserta yang diterima.</td>
                                </tr>
                                @endforelse
                            </tbody>
</table>
</div>
<!-- Pagination -->
<div class="bg-surface-container-lowest px-6 py-4 border-t border-outline-variant/30 flex items-center justify-between">
    <div class="w-full">
        {{ $participants->links() }}
    </div>
</div>
</div>
</div>

@endsection
