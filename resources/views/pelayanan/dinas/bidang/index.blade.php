@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg flex justify-between items-center">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Kelola Bidang</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">Kelola daftar bidang atau unit kerja di instansi Anda.</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 flex items-center gap-2">
            <span class="material-symbols-outlined">add</span> Tambah Bidang
        </button>
    </div>

    @if(session('success_swal'))
    <div class="bg-[#10B981]/10 border border-[#10B981]/20 text-[#10B981] px-4 py-3 rounded-lg relative mb-4">
        <span class="block sm:inline font-medium">{{ session('success_swal') }}</span>
    </div>
    @endif

    <!-- Data Table Card -->
    <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider w-16">No</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Bidang / Unit Kerja</th>
                        <th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-right w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @forelse($bidangs as $index => $bidang)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 pl-6 text-on-surface-variant">{{ $index + 1 }}</td>
                        <td class="p-4">
                            <span class="text-body-md font-body-md text-on-surface font-semibold">{{ $bidang->name }}</span>
                        </td>
                        <td class="p-4 pr-6 text-right">
                            <div class="flex justify-end gap-2">
                                <button onclick="openEditModal({{ $bidang->id }}, '{{ addslashes($bidang->name) }}')" class="p-2 text-secondary hover:bg-secondary/10 rounded-lg transition-colors inline-flex items-center" title="Edit">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <div x-data="{ showModal: false }" class="inline-block">
                                    <button @click="showModal = true" type="button" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors inline-flex items-center" title="Hapus">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50" x-cloak>
                                        <div @click.away="showModal = false" x-transition.scale.origin.bottom class="bg-white rounded-xl p-6 w-80 shadow-2xl text-center relative border-t-4 border-red-500">
                                            <div class="mb-4 text-red-500">
                                                <span class="material-symbols-outlined text-[48px]">warning</span>
                                            </div>
                                            <h3 class="text-red-600 font-bold mb-6 text-lg">Apakah anda yakin untuk menghapus data ini?</h3>
                                            <div class="flex justify-center gap-3">
                                                <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">Batal</button>
                                                <form action="{{ route('dinas.bidang.destroy', $bidang->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors">Ya, Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-8 text-center text-on-surface-variant">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[48px] text-outline-variant">domain_disabled</span>
                                <p>Belum ada data bidang. Silakan tambahkan bidang baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div id="addModal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-scale-in">
        <div class="px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="text-title-lg font-title-lg text-on-surface font-semibold">Tambah Bidang Baru</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-on-surface-variant hover:bg-surface-container p-2 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('dinas.bidang.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5">Nama Bidang / Unit Kerja</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none bg-surface" placeholder="Contoh: Bidang Bina Marga">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant/30 flex justify-end gap-3 bg-surface-container-lowest">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-5 py-2 rounded-lg text-primary font-medium hover:bg-primary/10 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg font-medium shadow-sm hover:bg-primary/90 transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="fixed inset-0 z-[100] hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-scale-in">
        <div class="px-6 py-4 border-b border-outline-variant/30 flex justify-between items-center bg-surface-container-lowest">
            <h3 class="text-title-lg font-title-lg text-on-surface font-semibold">Edit Bidang</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-on-surface-variant hover:bg-surface-container p-2 rounded-full transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-1.5">Nama Bidang / Unit Kerja</label>
                    <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 rounded-lg border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all outline-none bg-surface">
                </div>
            </div>
            <div class="px-6 py-4 border-t border-outline-variant/30 flex justify-end gap-3 bg-surface-container-lowest">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-5 py-2 rounded-lg text-primary font-medium hover:bg-primary/10 transition-colors">Batal</button>
                <button type="submit" class="px-5 py-2 bg-primary text-white rounded-lg font-medium shadow-sm hover:bg-primary/90 transition-colors">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, name) {
        document.getElementById('editName').value = name;
        document.getElementById('editForm').action = '/dinas/bidang/' + id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>

<style>
    .animate-scale-in {
        animation: scaleIn 0.2s ease-out forwards;
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
</style>
@endsection
