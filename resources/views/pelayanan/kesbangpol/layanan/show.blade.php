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
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $badgeClass }}">
                            {{ $layanan->statusMaster->nama ?? 'Unknown' }}
                        </span>
                    </div>
                </div>
                <div class="p-6">
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
                            <div class="font-semibold md:col-span-1">Keterangan (Revisi)</div>
                            <div class="md:col-span-2 text-red-600 font-medium">{{ $layanan->keterangan }}</div>
                        @endif
                    </div>

                    <hr class="my-6 border-gray-200">
                    
                    <h3 class="text-md font-bold text-gray-800 mb-4">Dokumen Lampiran</h3>
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
                            <div class="flex items-center justify-between p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center text-gray-700">
                                    <span class="material-symbols-outlined text-primary mr-3">{{ $info['icon'] }}</span>
                                    <span class="font-medium">{{ $info['label'] }}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <button type="button" @click="previewUrl = '{{ asset('storage/' . $layanan->$field) }}'; previewModalOpen = true" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-primary/10 text-primary transition-colors focus:outline-none" title="Lihat Preview">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>
                                    <a href="{{ asset('storage/' . $layanan->$field) }}" target="_blank" class="w-8 h-8 rounded-full flex items-center justify-center hover:bg-primary/10 text-primary transition-colors focus:outline-none" title="Buka / Download di Tab Baru">
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
            <!-- Box Quick Action: Generate Surat Rekomendasi (.docx) -->
            <div class="bg-blue-50/80 rounded-xl shadow-sm border border-blue-200 p-5">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 text-white flex items-center justify-center shadow-sm">
                        <span class="material-symbols-outlined text-[20px]">description</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900">Surat Rekomendasi Kesbangpol</h3>
                        <p class="text-[11px] text-gray-600">Generate draf dokumen resmi (.docx)</p>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mb-3.5 leading-relaxed">
                    Unduh draf Surat Rekomendasi Kesbangpol otomatis berbasis format Word (.docx) kapan saja.
                </p>
                <a href="{{ route('kesbangpol.layanan.generate_docx', $layanan->id) }}" target="_blank" class="w-full flex items-center justify-center gap-2 py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-xs font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Generate & Download Surat (.docx)
                </a>
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
                                    <option value="perlu_revisi">Minta Revisi</option>
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
                                        <span>Jika tidak diunggah manual, sistem akan <strong>otomatis membuat Surat Rekomendasi (.docx)</strong> saat menyetujui.</span>
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
                        
                        @if($layanan->file_surat_keluaran)
                        <a href="{{ asset('storage/' . $layanan->file_surat_keluaran) }}" target="_blank" class="w-full flex items-center justify-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors mt-4">
                            <span class="material-symbols-outlined mr-2 text-[18px]">download</span>
                            Download Surat Kesbangpol (Hasil Upload/PDF)
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
