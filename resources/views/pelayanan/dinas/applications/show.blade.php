@extends('pelayanan.layouts.dinas_stitch')

@section('title', 'Detail Pengajuan Magang')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ previewModalOpen: false, previewUrl: '', previewTitle: '', previewExt: '' }">
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
                    @if($application->permohonanLayanan && ($application->permohonanLayanan->file_surat_keluaran || ($application->permohonanLayanan->statusMaster && in_array($application->permohonanLayanan->statusMaster->kode, ['disetujui', 'selesai']))))
                    <div class="flex items-center justify-between p-3 border border-primary/30 bg-primary/5 rounded-xl">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">verified</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Surat Rekomendasi Kesbangpol</p>
                                <p class="text-[11px] text-on-surface-variant">Surat Resmi Kesbangpol (PDF + QR Code)</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ route('surat.pdf', $application->permohonanLayanan->id) }}'; previewTitle = 'Surat Rekomendasi Kesbangpol'; previewExt = 'pdf'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span> Lihat
                            </button>
                            <a href="{{ route('surat.pdf', $application->permohonanLayanan->id) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
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
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->permohonanLayanan->file_ktp) }}'; previewTitle = 'KTP / Identitas'; previewExt = '{{ strtolower(pathinfo($application->permohonanLayanan->file_ktp, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->permohonanLayanan->file_ktp) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_ktm)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#8B5CF6]">badge</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">KTM / Kartu Pelajar</p>
                                <p class="text-[11px] text-on-surface-variant">Identitas Siswa/Mahasiswa</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->permohonanLayanan->file_ktm) }}'; previewTitle = 'KTM / Kartu Pelajar'; previewExt = '{{ strtolower(pathinfo($application->permohonanLayanan->file_ktm, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->permohonanLayanan->file_ktm) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
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
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->permohonanLayanan->file_surat_permohonan) }}'; previewTitle = 'Surat Permohonan'; previewExt = '{{ strtolower(pathinfo($application->permohonanLayanan->file_surat_permohonan, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->permohonanLayanan->file_surat_permohonan) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($application->permohonanLayanan && $application->permohonanLayanan->file_surat_pengantar)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#10B981]">assignment</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Surat Pengantar</p>
                                <p class="text-[11px] text-on-surface-variant">Dari Instansi Pendidikan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->permohonanLayanan->file_surat_pengantar) }}'; previewTitle = 'Surat Pengantar'; previewExt = '{{ strtolower(pathinfo($application->permohonanLayanan->file_surat_pengantar, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->permohonanLayanan->file_surat_pengantar) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
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
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->permohonanLayanan->file_proposal) }}'; previewTitle = 'Proposal Kegiatan'; previewExt = '{{ strtolower(pathinfo($application->permohonanLayanan->file_proposal, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->permohonanLayanan->file_proposal) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
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
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $application->file_surat_penerimaan) }}'; previewTitle = 'Surat Penerimaan'; previewExt = '{{ strtolower(pathinfo($application->file_surat_penerimaan, PATHINFO_EXTENSION)) }}'; previewModalOpen = true" class="text-white bg-green-600 hover:bg-green-700 font-medium text-sm flex items-center gap-1 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $application->file_surat_penerimaan) }}" target="_blank" class="text-green-700 hover:text-green-900 p-1.5 rounded-lg hover:bg-green-100 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
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
                    @php
                        $targetBidangId = $application->bidang_id 
                            ?? optional($application->rekrutmen)->bidang_id 
                            ?? optional($application->user)->bidang_id;

                        if (!$targetBidangId && !empty($bidangs)) {
                            $searchTerms = array_filter([
                                $application->bidang->name ?? (is_string($application->bidang) ? $application->bidang : null),
                                optional(optional($application->rekrutmen)->bidang)->name,
                                $application->bidang_nama ?? null,
                                optional($application->permohonanLayanan)->tempat_kegiatan,
                                optional($application->permohonanLayanan)->keterangan,
                                optional($application->user)->program_studi,
                            ]);

                            foreach ($searchTerms as $term) {
                                $termClean = strtolower(trim($term));
                                if (!$termClean) continue;

                                foreach ($bidangs as $b) {
                                    $bName = strtolower(trim($b->name));
                                    if ($bName === $termClean || str_contains($bName, $termClean) || str_contains($termClean, $bName)) {
                                        $targetBidangId = $b->id;
                                        break 2;
                                    }
                                    if (str_contains($termClean, 'aptika') && (str_contains($bName, 'aptika') || str_contains($bName, 'aplikasi') || str_contains($bName, 'informatika'))) {
                                        $targetBidangId = $b->id;
                                        break 2;
                                    }
                                }
                            }
                        }
                    @endphp
                    <div id="bidang_container" class="{{ $application->status == 'diterima' ? '' : 'hidden' }}">
                        <label class="block text-label-sm font-bold text-on-surface mb-2">Penempatan Bidang Kerja <span class="text-red-500">*</span></label>
                        <select name="bidang_id" id="bidang_id" class="w-full px-3 py-2.5 border border-outline-variant/50 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm font-medium">
                            <option value="">-- Pilih Bidang Penempatan --</option>
                            @foreach($bidangs ?? [] as $b)
                                @php
                                    $isSelected = ($targetBidangId == $b->id);
                                @endphp
                                <option value="{{ $b->id }}" {{ $isSelected ? 'selected' : '' }}>
                                    {{ $b->name }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-on-surface-variant mt-1">Peserta akan ditempatkan di bidang ini dan dapat mulai melakukan presensi harian.</p>
                    </div>

                    <div>
                        <label class="block text-label-sm font-medium text-on-surface mb-2">Catatan Admin Dinas (Opsional)</label>
                        <textarea name="catatan_admin" rows="3" class="w-full px-3 py-2 border border-outline-variant/50 rounded-xl focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary text-sm" placeholder="Tuliskan catatan atau instruksi penempatan...">{{ $application->catatan_admin }}</textarea>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-primary text-white hover:bg-primary-dark rounded-xl font-semibold text-sm transition-colors text-center shadow-md shadow-primary/20 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Preview Dokumen -->
    <div x-show="previewModalOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-hidden bg-black/60 backdrop-blur-sm flex items-center justify-center p-4" 
         style="display: none;">
        <div @click.away="previewModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <!-- Header Modal -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50 shrink-0">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary">visibility</span>
                    <h3 class="text-lg font-bold text-gray-800" x-text="previewTitle || 'Preview Dokumen Lampiran'">Preview Dokumen Lampiran</h3>
                    <a :href="previewUrl" target="_blank" class="inline-flex items-center gap-1 text-xs font-bold text-primary bg-primary/10 hover:bg-primary/20 px-3 py-1.5 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                        Buka di Tab Baru
                    </a>
                </div>
                <button type="button" @click="previewModalOpen = false" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-200 text-gray-500 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
            </div>
            
            <!-- Content Frame -->
            <div class="flex-1 overflow-hidden bg-slate-900/5 relative flex items-center justify-center">
                <template x-if="previewUrl">
                    <div class="w-full h-full flex flex-col">
                        <!-- Image Viewer -->
                        <template x-if="['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'].includes(previewExt)">
                            <div class="w-full h-full flex items-center justify-center p-6 bg-slate-900/90 overflow-auto">
                                <img :src="previewUrl" class="max-h-full max-w-full object-contain rounded-lg shadow-2xl border border-white/10" alt="Preview Image" />
                            </div>
                        </template>

                        <!-- Office Doc Viewer (Word / Excel) -->
                        <template x-if="['doc', 'docx', 'xls', 'xlsx'].includes(previewExt)">
                            <div class="w-full h-full flex flex-col">
                                <div class="bg-amber-50 border-b border-amber-200 px-4 py-2 text-xs text-amber-800 flex items-center justify-between shrink-0">
                                    <span class="flex items-center gap-1.5 font-semibold">
                                        <span class="material-symbols-outlined text-[16px]">info</span>
                                        Dokumen Office (.docx/.xlsx) ditampilkan via Online Viewer
                                    </span>
                                    <a :href="previewUrl + '?download=1'" download class="font-bold text-amber-900 underline hover:text-amber-700">Unduh File Asli</a>
                                </div>
                                <iframe :src="'https://docs.google.com/gview?url=' + encodeURIComponent(previewUrl) + '&embedded=true'" class="w-full h-full border-0"></iframe>
                            </div>
                        </template>

                        <!-- Default PDF / Standard Web Viewer -->
                        <template x-if="!['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'doc', 'docx', 'xls', 'xlsx'].includes(previewExt)">
                            <iframe :src="previewUrl" class="w-full h-full border-0"></iframe>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function toggleDinasFields(val) {
    const bidangContainer = document.getElementById('bidang_container');
    const bidangSelect = document.getElementById('bidang_id');

    if (val === 'diterima') {
        bidangContainer.classList.remove('hidden');
        if (bidangSelect) {
            bidangSelect.required = true;
            if (!bidangSelect.value) {
                const preselected = bidangSelect.querySelector('option[selected]');
                if (preselected && preselected.value) {
                    bidangSelect.value = preselected.value;
                } else if (bidangSelect.options.length > 1) {
                    bidangSelect.selectedIndex = 1;
                }
            }
        }
    } else {
        bidangContainer.classList.add('hidden');
        if (bidangSelect) {
            bidangSelect.required = false;
        }
    }
}
</script>
@endsection
