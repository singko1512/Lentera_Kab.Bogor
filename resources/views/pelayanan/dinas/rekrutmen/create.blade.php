@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <a href="{{ route('dinas.rekrutmen.index') }}" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">arrow_back</span>
                </a>
                <h2 class="text-headline-lg font-headline-lg text-on-surface tracking-tight">Buka Lowongan Baru</h2>
            </div>
            <p class="text-body-md font-body-md text-on-surface-variant ml-8">Formulir untuk mengajukan pembukaan kuota layanan / magang baru ke publik.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="mb-6 bg-error/10 text-error p-4 rounded-xl border border-error/20 flex gap-2">
        <span class="material-symbols-outlined shrink-0 mt-0.5">error</span>
        <ul class="list-disc pl-5 text-sm font-semibold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft border border-outline-variant/30 overflow-hidden">
        <div class="p-6 md:p-8">
            <form action="{{ route('dinas.rekrutmen.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-label-md font-bold text-on-surface block">Penempatan Bidang / Unit Kerja</label>
                    <div class="relative">
                        <select name="bidang_id" required class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary appearance-none pr-10">
                            <option value="" disabled selected>Pilih Bidang / Unit Kerja</option>
                            @foreach($bidangs as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-on-surface-variant">
                            <span class="material-symbols-outlined text-[20px]">expand_more</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-label-md font-bold text-on-surface block">Judul Lowongan / Posisi</label>
                    <input type="text" name="judul" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Contoh: Magang IT Support (Diskominfo)" required maxlength="150">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-label-md font-bold text-on-surface block">Kuota Dibutuhkan</label>
                        <input type="number" name="kuota" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required min="1" placeholder="Masukkan jumlah kuota">
                    </div>
                    <div class="space-y-2">
                        <label class="text-label-md font-bold text-on-surface block">Tanggal Penutupan (Opsional)</label>
                        <input type="date" name="tanggal_berakhir" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <p class="text-xs text-on-surface-variant mt-1">Kosongkan jika lowongan terus dibuka sampai kuota habis.</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-label-md font-bold text-on-surface block">Deskripsi / Persyaratan Khusus</label>
                    <textarea name="deskripsi_persyaratan" rows="4" class="w-full bg-surface-container-lowest border border-outline-variant rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Masukkan persyaratan khusus untuk magang di posisi ini (misalnya keahlian yang dibutuhkan)..."></textarea>
                </div>

                <div class="space-y-3">
                    <label class="text-label-md font-bold text-on-surface block">Status Publikasi</label>
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="is_active" value="1" checked class="w-5 h-5 text-primary bg-surface-container-lowest border-outline-variant focus:ring-primary focus:ring-offset-0">
                            <span class="text-body-md text-on-surface group-hover:text-primary transition-colors">Aktif / Dibuka</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="radio" name="is_active" value="0" class="w-5 h-5 text-primary bg-surface-container-lowest border-outline-variant focus:ring-primary focus:ring-offset-0">
                            <span class="text-body-md text-on-surface group-hover:text-primary transition-colors">Draft / Ditutup</span>
                        </label>
                    </div>
                </div>

                <div class="pt-6 border-t border-outline-variant/30 flex justify-end gap-3">
                    <a href="{{ route('dinas.rekrutmen.index') }}" class="px-6 py-3 bg-surface-container text-on-surface-variant font-bold rounded-xl hover:bg-surface-container-high transition-all">Batal</a>
                    <button type="submit" class="px-6 py-3 bg-primary text-white font-bold rounded-xl hover:bg-primary/90 hover:shadow-lg transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        Simpan Lowongan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
