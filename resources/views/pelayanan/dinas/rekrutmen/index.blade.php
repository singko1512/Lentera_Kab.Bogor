@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Rekrutmen / Lowongan</h2>
        <a href="{{ route('dinas.rekrutmen.create') }}" class="bg-primary text-white px-4 py-2 rounded font-medium hover:bg-primary-dark">
            <i class="fa-solid fa-plus mr-2"></i> Buka Lowongan Baru
        </a>
    </div>

    @if(session('success_swal'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success_swal') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Nama (Bidang/Unit Kerja)</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-600">Deskripsi</th>
                    <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-600">Kuota</th>
                    <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-600">Status</th>
                    <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekrutmen as $r)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b font-medium text-gray-800">
                        {{ $r->bidang->name ?? $r->judul }}
                    </td>
                    <td class="py-3 px-4 border-b text-gray-600 max-w-xs truncate" title="{{ $r->deskripsi_persyaratan }}">
                        {{ $r->deskripsi_persyaratan ?? '-' }}
                    </td>
                    <td class="py-3 px-4 border-b text-center">
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">{{ $r->kuota }}</span>
                    </td>
                    <td class="py-3 px-4 border-b text-center">
                        @if($r->is_active)
                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Aktif / Dibuka</span>
                        @else
                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded">Ditutup</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b text-center">
                        <a href="{{ route('dinas.rekrutmen.edit', $r->id) }}" class="text-blue-600 hover:text-blue-900 mx-1">
                            <i class="fa-solid fa-edit"></i> Edit
                        </a>
                        <div x-data="{ showModal: false }" class="inline-block">
                            <button @click="showModal = true" type="button" class="text-red-600 hover:text-red-900 mx-1">
                                <i class="fa-solid fa-trash"></i> Hapus
                            </button>

                            <!-- Delete Modal -->
                            <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                                <div @click.away="showModal = false" x-transition.scale.origin.bottom class="bg-white rounded-xl p-6 w-80 shadow-2xl text-center relative border-t-4 border-red-500">
                                    <div class="mb-4 text-red-500">
                                        <span class="material-symbols-outlined text-[48px]">warning</span>
                                    </div>
                                    <h3 class="text-red-600 font-bold mb-6 text-lg">Apakah anda yakin untuk menghapus data ini?</h3>
                                    <div class="flex justify-center gap-3">
                                        <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">Batal</button>
                                        <form action="{{ route('dinas.rekrutmen.destroy', $r->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 px-4 border-b text-center text-gray-500">Belum ada lowongan rekrutmen yang dibuat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
