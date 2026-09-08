@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Buka Lowongan Rekrutmen Baru</h2>
        <p class="text-gray-600">Formulir untuk mengajukan pembukaan kuota layanan / magang baru ke publik.</p>
    </div>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('dinas.rekrutmen.store') }}" method="POST">
        @csrf
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Penempatan Bidang / Unit Kerja (Opsional)</label>
            <select name="bidang_id" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <option value="">Semua Bidang / Tidak Spesifik</option>
                @foreach($bidangs as $bidang)
                <option value="{{ $bidang->id }}">{{ $bidang->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Lowongan / Posisi</label>
            <input type="text" name="judul" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Contoh: Magang IT Support (Diskominfo)" required maxlength="150">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Kuota Dibutuhkan</label>
                <input type="number" name="kuota" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" required min="1">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Tanggal Penutupan (Opsional)</label>
                <input type="date" name="tanggal_berakhir" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <small class="text-gray-500">Kosongkan jika lowongan terus dibuka sampai kuota habis.</small>
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Persyaratan Khusus (Opsional)</label>
            <textarea name="deskripsi_persyaratan" rows="4" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Contoh: Harus menguasai jaringan dasar, diutamakan dari jurusan Teknik Informatika..."></textarea>
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" checked class="form-checkbox h-5 w-5 text-primary border-gray-300 rounded">
                <span class="ml-2 text-gray-700 font-medium">Langsung Aktifkan Lowongan</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dinas.rekrutmen.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-medium hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded font-medium hover:bg-primary-dark">Simpan Rekrutmen</button>
        </div>
    </form>
</div>
@endsection
