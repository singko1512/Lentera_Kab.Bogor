@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-6 pb-12">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Daftar Permohonan Layanan (Bakesbangpol)</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Verifikasi dan kelola pengajuan rekomendasi layanan permohonan magang / izin penelitian.</p>
    </div>
    
    <!-- Table Card -->
    <div class="bg-surface-container-lowest rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
                    <tr>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">ID</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Tanggal</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Pemohon</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Jenis Layanan</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($layanans as $layanan)
                    <tr class="hover:bg-surface-bright/50 transition-colors group">
                        <td class="py-4 px-6 text-body-md font-medium text-primary whitespace-nowrap">#{{ $layanan->id }}</td>
                        <td class="py-4 px-6 text-body-md text-on-surface-variant whitespace-nowrap">{{ $layanan->created_at->format('d/m/Y H:i') }}</td>
                        <td class="py-4 px-6">
                            <p class="text-body-md font-semibold text-on-surface">{{ $layanan->atas_nama }}</p>
                            <p class="text-caption text-on-surface-variant">{{ $layanan->asal_instansi ?? '-' }}</p>
                        </td>
                        <td class="py-4 px-6 text-body-md text-on-surface-variant capitalize">{{ $layanan->jenisLayanan->nama ?? '-' }}</td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            @php
                                $statusKode = strtolower($layanan->statusMaster->kode ?? '');
                                if (in_array($statusKode, ['menunggu_verifikasi', 'menunggu', 'proses'])) {
                                    $badgeClass = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                    $dotClass = 'bg-[#F59E0B]';
                                } elseif (in_array($statusKode, ['disetujui', 'selesai', 'diterima'])) {
                                    $badgeClass = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                    $dotClass = 'bg-[#28A745]';
                                } elseif ($statusKode === 'ditolak') {
                                    $badgeClass = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                    $dotClass = 'bg-[#DC3545]';
                                } elseif ($statusKode === 'perlu_revisi') {
                                    $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                    $dotClass = 'bg-orange-500';
                                } else {
                                    $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                                    $dotClass = 'bg-slate-400';
                                }
                            @endphp
                            <div class="flex flex-col items-start gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-caption font-semibold rounded-full border {{ $badgeClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                    {{ $layanan->statusMaster->nama ?? 'Unknown' }}
                                </span>
                                @if($layanan->isRevisiSelesai())
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 text-[11px] font-medium rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-xs" title="Pemohon telah mengirimkan dokumen revisi baru">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                        </span>
                                        Revisi Baru Masuk
                                    </span>
                                @elseif($layanan->isMenungguRevisiUser())
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-medium rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                        <span class="material-symbols-outlined text-[13px] text-amber-600">schedule</span>
                                        Menunggu Pemohon
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('kesbangpol.layanan.show', $layanan->id) }}" class="px-3.5 py-2 bg-primary text-white text-label-md font-medium rounded-lg hover:bg-primary/90 transition-colors shadow-sm inline-flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[18px]">search</span>
                                    <span>Periksa</span>
                                </a>
                                <a href="{{ route('kesbangpol.layanan.generate_docx', $layanan->id) }}" target="_blank" title="Generate & Download Surat Rekomendasi (.docx)" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors border border-outline-variant/40 inline-flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">description</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[48px] text-outline-variant">inbox</span>
                                <p>Belum ada permohonan layanan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($layanans->hasPages())
        <div class="p-4 border-t border-outline-variant/30 bg-surface-container-lowest">
            {{ $layanans->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
