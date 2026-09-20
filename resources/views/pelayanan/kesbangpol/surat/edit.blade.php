@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('admin.surat.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                </a>
                <span class="text-sm font-bold text-on-surface-variant uppercase tracking-wider">Kembali ke Daftar</span>
            </div>
            <h1 class="text-headline-md font-headline-md font-bold text-on-surface tracking-tight">Edit Kop Surat: {{ $surat->name }}</h1>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/50 overflow-hidden">
        <form action="{{ route('admin.surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-8">
            @csrf
            @method('PUT')

            <!-- SECTION: DATA KOP SURAT -->
            <div>
                <h2 class="text-title-md font-bold text-primary mb-4 border-b border-primary/20 pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">receipt_long</span> Informasi Kop Surat
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2 md:col-span-2">
                        <label for="alamat" class="block text-sm font-bold text-on-surface">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" rows="2" class="w-full rounded-xl border-outline-variant bg-surface px-4 py-2.5 text-on-surface focus:ring-primary focus:border-primary transition-all">{{ old('alamat', $surat->alamat) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label for="telepon" class="block text-sm font-bold text-on-surface">Nomor Telepon</label>
                        <input type="text" name="telepon" id="telepon" value="{{ old('telepon', $surat->telepon) }}" class="w-full rounded-xl border-outline-variant bg-surface px-4 py-2.5 text-on-surface focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-bold text-on-surface">Email Instansi</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $surat->email) }}" class="w-full rounded-xl border-outline-variant bg-surface px-4 py-2.5 text-on-surface focus:ring-primary focus:border-primary transition-all">
                    </div>

                    <div class="space-y-2 md:col-span-2">
                        <label for="kop_surat" class="block text-sm font-bold text-on-surface">Logo Kop Surat (Gambar JPG/PNG)</label>
                        @if($surat->kop_surat)
                            <div class="mb-4">
                                <p class="text-xs text-on-surface-variant mb-2">Logo Saat Ini:</p>
                                <img src="{{ asset($surat->kop_surat) }}" alt="Kop Surat" class="max-w-full md:max-w-[150px] border border-outline-variant/50 rounded p-2 bg-white">
                            </div>
                        @endif
                        <input type="file" name="kop_surat" id="kop_surat" accept="image/jpeg,image/png" class="w-full text-sm text-on-surface-variant file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 transition-all cursor-pointer">
                        <p class="text-xs text-on-surface-variant mt-1">Biarkan kosong jika tidak ingin mengubah logo saat ini.</p>
                    </div>
                </div>
            </div>

            <!-- SECTION: PENANDATANGAN SURAT (ISI SURAT) -->
            <div>
                <h2 class="text-title-md font-bold text-primary mb-4 border-b border-primary/20 pb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined">edit_document</span> Isi Surat / Penandatangan
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kepala Dinas Name -->
                    <div class="space-y-2">
                        <label for="nama_kepala" class="block text-sm font-bold text-on-surface">Nama Kepala Instansi/Dinas</label>
                        <input type="text" name="nama_kepala" id="nama_kepala" value="{{ old('nama_kepala', $surat->nama_kepala) }}" class="w-full rounded-xl border-outline-variant bg-surface px-4 py-2.5 text-on-surface focus:ring-primary focus:border-primary transition-all" placeholder="Masukkan nama kepala dinas">
                    </div>

                    <!-- Kepala Dinas NIP -->
                    <div class="space-y-2">
                        <label for="nip_kepala" class="block text-sm font-bold text-on-surface">NIP Kepala Instansi/Dinas</label>
                        <input type="text" name="nip_kepala" id="nip_kepala" value="{{ old('nip_kepala', $surat->nip_kepala) }}" class="w-full rounded-xl border-outline-variant bg-surface px-4 py-2.5 text-on-surface focus:ring-primary focus:border-primary transition-all" placeholder="Masukkan NIP">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/30 flex justify-end gap-3">
                <a href="{{ route('admin.surat.index') }}" class="px-6 py-2.5 border border-outline-variant text-on-surface-variant hover:bg-surface-container-low rounded-xl font-bold text-sm transition-colors">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white hover:bg-secondary rounded-xl font-bold text-sm shadow-md hover:shadow-lg transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
