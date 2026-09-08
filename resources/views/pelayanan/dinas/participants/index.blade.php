@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg">
@php
    $dinasName = strtolower(Auth::user()->dinas->nama ?? 'Instansi');
    $ignoreWords = ['dan', 'atau', 'di', 'ke', 'dari'];
    $words = explode(' ', $dinasName);
    foreach ($words as $key => $word) {
        if ($key == 0 || !in_array($word, $ignoreWords)) {
            $words[$key] = ucfirst($word);
        }
    }
    $formattedDinasName = implode(' ', $words);
@endphp
        <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Peserta Magang {{ $formattedDinasName }}</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Kelola peserta magang yang telah diterima atau sedang aktif.</p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Instansi Asal</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Penempatan Bidang</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Periode Magang</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                        <th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($participants as $peserta)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 pl-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold">
                                    {{ substr($peserta->user->name ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-body-md font-body-md text-on-surface font-semibold">{{ $peserta->user->name ?? '-' }}</p>
                                    <p class="text-[12px] text-on-surface-variant">{{ $peserta->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-body-md font-body-md text-on-surface">{{ $peserta->user->asal_instansi ?? '-' }}</span>
                        </td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-[12px] font-medium bg-surface-container border border-outline-variant/50 text-on-surface-variant">
                                {{ $peserta->rekrutmen->bidang->name ?? 'Tidak Spesifik' }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="text-body-md font-body-md text-on-surface">
                                {{ $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') : '-' }} <br>
                                <span class="text-[12px] text-on-surface-variant">s/d</span> <br>
                                {{ $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M Y') : '-' }}
                            </div>
                        </td>
                        <td class="p-4">
                            @if($peserta->status == 'aktif')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">Aktif</span>
                            @elseif($peserta->status == 'diterima')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">Diterima</span>
                            @elseif($peserta->status == 'menunggu')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#F59E0B]/10 text-[#F59E0B] border border-[#F59E0B]/20">Menunggu</span>
                            @elseif($peserta->status == 'ditolak')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#EF4444]/10 text-[#EF4444] border border-[#EF4444]/20">Ditolak</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-outline-variant/20 text-on-surface-variant border border-outline-variant/30">{{ ucfirst($peserta->status) }}</span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex justify-end gap-2 items-center">
                                <a href="{{ route('dinas.participants.show', $peserta->id) }}" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors cursor-pointer inline-flex items-center" title="Detail Peserta">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[48px] text-outline-variant">group_off</span>
                                <p>Belum ada peserta yang diterima atau aktif di Dinas ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($participants->hasPages())
        <div class="p-4 border-t border-outline-variant/40 bg-surface-container-lowest">
            {{ $participants->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
