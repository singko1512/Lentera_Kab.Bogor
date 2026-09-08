@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center gap-4 mb-8">
        <button onclick="history.back()" class="p-2 text-on-surface hover:bg-white hover:shadow-sm rounded-full transition-all flex items-center justify-center shrink-0">
            <span class="material-symbols-outlined">arrow_back</span>
        </button>
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface mb-1 tracking-tight">{{ $application->nama_lengkap }}</h2>
            <div class="flex items-center gap-3">
                <span class="text-caption font-caption text-on-surface-variant font-semibold flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[16px]">school</span>
                    {{ $application->instansi_asal }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[12px] font-label-md font-bold bg-[#FEF3C7] text-[#92400E]">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#D97706]"></span>
                    Menunggu Verifikasi
                </span>
            </div>
        </div>
    </div>

    <div class="flex flex-col gap-8">
        <!-- ROW 1 -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Ketua Pengaju -->
            <div class="lg:col-span-2 flex flex-col gap-8">
                <!-- Participant Summary Card -->
                <section class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30 h-full">
                    <h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
                        <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">person</span>
                        Ketua Pengaju (Penanggung Jawab)
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Jenis Pelayanan</p>
                            <p class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->jenis_layanan }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Pengajuan</p>
                            <p class="text-body-md font-body-md text-on-surface">{{ \Carbon\Carbon::parse($application->created_at)->format('d F Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8 mt-6">
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Lengkap</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->nama_lengkap }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Asal Instansi/Universitas</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->instansi_asal }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Program Studi/Jurusan</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->jurusan }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">NIM/NISN</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->nim }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Nomor Telepon (WhatsApp)</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->no_hp }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Email</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->email }}</span>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right Column: Tujuan & Jadwal -->
            <div class="flex flex-col gap-8">
                <section class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30 h-full">
                    <h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
                        <span class="material-symbols-outlined text-[#F59E0B] bg-[#F59E0B]/10 p-1.5 rounded-lg">domain</span>
                        Tujuan &amp; Jadwal
                    </h3>
                    <div class="flex flex-col gap-4">
                        <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Dinas Tujuan</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">Badan Kesatuan Bangsa dan Politik</span>
                        </div>
                        <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
                            <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Bidang / Unit Kerja</span>
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $application->bidang }}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
                                <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Mulai</span>
                                <span class="text-body-md font-body-md text-on-surface font-semibold">{{ \Carbon\Carbon::parse($application->tanggal_mulai)->format('d M Y') }}</span>
                            </div>
                            <div class="bg-surface-container-low p-4 rounded-xl flex flex-col gap-1 border border-outline-variant/40">
                                <span class="text-[12px] font-bold text-on-surface-variant uppercase tracking-wider">Tanggal Selesai</span>
                                <span class="text-body-md font-body-md text-on-surface font-semibold">{{ \Carbon\Carbon::parse($application->tanggal_selesai)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- ROW 2 -->
        <div class="flex flex-col gap-8">
            <!-- Full Width: Dokumen Lampiran -->
            <div class="w-full flex flex-col gap-8">
                <form action="{{ route('kesbangpol.layanan.verify', $application->id) }}" method="POST" class="flex flex-col gap-6">
                    @csrf
                    <section class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30 h-full">
                        <h3 class="text-title-lg font-title-lg text-on-surface mb-6 flex items-center gap-2 border-b border-outline-variant/40 pb-4">
                            <span class="material-symbols-outlined text-primary bg-primary/10 p-1.5 rounded-lg">attach_file</span>
                            Dokumen Lampiran
                        </h3>
                        <div class="bg-surface-container-low rounded-xl border border-outline-variant/40 overflow-hidden divide-y divide-outline-variant/40">
                            <!-- Doc 1 -->
                            <div class="flex flex-col">
                                <div class="p-4 flex items-center justify-between hover:bg-white hover:shadow-sm transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined">description</span>
                                        </div>
                                        <div>
                                            <p class="text-body-md font-body-md text-on-surface font-semibold">Surat Pengantar Kampus.pdf</p>
                                            <p class="text-[12px] font-medium text-on-surface-variant">Mandatory</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ asset('storage/' . $application->file_surat_pengantar) }}" target="_blank" class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Preview Dokumen">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </a>
                                        <a href="{{ asset('storage/' . $application->file_surat_pengantar) }}" download class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Unduh Dokumen">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 flex gap-2">
                                    <input type="text" name="note_surat_pengantar" placeholder="Beri catatan jika dokumen ini tidak sesuai ketentuan..." class="flex-1 bg-white border border-outline-variant/50 rounded-xl px-4 py-2.5 text-[13px] focus:ring-2 focus:ring-error/20 focus:border-error outline-none transition-all placeholder:text-on-surface-variant/50">
                                    <button type="button" class="px-3 flex items-center justify-center bg-primary/10 text-primary hover:bg-primary/20 hover:scale-105 active:scale-95 rounded-xl transition-all" title="Simpan Catatan">
                                        <span class="material-symbols-outlined text-[20px]">check</span>
                                    </button>
                                </div>
                            </div>
                            
                            @if($application->file_proposal)
                            <!-- Doc 2 -->
                            <div class="flex flex-col border-t border-outline-variant/40">
                                <div class="p-4 flex items-center justify-between hover:bg-white hover:shadow-sm transition-all group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined">description</span>
                                        </div>
                                        <div>
                                            <p class="text-body-md font-body-md text-on-surface font-semibold">Proposal_Magang.pdf</p>
                                            <p class="text-[12px] font-medium text-on-surface-variant">Mandatory</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <a href="{{ asset('storage/' . $application->file_proposal) }}" target="_blank" class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Preview Dokumen">
                                            <span class="material-symbols-outlined">visibility</span>
                                        </a>
                                        <a href="{{ asset('storage/' . $application->file_proposal) }}" download class="p-2 text-on-surface-variant hover:text-primary transition-colors cursor-pointer rounded-lg hover:bg-primary/10" title="Unduh Dokumen">
                                            <span class="material-symbols-outlined">download</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="px-4 pb-4 flex gap-2">
                                    <input type="text" name="note_proposal" placeholder="Beri catatan jika dokumen ini tidak sesuai ketentuan..." class="flex-1 bg-white border border-outline-variant/50 rounded-xl px-4 py-2.5 text-[13px] focus:ring-2 focus:ring-error/20 focus:border-error outline-none transition-all placeholder:text-on-surface-variant/50">
                                    <button type="button" class="px-3 flex items-center justify-center bg-primary/10 text-primary hover:bg-primary/20 hover:scale-105 active:scale-95 rounded-xl transition-all" title="Simpan Catatan">
                                        <span class="material-symbols-outlined text-[20px]">check</span>
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </section>
                    
                    <!-- Actions -->
                    <div class="flex justify-end gap-3 mt-2">
                        <button type="submit" name="action" value="reject" class="px-6 py-2.5 bg-white border border-error text-error text-label-md font-label-md font-bold rounded-xl hover:bg-error/5 hover:shadow-sm transition-all focus:ring-2 focus:ring-error/20 outline-none w-full sm:w-auto">
                            Tolak Pengajuan
                        </button>
                        <button type="submit" name="action" value="approve" class="px-6 py-2.5 bg-primary text-white text-label-md font-label-md font-bold rounded-xl hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all focus:ring-2 focus:ring-primary/20 outline-none flex items-center justify-center gap-2 w-full sm:w-auto">
                            <span class="material-symbols-outlined text-[18px]">check_circle</span>
                            Setujui Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>
@endsection
