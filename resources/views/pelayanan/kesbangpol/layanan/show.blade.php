@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6" x-data="{ previewModalOpen: false, previewUrl: '' }">
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Kolom Data Pemohon -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex flex-wrap justify-between items-center gap-2">
                    <h2 class="text-lg font-bold text-gray-800 m-0">Data Pemohon</h2>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('kesbangpol.layanan.generate_pdf', $layanan->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold rounded-full transition-colors shadow-sm" title="Generate & Download Surat Rekomendasi PDF dengan QR Code">
                            <span class="material-symbols-outlined text-[16px]">picture_as_pdf</span>
                            Surat PDF (QR Code)
                        </a>
                        <a href="{{ route('kesbangpol.layanan.generate_docx', $layanan->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 border border-blue-200 text-xs font-semibold rounded-full transition-colors shadow-sm" title="Generate & Download Surat Rekomendasi (.docx)">
                            <span class="material-symbols-outlined text-[16px]">description</span>
                            Generate Surat (.docx)
                        </a>
                        @php
                            $statusKode = strtolower($layanan->statusMaster->kode ?? '');
                            $badgeClass = match($statusKode) {
                                'menunggu_verifikasi', 'menunggu', 'proses' => 'bg-amber-100 text-amber-800 border border-amber-200',
                                'disetujui', 'selesai', 'diterima' => 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                                'ditolak' => 'bg-rose-100 text-rose-800 border border-rose-200',
                                'perlu_revisi' => 'bg-orange-100 text-orange-800 border border-orange-200',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200'
                            };
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $badgeClass }}">
                                {{ $layanan->statusMaster->nama ?? 'Unknown' }}
                            </span>
                            @if($layanan->isRevisiSelesai())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-xs animate-pulse">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                                    Revisi Baru Masuk
                                </span>
                            @elseif($layanan->isMenungguRevisiUser())
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-800 border border-amber-300 shadow-xs">
                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                                    Menunggu Revisi Pemohon
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    {{-- Alert Box Informasi Revisi --}}
                    @if($layanan->isRevisiSelesai())
                        <div class="mb-6 p-4 bg-emerald-50/90 border-2 border-emerald-400 rounded-xl shadow-xs text-emerald-950">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                                    <span class="material-symbols-outlined text-[24px]">published_with_changes</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <h4 class="font-bold text-sm text-emerald-950 flex items-center gap-2">
                                            Pemohon Telah Mengunggah Dokumen Revisi
                                            <span class="text-[11px] font-bold bg-emerald-200 text-emerald-900 px-2 py-0.5 rounded-full">Siap Diverifikasi Ulang</span>
                                        </h4>
                                        <span class="text-xs font-semibold text-emerald-800 bg-white/80 px-2.5 py-1 rounded-lg border border-emerald-200">
                                            <span class="material-symbols-outlined text-[14px] align-middle mr-0.5">schedule</span>
                                            {{ $layanan->tanggal_revisi ? $layanan->tanggal_revisi->format('d/m/Y H:i') : $layanan->updated_at->format('d/m/Y H:i') }} WIB
                                        </span>
                                    </div>
                                    <div class="mt-2.5 text-xs space-y-2 text-emerald-900 leading-relaxed">
                                        @if($layanan->keterangan)
                                            <div class="bg-white/90 p-2.5 rounded-lg border border-emerald-200">
                                                <span class="font-bold text-gray-700">Permintaan Revisi Sebelumnya (dari Kesbangpol):</span>
                                                <p class="text-rose-700 font-semibold mt-0.5">{{ $layanan->keterangan }}</p>
                                            </div>
                                        @endif
                                        @if($layanan->catatan_pemohon)
                                            <div class="bg-white/90 p-2.5 rounded-lg border border-emerald-200">
                                                <span class="font-bold text-gray-700">Pesan / Keterangan dari Pemohon:</span>
                                                <p class="text-gray-900 font-medium mt-0.5 italic">"{{ $layanan->catatan_pemohon }}"</p>
                                            </div>
                                        @endif
                                        @if(!empty($layanan->dokumen_direvisi))
                                            <div class="flex items-center flex-wrap gap-1.5 pt-0.5">
                                                <span class="font-bold text-emerald-950">Dokumen yang baru diupload:</span>
                                                @php
                                                    $labels = [
                                                        'file_ktp' => 'KTP / Identitas',
                                                        'file_ktm' => 'KTM / Kartu Pelajar',
                                                        'file_surat_permohonan' => 'Surat Permohonan',
                                                        'file_surat_pengantar' => 'Surat Pengantar',
                                                        'file_surat_lokasi' => 'Surat dari Lokasi',
                                                        'file_proposal' => 'Proposal',
                                                        'file_surat_kesbangpol_jabar' => 'Surat Kesbangpol Jabar',
                                                        'file_surat_kemendagri' => 'Surat Kemendagri',
                                                        'file_surat_rekomendasi_lama' => 'Surat Rekomendasi Lama',
                                                        'file_pendukung' => 'Dokumen Pendukung',
                                                    ];
                                                @endphp
                                                @foreach($layanan->dokumen_direvisi as $docKey)
                                                    <span class="px-2 py-0.5 bg-emerald-600 text-white rounded font-bold text-[11px] shadow-xs">
                                                        {{ $labels[$docKey] ?? $docKey }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif($layanan->isMenungguRevisiUser())
                        <div class="mb-6 p-4 bg-amber-50/90 border-2 border-amber-300 rounded-xl shadow-xs text-amber-950">
                            <div class="flex items-start gap-3.5">
                                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                                    <span class="material-symbols-outlined text-[24px]">pending_actions</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-sm text-amber-950">Menunggu Pemohon Mengunggah Dokumen Revisi</h4>
                                    <p class="text-xs text-amber-800 mt-0.5">Kesbangpol telah meminta perbaikan pada permohonan ini. Pemohon belum mengunggah file revisi terbaru.</p>
                                    @if($layanan->keterangan)
                                        <div class="mt-2 bg-white/90 p-2.5 rounded-lg border border-amber-200 text-xs">
                                            <span class="font-bold text-gray-700">Catatan Revisi yang Diminta:</span>
                                            <p class="text-rose-600 font-bold mt-0.5">{{ $layanan->keterangan }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-y-4 gap-x-2 text-sm text-gray-700">
                        <div class="font-semibold md:col-span-1">ID Permohonan</div>
                        <div class="md:col-span-2">#{{ $layanan->id }}</div>
                        
                        <div class="font-semibold md:col-span-1">Jenis Layanan</div>
                        <div class="md:col-span-2 text-capitalize">{{ $layanan->jenisLayanan->nama ?? '-' }}</div>
                        
                        <div class="font-semibold md:col-span-1">Atas Nama</div>
                        <div class="md:col-span-2">{{ $layanan->atas_nama }}</div>
                        
                        <div class="font-semibold md:col-span-1">Asal Instansi</div>
                        <div class="md:col-span-2">{{ $layanan->asal_instansi ?? '-' }}</div>
                        
                        <div class="font-semibold md:col-span-1">Nomor HP</div>
                        <div class="md:col-span-2">{{ $layanan->no_hp }}</div>

                        @if($layanan->judul_kegiatan)
                        <div class="font-semibold md:col-span-1">Judul / Tema</div>
                        <div class="md:col-span-2">{{ $layanan->judul_kegiatan }}</div>
                        @endif
                        
                        <div class="font-semibold md:col-span-1">Tanggal Pelaksanaan</div>
                        <div class="md:col-span-2">{{ \Carbon\Carbon::parse($layanan->tanggal_mulai)->format('d/m/Y') }} s/d {{ \Carbon\Carbon::parse($layanan->tanggal_selesai)->format('d/m/Y') }}</div>
                        
                        @if($layanan->keterangan)
                            <div class="font-semibold md:col-span-1">Catatan Revisi Kesbangpol</div>
                            <div class="md:col-span-2">
                                <span class="text-rose-600 font-semibold">{{ $layanan->keterangan }}</span>
                                @if($layanan->isRevisiSelesai())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                                        Sudah ditindaklanjuti pemohon
                                    </span>
                                @elseif($layanan->isMenungguRevisiUser())
                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-300">
                                        Menunggu respon pemohon
                                    </span>
                                @endif
                            </div>
                        @endif

                        @if($layanan->catatan_pemohon)
                            <div class="font-semibold md:col-span-1">Pesan Pemohon</div>
                            <div class="md:col-span-2 text-gray-800 font-medium italic">"{{ $layanan->catatan_pemohon }}"</div>
                        @endif
                    </div>

                    <hr class="my-6 border-gray-200">
                    
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-md font-bold text-gray-800 m-0">Dokumen Lampiran</h3>
                        @if($layanan->isRevisiSelesai())
                            <span class="text-xs text-emerald-700 font-semibold flex items-center gap-1 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                                <span class="material-symbols-outlined text-[14px]">info</span>
                                Dokumen bertanda hijau adalah hasil revisi terbaru
                            </span>
                        @endif
                    </div>

                    <div class="flex flex-col gap-3">
                        @php
                            $dokumens = [
                                'file_ktp' => ['icon' => 'badge', 'label' => 'KTP / Identitas'],
                                'file_ktm' => ['icon' => 'badge', 'label' => 'KTM / Kartu Pelajar'],
                                'file_surat_permohonan' => ['icon' => 'description', 'label' => 'Surat Permohonan ke Kesbangpol'],
                                'file_surat_pengantar' => ['icon' => 'description', 'label' => 'Surat Pengantar Asli'],
                                'file_surat_lokasi' => ['icon' => 'location_on', 'label' => 'Surat dari Lokasi'],
                                'file_proposal' => ['icon' => 'menu_book', 'label' => 'Proposal'],
                                'file_surat_kesbangpol_jabar' => ['icon' => 'verified', 'label' => 'Surat Kesbangpol Jabar'],
                                'file_surat_kemendagri' => ['icon' => 'verified', 'label' => 'Surat Kemendagri'],
                                'file_surat_rekomendasi_lama' => ['icon' => 'history', 'label' => 'Surat Rekomendasi Lama'],
                                'file_pendukung' => ['icon' => 'folder', 'label' => 'Dokumen Pendukung Lainnya'],
                            ];
                        @endphp
                        @foreach($dokumens as $field => $info)
                            @if($layanan->$field)
                            @php
                                $isNewlyRevised = is_array($layanan->dokumen_direvisi) && in_array($field, $layanan->dokumen_direvisi);
                                if (!$isNewlyRevised && $layanan->isRevisiSelesai() && !empty($layanan->keterangan)) {
                                    $ketLower = strtolower($layanan->keterangan);
                                    if (str_contains($field, 'ktp') && (str_contains($ketLower, 'ktp') || str_contains($ketLower, 'identitas'))) {
                                        $isNewlyRevised = true;
                                    } elseif (str_contains($field, 'proposal') && str_contains($ketLower, 'proposal')) {
                                        $isNewlyRevised = true;
                                    } elseif (str_contains($field, 'pengantar') && str_contains($ketLower, 'pengantar')) {
                                        $isNewlyRevised = true;
                                    }
                                }
                            @endphp
                            <div class="flex items-center justify-between p-3 rounded-lg border {{ $isNewlyRevised ? 'border-emerald-300 bg-emerald-50/50 shadow-xs' : 'border-gray-200 hover:bg-gray-50' }} transition-colors">
                                <div class="flex items-center text-gray-700 flex-wrap gap-2">
                                    <span class="material-symbols-outlined {{ $isNewlyRevised ? 'text-emerald-600' : 'text-primary' }} mr-1">{{ $info['icon'] }}</span>
                                    <span class="font-medium {{ $isNewlyRevised ? 'text-emerald-950 font-bold' : '' }}">{{ $info['label'] }}</span>
                                    @if($isNewlyRevised)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-600 text-white shadow-xs">
                                            <span class="material-symbols-outlined text-[13px]">check_circle</span>
                                            Baru Direvisi
                                        </span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="previewUrl = '{{ asset('storage/' . $layanan->$field) }}'; previewModalOpen = true" class="w-8 h-8 rounded-full flex items-center justify-center {{ $isNewlyRevised ? 'hover:bg-emerald-100 text-emerald-700' : 'hover:bg-primary/10 text-primary' }} transition-colors focus:outline-none" title="Lihat Preview">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>
                                    <a href="{{ asset('storage/' . $layanan->$field) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center {{ $isNewlyRevised ? 'hover:bg-emerald-100 text-emerald-700' : 'hover:bg-primary/10 text-primary' }} transition-colors focus:outline-none" title="Buka / Download di Tab Baru">
                                        <span class="material-symbols-outlined text-[20px]">open_in_new</span>
                                    </a>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Kolom Aksi Verifikasi -->
        <div class="w-full lg:w-1/3 flex flex-col gap-6">
            <!-- Box Quick Action: Surat Rekomendasi Kesbangpol (PDF Resmi + QR Code) -->
            <div class="bg-blue-50/80 rounded-xl shadow-sm border border-blue-200 p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">verified</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Surat Rekomendasi Kesbangpol</h3>
                        <p class="text-[11px] text-gray-600">Dokumen resmi PDF ber-QR Code</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mb-3.5 leading-relaxed">
                    Lihat atau unduh Surat Rekomendasi Kesbangpol resmi berformat PDF lengkap dengan tanda tangan elektronik dan QR Code verifikasi.
                </p>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('surat.pdf', $layanan->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg shadow-sm text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        Lihat / Download Surat (.pdf)
                    </a>
                    <a href="{{ route('kesbangpol.layanan.generate_docx', $layanan->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg border border-blue-300 text-xs font-semibold text-blue-700 bg-white hover:bg-blue-50 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">description</span>
                        Unduh Draf Word (.docx)
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-bold text-gray-800 m-0">Aksi Verifikasi</h2>
                </div>
                <div class="p-6">
                    @if($layanan->statusMaster && in_array($layanan->statusMaster->kode, ['menunggu_verifikasi', 'perlu_revisi']))
                        <form action="{{ route('kesbangpol.layanan.verify', $layanan->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keputusan</label>
                                <select name="status" id="status" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 shadow-sm text-sm" required onchange="toggleRevisiField()">
                                    <option value="">-- Pilih --</option>
                                    <option value="disetujui">Setujui Layanan</option>
                                    <option value="perlu_revisi">Revisi Layanan</option>
                                    <option value="ditolak">Tolak Layanan</option>
                                </select>
                            </div>
                            
                            <div id="keterangan_field" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan / Catatan Revisi / Alasan Tolak</label>
                                <textarea name="keterangan" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 shadow-sm text-sm" rows="3"></textarea>
                            </div>

                            <div id="surat_field" class="hidden flex flex-col gap-3">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Dinas Tujuan Layanan <span class="text-red-500">*</span></label>
                                    <select name="dinas_id" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 shadow-sm text-sm">
                                        <option value="">-- Pilih Dinas Tujuan --</option>
                                        @foreach($allDinas ?? [] as $d)
                                            <option value="{{ $d->id }}" {{ (str_contains(strtolower($layanan->tempat_kegiatan), strtolower($d->name)) || str_contains(strtolower($d->name), strtolower($layanan->tempat_kegiatan))) ? 'selected' : '' }}>
                                                {{ $d->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-xs text-gray-500">Data akan otomatis diteruskan ke Dinas yang dipilih untuk verifikasi penempatan bidang.</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Upload Surat Rekomendasi Kesbangpol (Opsional)</label>
                                    <input type="file" name="file_surat_keluaran" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-container file:text-primary hover:file:bg-primary hover:file:text-white transition-colors cursor-pointer" accept=".pdf">
                                    <div class="mt-2 text-xs text-blue-800 bg-blue-50/80 p-2.5 rounded-lg border border-blue-200/80 flex items-start gap-2">
                                        <span class="material-symbols-outlined text-blue-600 text-[16px] mt-0.5 shrink-0">info</span>
                                        <span>Jika tidak diunggah manual, sistem akan <strong>otomatis menerbitkan Surat Rekomendasi resmi (.pdf) ber-QR Code</strong> saat menyetujui.</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors mt-2">
                                Simpan Keputusan
                            </button>
                        </form>
                    @else
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <span class="material-symbols-outlined text-blue-400">info</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        Layanan ini sudah diproses dan berstatus <strong class="font-bold">{{ $layanan->statusMaster->nama ?? 'Unknown' }}</strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        @if($layanan->file_surat_keluaran || ($layanan->statusMaster && in_array($layanan->statusMaster->kode, ['disetujui', 'selesai'])))
                        <a href="{{ route('surat.pdf', $layanan->id) }}" target="_blank" class="w-full flex items-center justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors mt-4">
                            <span class="material-symbols-outlined mr-2 text-[18px]">picture_as_pdf</span>
                            Download Surat Rekomendasi (PDF + QR Code)
                        </a>
                        @endif
                    @endif
                    
                    <a href="{{ route('kesbangpol.layanan.index') }}" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors mt-3">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="previewModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
        <div x-show="previewModalOpen" x-transition.opacity class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="previewModalOpen = false"></div>
        <div x-show="previewModalOpen" 
             x-transition.scale.origin.bottom 
             class="relative w-full max-w-5xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col"
             style="height: 90vh;">
            
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gray-50 shrink-0">
                <div class="flex items-center gap-3">
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
            
            <!-- Content -->
            <div class="flex-1 overflow-hidden bg-gray-100 relative">
                <template x-if="previewUrl">
                    <iframe :src="previewUrl" class="w-full h-full border-0"></iframe>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRevisiField() {
    var status = document.getElementById('status').value;
    var ket = document.getElementById('keterangan_field');
    var surat = document.getElementById('surat_field');
    var textarea = document.querySelector('textarea[name="keterangan"]');
    
    if (status === 'perlu_revisi' || status === 'ditolak') {
        ket.classList.remove('hidden');
        if (textarea) textarea.required = true;
    } else {
        ket.classList.add('hidden');
        if (textarea) textarea.required = false;
    }

    if (status === 'disetujui') {
        surat.classList.remove('hidden');
    } else {
        surat.classList.add('hidden');
    }
}
</script>
@endsection
