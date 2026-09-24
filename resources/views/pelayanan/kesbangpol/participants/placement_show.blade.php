@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto">
    <!-- Breadcrumb & Back Button -->
    <div class="mb-stack-md flex flex-col gap-stack-sm">
        <nav aria-label="Breadcrumb" class="flex items-center text-label-md font-label-md text-on-surface-variant">
            <a class="hover:text-primary transition-colors" href="{{ route('kesbangpol.dashboard') }}">Dashboard</a>
            <span class="mx-2 text-outline-variant">/</span>
            <a class="hover:text-primary transition-colors" href="{{ route('kesbangpol.participants.placement') }}">Penempatan Peserta</a>
            <span class="mx-2 text-outline-variant">/</span>
            <span class="text-on-surface font-semibold">Detail Peserta</span>
        </nav>
        <div class="flex items-center gap-4 mt-2">
            <button onclick="history.back()" aria-label="Kembali" class="flex items-center justify-center w-10 h-10 rounded-lg border border-outline-variant bg-surface hover:bg-surface-container transition-colors text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">arrow_back</span>
            </button>
            <h2 class="text-headline-md font-headline-md text-on-surface md:text-headline-lg font-headline-lg hidden md:block">Detail Penempatan Peserta</h2>
        </div>
    </div>

    <!-- Summary Header Card -->
    <div class="glass-card rounded-xl p-6 mb-stack-lg border-t-4 border-t-secondary relative overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div class="flex items-start gap-6">
                <div class="w-20 h-20 rounded-full bg-surface-container-high border-2 border-primary-container flex items-center justify-center flex-shrink-0 shadow-sm overflow-hidden">
                    <span class="material-symbols-outlined text-4xl text-primary-container">person</span>
                </div>
                <div>
                    <h3 class="text-headline-md font-headline-md text-primary mb-1">{{ $application->user->name ?? '-' }}</h3>
                    <p class="text-body-md font-body-md text-on-surface-variant flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">school</span>
                        {{ $application->user->asal_instansi ?? '-' }}
                    </p>
                </div>
            </div>
            <div class="flex flex-col md:items-end gap-3">
                @if($application->status == 'diterima' || $application->status == 'aktif')
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-[#dcfce7] text-[#166534] rounded-full text-label-md font-label-md font-bold border border-[#bbf7d0]">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        {{ strtoupper($application->status) }}
                    </div>
                @elseif($application->status == 'ditolak')
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-error/10 text-error rounded-full text-label-md font-label-md font-bold border border-error/20">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">cancel</span>
                        DITOLAK
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-tertiary/10 text-tertiary rounded-full text-label-md font-label-md font-bold border border-tertiary/20">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">pending</span>
                        {{ strtoupper($application->status) }}
                    </div>
                @endif
                <p class="text-caption font-caption text-on-surface-variant">Update terakhir: <span class="font-medium text-on-surface">{{ $application->updated_at ? \Carbon\Carbon::parse($application->updated_at)->translatedFormat('d F Y') : '-' }}</span></p>
            </div>
        </div>
    </div>

    <!-- Main Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-lg">
        <!-- Left Column: Primary Details -->
        <div class="lg:col-span-2 flex flex-col gap-stack-lg">
            
            <!-- Data Peserta Section -->
            <div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6 border-t-4 border-primary-container">
                <h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
                    <span class="material-symbols-outlined text-primary-container">contact_page</span>
                    Data Peserta
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                    <div class="flex flex-col gap-1">
                        <span class="text-label-md font-label-md text-on-surface-variant">Nama Lengkap</span>
                        <span class="text-body-md font-body-md text-on-surface font-medium">{{ $application->user->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-label-md font-label-md text-on-surface-variant">Asal Instansi</span>
                        <span class="text-body-md font-body-md text-on-surface font-medium">{{ $application->user->asal_instansi ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1 md:col-span-2">
                        <span class="text-label-md font-label-md text-on-surface-variant">Program Studi / Jurusan</span>
                        <span class="text-body-md font-body-md text-on-surface font-medium">{{ $application->user->program_studi ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-label-md font-label-md text-on-surface-variant">Nomor Telepon</span>
                        <span class="text-body-md font-body-md text-on-surface font-medium">{{ $application->user->no_hp ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col gap-1">
                        <span class="text-label-md font-label-md text-on-surface-variant">Email</span>
                        <span class="text-body-md font-body-md text-on-surface font-medium">{{ $application->user->email ?? '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Dokumen Pengajuan Section -->
            <div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6">
                <h3 class="text-title-lg font-title-lg text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
                    <span class="material-symbols-outlined text-primary">folder_open</span> Dokumen
                </h3>
                
                @if($application->permohonanLayanan)
                    <div class="bg-surface rounded-xl border border-outline-variant/30 overflow-hidden divide-y divide-outline-variant/20">
                        @php
                            $dokumenList = [
                                ['nama' => 'KTP', 'file' => $application->permohonanLayanan->file_ktp, 'icon' => 'badge'],
                                ['nama' => 'KTM', 'file' => $application->permohonanLayanan->file_ktm, 'icon' => 'branding_watermark'],
                                ['nama' => 'Surat Pengantar', 'file' => $application->permohonanLayanan->file_surat_pengantar, 'icon' => 'article'],
                                ['nama' => 'Proposal', 'file' => $application->permohonanLayanan->file_proposal, 'icon' => 'book'],
                            ];
                        @endphp
                        
                        @foreach($dokumenList as $dok)
                            @if($dok['file'])
                                <div class="p-4 flex items-center justify-between hover:bg-surface-bright transition-colors group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                            <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">{{ $dok['icon'] }}</span>
                                        </div>
                                        <div>
                                            <p class="text-body-md font-body-md text-on-surface font-medium">{{ $dok['nama'] }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-[#dcfce7] text-[#166534] uppercase tracking-wide mt-1">Tersedia</span>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/dokumen/'.$dok['file']) }}" target="_blank" class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-surface-container" title="Lihat Dokumen">
                                        <span class="material-symbols-outlined text-sm">visibility</span>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-body-md font-body-md text-on-surface-variant py-4">Belum ada dokumen yang dilampirkan terkait permohonan magang ini.</p>
                @endif
            </div>

        </div>

        <!-- Right Column: Secondary Details -->
        <div class="flex flex-col gap-stack-lg">
            
            <!-- Informasi Penempatan Section -->
            <div class="bg-surface-container-lowest rounded-xl shadow-level-1 p-6">
                <h3 class="text-title-lg font-title-lg text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-3">
                    <span class="material-symbols-outlined text-primary-container">location_on</span>
                    Informasi Penempatan
                </h3>
                <div class="flex flex-col gap-4">
                    <div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
                        <span class="text-label-md font-label-md text-on-surface-variant">Dinas Tujuan</span>
                        <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->rekrutmen->dinas->nama ?? '-' }}</span>
                    </div>
                    
                    @if($application->rekrutmen->bidang)
                    <div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
                        <span class="text-label-md font-label-md text-on-surface-variant">Bidang Magang</span>
                        <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->rekrutmen->bidang->nama ?? '-' }}</span>
                    </div>
                    @endif
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
                            <span class="text-label-md font-label-md text-on-surface-variant">Tanggal Mulai</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->tanggal_mulai ? \Carbon\Carbon::parse($application->tanggal_mulai)->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <div class="bg-surface-container-low p-4 rounded-lg flex flex-col gap-1 border border-outline-variant/20">
                            <span class="text-label-md font-label-md text-on-surface-variant">Tanggal Selesai</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->tanggal_selesai ? \Carbon\Carbon::parse($application->tanggal_selesai)->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="pb-12"></div> <!-- Bottom padding -->
</div>

@endsection
