@extends('pelayanan.layouts.kesbangpol_stitch')
@section('content')

<!-- Breadcrumb & Back Action -->
<div class="flex flex-col gap-stack-sm mb-stack-lg">
<div class="flex items-center gap-2 text-label-md font-label-md text-on-surface-variant">
<a class="hover:text-primary transition-colors" href="#">Dashboard</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">Riwayat Pengajuan</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-on-surface font-semibold">Detail Pengajuan</span>
</div>
<a class="inline-flex items-center gap-2 text-primary hover:text-primary-container font-label-md w-fit mt-2" href="javascript:history.back()">
<span class="material-symbols-outlined">arrow_back</span>
                Kembali
            </a>
</div>
<!-- Header Summary Card -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6 mb-stack-lg border-t-4 border-green-600">
<div class="flex justify-between items-start">
<div>
<h2 class="text-headline-md font-headline-md text-on-surface mb-1">ARJUNA OLANDA PUTRA</h2>
<p class="text-body-md font-body-md text-on-surface-variant mb-4">Universitas Pakuan Bogor</p>
<div class="flex gap-6 text-label-md font-label-md text-on-surface-variant">
<div>
<span class="block text-caption font-caption text-outline">Tanggal Pengajuan</span>
<span class="font-medium text-on-surface">12 Oktober 2023</span>
</div>
<div>
<span class="block text-caption font-caption text-outline">Tanggal Diproses</span>
<span class="font-medium text-on-surface">15 Oktober 2023</span>
</div>
</div>
</div>
<div class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-label-md font-label-md font-bold uppercase tracking-wider flex items-center gap-1">
<span class="material-symbols-outlined text-[18px]">check_circle</span>
                    Diterima
                </div>
</div>
</div>
<!-- Bento Grid Layout for Details -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-stack-md mb-stack-lg">
<!-- Column 1: Data Peserta & Informasi Magang -->
<div class="lg:col-span-2 flex flex-col gap-stack-md">
<!-- Data Peserta -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="text-title-lg font-title-lg text-primary mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-2">
<span class="material-symbols-outlined">person</span> Data Peserta
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md">
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Nama Lengkap</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Arjuna Olanda Putra</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Institusi Pendidikan</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Universitas Pakuan Bogor</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Program Studi / Jurusan</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Ilmu Administrasi Negara</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Nomor Telepon / WhatsApp</span>
<span class="text-body-md font-body-md text-on-surface font-medium">0812-3456-7890</span>
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Alamat Email</span>
<span class="text-body-md font-body-md text-on-surface font-medium">arjuna.op@student.unpak.ac.id</span>
</div>
</div>
</div>
<!-- Informasi Magang -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="text-title-lg font-title-lg text-primary mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-2">
<span class="material-symbols-outlined">work</span> Informasi Magang
                    </h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-stack-md">
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Jenis Layanan</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Magang / Praktik Kerja</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Instansi Tujuan</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Badan Kesatuan Bangsa dan Politik</span>
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Bidang / Divisi (Preferensi)</span>
<span class="text-body-md font-body-md text-on-surface font-medium">Bidang Kewaspadaan Nasional</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Tanggal Mulai</span>
<span class="text-body-md font-body-md text-on-surface font-medium">01 November 2023</span>
</div>
<div class="flex flex-col gap-stack-sm">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Tanggal Selesai</span>
<span class="text-body-md font-body-md text-on-surface font-medium">31 Januari 2024</span>
</div>
<div class="flex flex-col gap-stack-sm md:col-span-2">
<span class="text-caption font-caption text-on-surface-variant uppercase tracking-wider">Durasi</span>
<span class="text-body-md font-body-md text-on-surface font-medium">3 Bulan</span>
</div>
</div>
</div>
<!-- Dokumen Pengajuan -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="text-title-lg font-title-lg text-primary mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-2">
<span class="material-symbols-outlined">folder</span> Dokumen Pengajuan
                    </h3>
<div class="flex flex-col gap-3">
<!-- Doc Item -->
<div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">description</span>
<div>
<p class="text-label-md font-label-md text-on-surface">Surat Pengantar Institusi</p>
<p class="text-caption font-caption text-green-600">Terverifikasi</p>
</div>
</div>
<button class="text-primary hover:text-primary-container text-label-md font-label-md font-semibold flex items-center gap-1">
<span class="material-symbols-outlined text-[18px]">visibility</span> Lihat
                            </button>
</div>
<!-- Doc Item -->
<div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">badge</span>
<div>
<p class="text-label-md font-label-md text-on-surface">Kartu Tanda Penduduk (KTP)</p>
<p class="text-caption font-caption text-green-600">Terverifikasi</p>
</div>
</div>
<button class="text-primary hover:text-primary-container text-label-md font-label-md font-semibold flex items-center gap-1">
<span class="material-symbols-outlined text-[18px]">visibility</span> Lihat
                            </button>
</div>
<!-- Doc Item -->
<div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">id_card</span>
<div>
<p class="text-label-md font-label-md text-on-surface">Kartu Tanda Mahasiswa (KTM)</p>
<p class="text-caption font-caption text-green-600">Terverifikasi</p>
</div>
</div>
<button class="text-primary hover:text-primary-container text-label-md font-label-md font-semibold flex items-center gap-1">
<span class="material-symbols-outlined text-[18px]">visibility</span> Lihat
                            </button>
</div>
<!-- Doc Item -->
<div class="flex items-center justify-between p-3 border border-outline-variant/50 rounded-lg hover:bg-surface-container-low transition-colors">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary">article</span>
<div>
<p class="text-label-md font-label-md text-on-surface">Proposal Magang</p>
<p class="text-caption font-caption text-green-600">Terverifikasi</p>
</div>
</div>
<button class="text-primary hover:text-primary-container text-label-md font-label-md font-semibold flex items-center gap-1">
<span class="material-symbols-outlined text-[18px]">visibility</span> Lihat
                            </button>
</div>
</div>
</div>
</div>
<!-- Column 2: Timeline & Notes -->
<div class="flex flex-col gap-stack-md">
<!-- Riwayat Proses -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="text-title-lg font-title-lg text-primary mb-6 flex items-center gap-2 border-b border-outline-variant/30 pb-2">
<span class="material-symbols-outlined">timeline</span> Riwayat Proses
                    </h3>
<div class="relative border-l-2 border-outline-variant/30 ml-3 space-y-6">
<!-- Timeline Item 1 -->
<div class="relative pl-6">
<div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-primary ring-4 ring-white"></div>
<p class="text-caption font-caption text-on-surface-variant">12 Okt 2023, 09:00</p>
<p class="text-body-md font-body-md text-on-surface font-semibold">Pengajuan Dibuat</p>
<p class="text-caption font-caption text-on-surface-variant mt-1">Sistem menerima pengajuan baru dari pemohon.</p>
</div>
<!-- Timeline Item 2 -->
<div class="relative pl-6">
<div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-primary ring-4 ring-white"></div>
<p class="text-caption font-caption text-on-surface-variant">13 Okt 2023, 10:30</p>
<p class="text-body-md font-body-md text-on-surface font-semibold">Dokumen Diverifikasi</p>
<p class="text-caption font-caption text-on-surface-variant mt-1">Admin telah memverifikasi kelengkapan dokumen.</p>
</div>
<!-- Timeline Item 3 -->
<div class="relative pl-6">
<div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-primary ring-4 ring-white"></div>
<p class="text-caption font-caption text-on-surface-variant">14 Okt 2023, 14:15</p>
<p class="text-body-md font-body-md text-on-surface font-semibold">Sedang Diproses</p>
<p class="text-caption font-caption text-on-surface-variant mt-1">Pengajuan diteruskan ke Bidang terkait untuk persetujuan.</p>
</div>
<!-- Timeline Item 4 (Final) -->
<div class="relative pl-6">
<div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-green-500 ring-4 ring-white"></div>
<p class="text-caption font-caption text-on-surface-variant">15 Okt 2023, 11:00</p>
<p class="text-body-md font-body-md text-green-700 font-semibold">DITERIMA</p>
<p class="text-caption font-caption text-on-surface-variant mt-1">Pengajuan magang disetujui. Surat balasan telah diterbitkan.</p>
</div>
</div>
</div>
<!-- Catatan Admin -->
<div class="bg-surface-container-lowest rounded-lg shadow-[0px_4px_20px_rgba(0,0,0,0.05)] p-6">
<h3 class="text-title-lg font-title-lg text-primary mb-4 flex items-center gap-2 border-b border-outline-variant/30 pb-2">
<span class="material-symbols-outlined">notes</span> Catatan Administratif
                    </h3>
<div class="bg-surface p-4 rounded-lg border border-outline-variant/30 text-body-md font-body-md text-on-surface-variant min-h-[120px]">
                        Dokumen lengkap dan sesuai dengan kuota yang tersedia di Bidang Kewaspadaan Nasional. Peserta diharapkan melapor ke TU Bidang pada hari pertama pelaksanaan magang (01 November 2023). Surat Balasan (Penerimaan) telah dicetak dan dikirimkan via email ke peserta.
                    </div>
</div>
</div>
</div>

@endsection
