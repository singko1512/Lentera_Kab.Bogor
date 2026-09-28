@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg">
@php
    $rawDinasName = $dinas->name ?? $dinas->nama ?? Auth::user()->dinas->name ?? Auth::user()->dinas->nama ?? 'Instansi';
    $dinasSingkat = \App\Models\Dinas::formatSingkatan($rawDinasName);
@endphp
        <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Manajemen Peserta {{ $dinasSingkat }}</h2>
        <p class="text-body-md font-body-md text-on-surface-variant">Kelola peserta magang dan status akun peserta yang telah diterima atau sedang aktif di {{ $dinasSingkat }}.</p>
    </div>

    <!-- Data Table Card -->
    <div class="bg-surface-container-lowest rounded-xl shadow-[0px_4px_20px_rgba(0,0,0,0.05)] border border-outline-variant/20 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#F1F5F9] border-b border-outline-variant/30">
                    <tr>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Nama Peserta</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Instansi Asal</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Penempatan Bidang</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Periode Magang</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold whitespace-nowrap">Status</th>
                        <th class="py-4 px-6 text-label-md font-label-md text-on-surface-variant uppercase tracking-wider font-semibold text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#E2E8F0]">
                    @forelse($participants as $peserta)
                    <tr class="hover:bg-surface-bright/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-blue-100 text-primary border border-blue-200 flex items-center justify-center font-bold shrink-0 shadow-xs">
                                    {{ strtoupper(substr($peserta->user->name ?? '?', 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-body-md font-body-md font-medium text-on-surface">{{ $peserta->user->name ?? '-' }}</p>
                                    <p class="text-caption font-caption text-on-surface-variant">{{ $peserta->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant max-w-[200px] truncate" title="{{ $peserta->user->asal_instansi ?? ($peserta->permohonanLayanan->asal_instansi ?? '-') }}">
                            {{ $peserta->user->asal_instansi ?? ($peserta->permohonanLayanan->asal_instansi ?? '-') }}
                        </td>
                        <td class="py-4 px-6">
                            @php
                                $rawBidang = $peserta->bidang->name ?? $peserta->rekrutmen?->bidang?->name ?? null;
                                $formattedBidang = $rawBidang ? ucwords(strtolower($rawBidang)) : 'Belum Ditentukan';
                            @endphp
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-surface border border-outline-variant/60 shadow-xs max-w-[260px]" title="{{ $rawBidang ?? 'Belum Ditentukan' }}">
                                <span class="material-symbols-outlined text-[18px] text-primary shrink-0">apartment</span>
                                <span class="text-caption font-medium text-on-surface truncate">{{ $formattedBidang }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-body-md font-body-md font-medium text-on-surface">
                                    {{ $peserta->tanggal_mulai ? \Carbon\Carbon::parse($peserta->tanggal_mulai)->translatedFormat('d M Y') : '-' }}
                                </span>
                                <span class="text-caption font-caption text-on-surface-variant flex items-center gap-1">
                                    <span class="text-[11px] text-outline">s/d</span>
                                    {{ $peserta->tanggal_selesai ? \Carbon\Carbon::parse($peserta->tanggal_selesai)->translatedFormat('d M Y') : '-' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex flex-col gap-1.5 items-start">
                                {{-- Status Magang --}}
                                @php
                                    $stMagang = strtolower($peserta->status ?? 'menunggu');
                                    if ($stMagang === 'aktif') {
                                        $badgeMagang = 'bg-[#D4EDDA] text-[#155724] border-[#C3E6CB]';
                                        $labelMagang = 'Magang Aktif';
                                    } elseif ($stMagang === 'diterima') {
                                        $badgeMagang = 'bg-primary/10 text-primary border-primary/20';
                                        $labelMagang = 'Diterima';
                                    } elseif ($stMagang === 'menunggu') {
                                        $badgeMagang = 'bg-[#FFF3CD] text-[#856404] border-[#FFEEBA]';
                                        $labelMagang = 'Menunggu';
                                    } elseif ($stMagang === 'ditolak') {
                                        $badgeMagang = 'bg-[#F8D7DA] text-[#721C24] border-[#F5C6CB]';
                                        $labelMagang = 'Ditolak';
                                    } else {
                                        $badgeMagang = 'bg-surface-variant text-on-surface-variant border-outline-variant/30';
                                        $labelMagang = ucfirst($stMagang);
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-caption font-medium border {{ $badgeMagang }}">
                                    {{ $labelMagang }}
                                </span>

                                {{-- Status Akun --}}
                                @php
                                    $statusAkun = strtolower($peserta->user->status_akun ?? 'aktif');
                                    if ($statusAkun == 'aktif') {
                                        $badgeAkun = 'bg-[#D4EDDA] text-[#155724]';
                                        $dotAkun = 'bg-[#28A745]';
                                    } elseif ($statusAkun == 'dibatasi') {
                                        $badgeAkun = 'bg-yellow-100 text-yellow-800';
                                        $dotAkun = 'bg-yellow-500';
                                    } else {
                                        $badgeAkun = 'bg-red-100 text-red-800';
                                        $dotAkun = 'bg-red-500';
                                    }
                                @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-caption font-medium {{ $badgeAkun }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotAkun }}"></span>
                                    Akun {{ ucfirst($statusAkun) }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center whitespace-nowrap">
                            <div class="inline-flex items-center justify-center gap-2">
                                <a href="{{ route('dinas.participants.show', $peserta->id) }}" aria-label="Lihat Detail" class="w-9 h-9 text-on-surface-variant hover:bg-surface-container hover:text-primary transition-colors rounded-lg flex items-center justify-center border border-outline-variant/30 shrink-0" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                <button type="button" data-id="{{ $peserta->user->id }}" data-name="{{ $peserta->user->name ?? '' }}" data-status="{{ strtolower($peserta->user->status_akun ?? 'aktif') }}" onclick="openKelolaAkunModal(this)" class="w-32 h-9 bg-primary text-white text-label-md font-label-md font-medium rounded-lg hover:bg-primary/90 transition-colors shadow-sm whitespace-nowrap flex items-center justify-center gap-1.5 shrink-0" title="Kelola Akun Peserta">
                                    <span class="material-symbols-outlined text-[18px]">manage_accounts</span>
                                    <span>Kelola Akun</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-on-surface-variant">
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

<!-- Modal Kelola Akun -->
<div id="modalKelolaAkun" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-sm items-center justify-center p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-surface-container-lowest rounded-2xl shadow-xl w-full max-w-md transform scale-95 transition-transform duration-300" id="modalKelolaAkunContent">
        <form id="formKelolaAkun" method="POST" action="">
            @csrf
            <div class="p-6 border-b border-outline-variant/30 flex items-center justify-between">
                <h3 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">manage_accounts</span>
                    Kelola Akun Peserta
                </h3>
                <button type="button" onclick="closeKelolaAkunModal()" class="text-on-surface-variant hover:bg-surface-variant p-2 rounded-full transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            <div class="p-6 space-y-4">
                <p class="text-body-md text-on-surface-variant">Atur status akun untuk peserta: <strong id="modalPesertaName" class="text-on-surface"></strong></p>
                
                <div class="flex flex-col gap-2">
                    <label class="text-label-md font-bold text-on-surface">Status Akun</label>
                    <select name="status_akun" id="modalStatusAkun" class="w-full rounded-lg border-outline-variant bg-surface-bright focus:border-secondary focus:ring focus:ring-secondary/20 font-body-md text-body-md p-3 text-on-surface" required>
                        <option value="aktif">Aktif (Normal)</option>
                        <option value="dibatasi">Dibatasi (Hanya bisa melihat data)</option>
                        <option value="diblokir">Diblokir (Indikasi Spam/Melanggar)</option>
                    </select>
                    <p class="text-caption text-on-surface-variant mt-1">
                        Pilih <b>Diblokir</b> untuk menonaktifkan total akses pengguna ini dan mencegah spam pengajuan.
                    </p>
                </div>
            </div>
            
            <div class="p-6 border-t border-outline-variant/30 bg-surface-container-low/50 flex justify-end gap-3 rounded-b-2xl">
                <button type="button" onclick="closeKelolaAkunModal()" class="px-5 py-2.5 rounded-lg border border-outline text-primary font-label-lg hover:bg-surface-variant transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2.5 rounded-lg bg-primary text-white font-label-lg hover:bg-primary/90 transition-colors shadow-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.openKelolaAkunModal = function(btn) {
        const id = btn.dataset.id;
        const name = btn.dataset.name;
        const currentStatus = btn.dataset.status;
        
        const modal = document.getElementById('modalKelolaAkun');
        const modalContent = document.getElementById('modalKelolaAkunContent');
        const form = document.getElementById('formKelolaAkun');
        
        // Update URL form
        form.action = `/dinas/participants/${id}/status`;
        
        document.getElementById('modalPesertaName').textContent = name;
        document.getElementById('modalStatusAkun').value = currentStatus;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }
    
    window.closeKelolaAkunModal = function() {
        const modal = document.getElementById('modalKelolaAkun');
        const modalContent = document.getElementById('modalKelolaAkunContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }
</script>
@endsection
