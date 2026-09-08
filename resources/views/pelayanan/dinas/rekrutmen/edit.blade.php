@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Edit Lowongan Rekrutmen</h2>
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

    <form action="{{ route('dinas.rekrutmen.update', $rekrutmen->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Penempatan Bidang / Unit Kerja (Opsional)</label>
            <select name="bidang_id" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                <option value="">Semua Bidang / Tidak Spesifik</option>
                @foreach($bidangs as $bidang)
                <option value="{{ $bidang->id }}" {{ $rekrutmen->bidang_id == $bidang->id ? 'selected' : '' }}>{{ $bidang->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Judul Lowongan / Posisi</label>
            <input type="text" name="judul" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" value="{{ $rekrutmen->judul }}" required maxlength="150">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Kuota Dibutuhkan</label>
                <input type="number" name="kuota" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" value="{{ $rekrutmen->kuota }}" required min="1">
            </div>
            <div>
                <label class="block text-gray-700 font-medium mb-2">Tanggal Penutupan (Opsional)</label>
                <input type="date" name="tanggal_berakhir" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary" value="{{ $rekrutmen->tanggal_berakhir ? $rekrutmen->tanggal_berakhir->format('Y-m-d') : '' }}">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-2">Persyaratan Khusus (Opsional)</label>
            <textarea name="deskripsi_persyaratan" rows="4" class="w-full border border-gray-300 rounded px-4 py-2 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">{{ $rekrutmen->deskripsi_persyaratan }}</textarea>
        </div>

        <div class="mb-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ $rekrutmen->is_active ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-primary border-gray-300 rounded">
                <span class="ml-2 text-gray-700 font-medium">Aktif / Dibuka</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('dinas.rekrutmen.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded font-medium hover:bg-gray-300">Batal</a>
            <button type="submit" class="px-4 py-2 bg-primary text-white rounded font-medium hover:bg-primary-dark">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
