@extends('pelayanan.layouts.dinas_stitch')

@section('title', 'Detail Pengajuan Magang')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('dinas.applications.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h1 class="text-display-sm font-display-sm text-on-surface">Detail Pengajuan</h1>
            </div>
            <p class="text-body-md text-on-surface-variant">Review dan verifikasi permohonan magang peserta</p>
        </div>
        
        <!-- Status Badge -->
        <div>
            @php
                $statusColor = 'bg-surface-container-high text-on-surface';
                if ($application->status == 'menunggu') $statusColor = 'bg-[#F59E0B]/10 text-[#F59E0B] border-[#F59E0B]/20';
                if ($application->status == 'diterima' || $application->status == 'aktif') $statusColor = 'bg-primary/10 text-primary border-primary/20';
                if ($application->status == 'ditolak') $statusColor = 'bg-error/10 text-error border-error/20';
                if ($application->status == 'perlu_revisi') $statusColor = 'bg-amber-100 text-amber-800 border-amber-300';
            @endphp
            <span class="px-4 py-2 rounded-full border {{ $statusColor }} font-semibold text-sm flex items-center gap-2 shadow-sm">
                @if($application->status == 'menunggu')
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                @endif
                {{ ucfirst($application->status) }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 text-green-700 p-4 rounded-xl border border-green-200 flex items-center gap-2">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Main Content: Applicant Details -->
        <div class="xl:col-span-2 space-y-6">
            <!-- Data Diri Card -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/5 rounded-bl-full -z-10"></div>
                <h3 class="text-title-md font-title-md text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">person</span>
                    Data Pemohon
                </h3>
                
                <div class="flex flex-col sm:flex-row gap-6 mb-6 pb-6 border-b border-outline-variant/40">
                    <div class="w-20 h-20 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold text-2xl border-2 border-primary/20 flex-shrink-0">
                        {{ strtoupper(substr($application->user->nama ?? ($application->user->name ?? 'P'), 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-title-lg font-title-lg text-on-surface mb-1">{{ $application->user->nama ?? ($application->user->name ?? 'User Tidak Ditemukan') }}</h4>
                        <div class="flex flex-wrap gap-3 mt-2">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-low rounded-lg text-sm text-on-surface-variant border border-outline-variant/50">
                                <span class="material-symbols-outlined text-[16px]">school</span>
                                {{ $application->user->asal_instansi ?? ($application->instansi_asal ?? '-') }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-surface-container-low rounded-lg text-sm text-on-surface-variant border border-outline-variant/50">
                                <span class="material-symbols-outlined text-[16px]">badge</span>
                                {{ $application->user->nim ?? ($application->nim_nis ?? '-') }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Email</p>
                        <p class="text-body-md text-on-surface font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-primary">mail</span>
                            {{ $application->user->email ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">No. WhatsApp</p>
                        <p class="text-body-md text-on-surface font-medium flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-primary">call</span>
                            {{ $application->user->no_hp ?? '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Jurusan / Program Studi</p>
                        <p class="text-body-md text-on-surface font-medium">{{ $application->user->program_studi ?? ($application->jurusan ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Alamat Domisili</p>
                        <p class="text-body-md text-on-surface font-medium">{{ $application->user->alamat ?? ($application->alamat ?? '-') }}</p>
                    </div>
                </div>
            </div>

            <!-- Dokumen Lampiran Card -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">folder_open</span>
                    Dokumen Lampiran Pengajuan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_surat_keluaran)
                    <div class="flex items-center justify-between p-3 border border-primary/30 bg-primary/5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">verified</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Surat Rekomendasi Kesbangpol</p>
                                <p class="text-[11px] text-on-surface-variant">Surat Resmi Kesbangpol</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($application->permohonanLayanan->file_surat_keluaran) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
                    </div>
                    @endif

                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_ktp)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B]">badge</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">KTP / Identitas</p>
                                <p class="text-[11px] text-on-surface-variant">Identitas Pemohon</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($application->permohonanLayanan->file_ktp) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
                    </div>
                    @endif

                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_surat_permohonan)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B]">description</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Surat Permohonan</p>
                                <p class="text-[11px] text-on-surface-variant">Dari Perguruan Tinggi / Sekolah</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($application->permohonanLayanan->file_surat_permohonan) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
                    </div>
                    @endif

                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_proposal)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B]">menu_book</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Proposal Kegiatan</p>
                                <p class="text-[11px] text-on-surface-variant">Rencana Kegiatan Magang</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($application->permohonanLayanan->file_proposal) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
                    </div>
                    @endif

                    @if($application->file_surat_penerimaan)
                    <div class="flex items-center justify-between p-3 border border-green-300 bg-green-50 rounded-xl md:col-span-2">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-green-600">task_alt</span>
                            <div>
                                <p class="text-body-sm font-semibold text-green-900">Surat Penerimaan / Balasan Dinas</p>
                                <p class="text-[11px] text-green-700">Diupload oleh Dinas</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($application->file_surat_penerimaan) }}" target="_blank" class="text-white bg-green-600 hover:bg-green-700 font-medium text-sm flex items-center gap-1 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">download</span> Unduh
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Content: Application & Action -->
        <div class="space-y-6">
            <!-- Detail Pilihan Magang -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 border-b border-outline-variant/40 pb-3">
                    Detail Pengajuan Dinas
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Dinas Tujuan</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $application->dinas->name ?? ($dinas->name ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Penempatan Bidang</p>
                        <p class="text-body-md font-bold text-primary">{{ $application->bidang->name ?? 'Belum Ditentukan' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Periode Magang</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="material-symbols-outlined text-[16px] text-primary">calendar_month</span>
                            <span class="text-sm font-medium">
                                {{ $application->tanggal_mulai ? \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') : '-' }}
                                <span class="text-on-surface-variant mx-1">-</span>
                                {{ $application->tanggal_selesai ? \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Tanggal Pengajuan</p>
                        <p class="text-body-sm text-on-surface">{{ $application->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Formulir Keputusan Admin Dinas -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border-2 border-primary/20">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">gavel</span>
                    Keputusan Verifikasi Dinas
                </h3>
                <form action="{{ route('dinas.applications.verify', $application->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-label-sm font-bold text-on-surface mb-2">Keputusan Dinas <span class="text-red-500">*</span></label>
                        <select name="status" id="dinas_status_select" class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm font-medium" required onchange="toggleDinasFields(this.value)">
                            <option value="">-- Pilih Keputusan --</option>
                            <option value="diterima" {{ $application->status == 'diterima' ? 'selected' : '' }}>Setujui & Terima Peserta (Acc)</option>
                            <option value="perlu_revisi" {{ $application->status == 'perlu_revisi' ? 'selected' : '' }}>Minta Revisi Dokumen</option>
                            <option value="ditolak" {{ $application->status == 'ditolak' ? 'selected' : '' }}>Tolak Permohonan</option>
                        </select>
                    </div>

                    <!-- Bidang Penempatan Dropdown -->
                    <div id="bidang_container" class="{{ $application->status == 'diterima' ? '' : 'hidden' }}">
                        <label class="block text-label-sm font-bold text-on-surface mb-2">Penempatan Bidang Kerja <span class="text-red-500">*</span></label>
                        <select name="bidang_id" id="bidang_id" class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm font-medium">
                            <option value="">-- Pilih Bidang Penempatan --</option>
                            @foreach($bidangs ?? [] as $b)
                                <option value="{{ $b->id }}" {{ ($application->bidang_id == $b->id || (optional($application->user)->bidang_id == $b->id)) ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-on-surface-variant mt-1">Peserta akan ditempatkan di bidang ini dan dapat mulai melakukan presensi harian.</p>
                    </div>

                    <!-- Upload Surat Penerimaan Dinas -->
                    <div id="surat_penerimaan_container" class="{{ $application->status == 'diterima' ? '' : 'hidden' }}">
                        <label class="block text-label-sm font-bold text-on-surface mb-2">Upload Surat Penerimaan Dinas (Optional, PDF)</label>
                        <input type="file" name="file_surat_penerimaan" accept=".pdf" class="block w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-2">Catatan Admin Dinas (Opsional)</label>
                        <textarea name="catatan_admin" rows="3" class="w-full px-3 py-2 border border-outline-variant/50 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm" placeholder="Tuliskan catatan atau instruksi penempatan...">{{ $application->catatan_admin }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-primary text-white hover:bg-primary-dark rounded-xl font-semibold text-sm transition-colors text-center shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Keputusan Dinas
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDinasFields(val) {
    const bidangContainer = document.getElementById('bidang_container');
    const suratContainer = document.getElementById('surat_penerimaan_container');
    const bidangSelect = document.getElementById('bidang_id');

    if (val === 'diterima') {
        bidangContainer.classList.remove('hidden');
        suratContainer.classList.remove('hidden');
        if (bidangSelect) bidangSelect.required = true;
    } else {
        bidangContainer.classList.add('hidden');
        suratContainer.classList.add('hidden');
        if (bidangSelect) bidangSelect.required = false;
    }
}
</script>
@endsection
