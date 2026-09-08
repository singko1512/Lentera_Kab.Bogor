@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
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
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->user->nama ?? '-' }}</p>
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
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->instansi_asal ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Program Studi / Jurusan</p>
                        <p class="text-body-md font-medium text-on-surface">{{ $participant->jurusan ?? '-' }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-label-sm text-on-surface-variant mb-1">Alamat Instansi</p>
                        <p class="text-body-md text-on-surface">{{ $participant->alamat_instansi ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen Magang -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">folder_open</span>
                    Dokumen & File
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
                        <a href="{{ Storage::url($participant->ktp_file) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
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
                        <a href="{{ Storage::url($participant->surat_pengantar) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
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
                        <a href="{{ Storage::url($participant->proposal) }}" target="_blank" class="text-primary hover:text-primary-dark font-medium text-sm flex items-center gap-1 bg-primary/10 px-3 py-1.5 rounded-lg">
                            <span class="material-symbols-outlined text-[16px]">visibility</span> Lihat
                        </a>
                    </div>
                    @endif
                </div>
            <!-- Absensi -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">how_to_reg</span>
                    Riwayat Absensi
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface-container-low text-on-surface-variant">
                            <tr>
                                <th class="p-3 rounded-l-lg">Tanggal</th>
                                <th class="p-3">Check In</th>
                                <th class="p-3">Check Out</th>
                                <th class="p-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/30">
                            @forelse($participant->absensis ?? [] as $absen)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="p-3 font-medium">{{ \Carbon\Carbon::parse($absen->tanggal)->format('d M Y') }}</td>
                                <td class="p-3">
                                    {{ $absen->waktu_masuk ?? '-' }}
                                    @if($absen->foto_masuk)
                                        <a href="{{ Storage::url($absen->foto_masuk) }}" target="_blank" class="text-xs text-primary underline ml-1">Foto</a>
                                    @endif
                                    @if($absen->lokasi_masuk)
                                        <a href="https://maps.google.com/?q={{ $absen->lokasi_masuk }}" target="_blank" class="text-xs text-primary underline ml-1">Map</a>
                                    @endif
                                </td>
                                <td class="p-3">
                                    {{ $absen->waktu_pulang ?? '-' }}
                                    @if($absen->foto_pulang)
                                        <a href="{{ Storage::url($absen->foto_pulang) }}" target="_blank" class="text-xs text-primary underline ml-1">Foto</a>
                                    @endif
                                    @if($absen->lokasi_pulang)
                                        <a href="https://maps.google.com/?q={{ $absen->lokasi_pulang }}" target="_blank" class="text-xs text-primary underline ml-1">Map</a>
                                    @endif
                                </td>
                                <td class="p-3">
                                    <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold">{{ strtoupper($absen->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-on-surface-variant italic">Belum ada data absensi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Jurnal -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 flex items-center gap-2 border-b border-outline-variant/40 pb-3">
                    <span class="material-symbols-outlined text-primary text-[20px]">edit_note</span>
                    Jurnal Kegiatan
                </h3>
                <div class="space-y-4">
                    @forelse($participant->jurnals ?? [] as $jurnal)
                    <div class="p-4 bg-surface-container-lowest border border-outline-variant/30 rounded-xl">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-sm font-semibold text-on-surface-variant">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('l, d M Y') }}</span>
                            @if($jurnal->status_verifikasi)
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">verified</span> Terverifikasi
                                </span>
                            @else
                                <form action="{{ route('dinas.participants.jurnal.verify', ['id' => $participant->id, 'jurnal_id' => $jurnal->id]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-3 py-1 bg-primary text-white rounded-md text-xs font-bold hover:bg-primary-dark transition-colors">
                                        Verifikasi
                                    </button>
                                </form>
                            @endif
                        </div>
                        <p class="text-sm text-on-surface">{{ $jurnal->kegiatan }}</p>
                    </div>
                    @empty
                    <p class="text-center text-on-surface-variant italic py-4">Belum ada jurnal kegiatan.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Sidebar Content: Placement & Letters -->
        <div class="space-y-6">
            <!-- Penempatan -->
            <div class="bg-white rounded-2xl shadow-soft p-6 border border-outline-variant/30">
                <h3 class="text-title-md font-title-md text-on-surface mb-4 border-b border-outline-variant/40 pb-3">
                    Informasi Penempatan
                </h3>
                <div class="space-y-4">
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Dinas</p>
                        <p class="text-body-md font-semibold text-on-surface">{{ $participant->dinas->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-2">Bidang / Sub-unit Penempatan</p>
                        <form action="{{ route('dinas.participants.penempatan.update', $participant->id) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <select name="bidang_id" class="flex-1 rounded-xl border-outline-variant/30 text-sm focus:ring-primary focus:border-primary bg-surface-container-lowest">
                                <option value="">Pilih Bidang Penempatan</option>
                                @foreach($dinas->bidangs as $bidang)
                                    <option value="{{ $bidang->id }}" {{ $participant->bidang_id == $bidang->id ? 'selected' : '' }}>
                                        {{ $bidang->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="py-2 px-4 bg-primary text-white hover:bg-primary-dark rounded-xl text-sm font-medium transition-colors shadow-md shadow-primary/20">
                                Simpan
                            </button>
                        </form>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant mb-1">Periode Magang</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="material-symbols-outlined text-[16px] text-primary">calendar_month</span>
                            <span class="text-sm font-medium">
                                {{ $participant->tanggal_mulai ? \Carbon\Carbon::parse($participant->tanggal_mulai)->format('d M Y') : '-' }}
                                <span class="text-on-surface-variant mx-1">-</span>
                                {{ $participant->tanggal_selesai ? \Carbon\Carbon::parse($participant->tanggal_selesai)->format('d M Y') : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

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
                        <a href="{{ Storage::url($suratPenerimaan->file_path) }}" target="_blank" class="w-full inline-flex justify-center items-center gap-2 bg-primary text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                            <span class="material-symbols-outlined text-[18px]">download</span> Unduh Surat
                        </a>
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
                @else
                    <div class="bg-surface-container-low p-4 rounded-xl border border-outline-variant/50 text-center mb-4">
                        <span class="material-symbols-outlined text-[32px] text-outline-variant mb-2">drafts</span>
                        <p class="text-body-sm text-on-surface-variant">Surat penerimaan digital belum diunggah.</p>
                    </div>
                    
                    <form action="{{ route('dinas.participants.surat.update', $participant->id) }}" method="POST" enctype="multipart/form-data" class="mt-4">
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
</div>
@endsection
