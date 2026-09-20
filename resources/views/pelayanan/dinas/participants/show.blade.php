@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12" x-data="{ previewModalOpen: false, previewUrl: '' }">
    <!-- Page Header -->
    <div class="mb-stack-lg flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('dinas.participants.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <h2 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Detail Peserta Magang</h2>
            </div>
            <p class="text-body-md font-body-md text-on-surface-variant ml-8">Informasi lengkap peserta magang aktif di instansi Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            @if($participant->status == 'aktif')
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                    Aktif Magang
                </span>
            @elseif($participant->status == 'diterima')
                <span class="px-4 py-2 rounded-full text-sm font-semibold bg-primary/10 text-primary border border-primary/20">
                    Diterima
                </span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content: Participant Data -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Identitas Peserta -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">badge</span>
                    Identitas Peserta
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Nama Lengkap</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Email</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">NIK / Identitas</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->nik ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">No. HP / Kontak</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->no_hp ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Akademik -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">school</span>
                    Informasi Akademik
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Instansi Asal</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->asal_instansi ?? ($participant->permohonanLayanan->asal_instansi ?? '-') }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Program Studi / Jurusan</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->program_studi ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-label-sm text-on-surface-variant mb-1">Alamat Instansi</p>
                        <p class="text-body-md text-on-surface">{{ $participant->permohonanLayanan->alamat_instansi ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen Persyaratan -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">folder</span>
                    Berkas Persyaratan
                </h3>
                <div class="space-y-3">
                    @if($participant->ktp_file)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">badge</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">KTP Peserta</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $participant->ktp_file) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $participant->ktp_file) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($participant->surat_pengantar)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">description</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Surat Pengantar</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $participant->surat_pengantar) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $participant->surat_pengantar) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($participant->proposal)
                    <div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-xl hover:bg-surface-container-low transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-[#F59E0B]">picture_as_pdf</span>
                            <div>
                                <p class="text-body-sm font-semibold text-on-surface">Proposal Magang</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $participant->proposal) }}'; previewModalOpen = true" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $participant->proposal) }}" target="_blank" class="text-primary hover:text-primary-dark p-1.5 rounded-lg hover:bg-primary/10 transition-colors" title="Buka di Tab Baru">
                                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>




        </div>

        <!-- Right: Actions & Surat Balasan (1 col) -->
        <div class="space-y-stack-lg">


            <!-- Surat Balasan -->
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">mark_email_read</span>
                    Surat Penerimaan
                </h3>
                
                @if($suratPenerimaan && $suratPenerimaan->file_path)
                    <div class="bg-primary/5 p-4 rounded-xl border border-primary/20 text-center mb-4">
                        <span class="material-symbols-outlined text-[32px] text-primary mb-2">task</span>
                        <p class="text-body-sm text-on-surface font-semibold mb-3">Surat Balasan / Penerimaan Tersedia</p>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="previewUrl = '{{ url('/dokumen/' . $suratPenerimaan->file_path) }}?t={{ time() }}'; previewModalOpen = true" class="flex-1 inline-flex justify-center items-center gap-1.5 bg-primary text-white py-2 px-3 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                                <span class="material-symbols-outlined text-[18px]">visibility</span> Lihat
                            </button>
                            <a href="{{ url('/dokumen/' . $suratPenerimaan->file_path) }}?t={{ time() }}" target="_blank" class="inline-flex justify-center items-center p-2 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg transition-colors" title="Unduh / Tab Baru">
                                <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    
                    <form action="{{ route('dinas.participants.surat.update', $participant->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-outline-variant/30">
                        @csrf
                        <label class="block text-label-sm font-medium text-on-surface mb-2">Ganti Dokumen Surat (PDF)</label>
                        <div class="flex items-center gap-2">
                            <input type="file" name="surat" accept=".pdf" required class="flex-1 text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20">
                            <button type="submit" class="py-2 px-4 bg-surface-container-high hover:bg-outline-variant/30 text-on-surface rounded-xl text-sm font-medium transition-colors">Update</button>
                        </div>
                        @error('surat') <p class="text-error text-[11px] mt-1">{{ $message }}</p> @enderror
                    </form>

                    <div class="mt-3 pt-3 border-t border-outline-variant/30">
                        <form action="{{ route('dinas.participants.surat.generate', $participant->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full justify-center inline-flex items-center gap-1.5 bg-primary/10 text-primary py-2 px-4 rounded-xl text-sm font-semibold hover:bg-primary/20 transition-colors">
                                <span class="material-symbols-outlined text-[18px]">autorenew</span> Re-Generate Surat Otomatis
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/50 text-center mb-4">
                        <span class="material-symbols-outlined text-[32px] text-outline-variant mb-2">drafts</span>
                        <p class="text-body-sm text-on-surface-variant mb-3">Surat penerimaan digital belum ada.</p>
                        
                        <form action="{{ route('dinas.participants.surat.generate', $participant->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex justify-center items-center gap-1.5 bg-primary text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors mx-auto">
                                <span class="material-symbols-outlined text-[18px]">autorenew</span> Auto-Generate Surat
                            </button>
                        </form>
                    </div>
                    
                    <form action="{{ route('dinas.participants.surat.update', $participant->id) }}" method="POST" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-outline-variant/30">
                        @csrf
                        <label class="block text-label-sm font-medium text-on-surface mb-2">Unggah Dokumen Surat (PDF)</label>
                        <input type="file" name="surat" accept=".pdf" required class="block w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 mb-3">
                        <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white hover:bg-primary-dark rounded-xl font-semibold text-sm transition-colors text-center shadow-md shadow-primary/20">
                            Unggah Surat
                        </button>
                        @error('surat') <p class="text-error text-[11px] mt-1">{{ $message }}</p> @enderror
                    </form>
                @endif
            </div>
            
            <div class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-2">Catatan Admin Dinas</h3>
                @if($participant->catatan_admin)
                <div class="bg-surface-container-low p-3 rounded-xl border border-outline-variant/50 mt-2">
                    <p class="text-body-sm text-on-surface italic">"{{ $participant->catatan_admin }}"</p>
                </div>
                @else
                <p class="text-body-sm text-on-surface-variant italic">Tidak ada catatan admin terkait penerimaan peserta ini.</p>
                @endif
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
                    <h3 class="text-lg font-bold text-gray-800">Preview Dokumen</h3>
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
            <div class="flex-1 overflow-hidden bg-gray-100 relative">
                <template x-if="previewUrl">
                    <iframe :src="previewUrl" class="w-full h-full border-0"></iframe>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
