@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg flex justify-between items-end">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Pengajuan Magang / PKL Masuk</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">Daftar permohonan magang yang masuk ke {{ $dinas->name ?? ($dinas->nama ?? 'Dinas') }}.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Instansi Asal</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status & Penempatan</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Periode Magang</th>
                        <th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($applications as $app)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    {{ strtoupper(substr($app->user->nama ?? ($app->user->name ?? '?'), 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-body-md font-body-md text-on-surface font-semibold">{{ $app->user->nama ?? ($app->user->name ?? '-') }}</p>
                                    <p class="text-[12px] text-on-surface-variant">{{ $app->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-body-md font-body-md text-on-surface">{{ $app->user->asal_instansi ?? ($app->instansi_asal ?? '-') }}</span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-1">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold w-max {{ $app->status == 'diterima' ? 'bg-green-100 text-green-800 border border-green-300' : ($app->status == 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($app->status) }}
                                </span>
                                <span class="text-xs text-on-surface-variant font-medium">
                                    {{ $app->bidang->name ?? 'Belum Ditentukan' }}
                                </span>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-body-md font-body-md text-on-surface">{{ $app->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="p-4">
                            <div class="text-body-md font-body-md text-on-surface">
                                {{ $app->tanggal_mulai ? \Carbon\Carbon::parse($app->tanggal_mulai)->format('d M Y') : '-' }} <br>
                                <span class="text-[12px] text-on-surface-variant">s/d</span> <br>
                                {{ $app->tanggal_selesai ? \Carbon\Carbon::parse($app->tanggal_selesai)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('dinas.applications.show', $app->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-primary text-white hover:bg-primary-dark rounded-lg transition-colors text-xs font-semibold" title="Detail & Verifikasi">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span> Detail & Verifikasi
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-8 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[48px] text-outline-variant">inbox</span>
                                <p>Tidak ada pengajuan magang yang masuk saat ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
        <div class="p-4 border-t border-outline-variant/40 bg-surface-container-lowest">
            {{ $applications->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
