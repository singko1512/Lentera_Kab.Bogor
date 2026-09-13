@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-stack-lg pb-12">
    <!-- Page Header -->
    <div class="mb-stack-lg flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface mb-2 tracking-tight">Daftar Rekrutmen / Lowongan</h2>
            <p class="text-body-md font-body-md text-on-surface-variant">Kelola daftar lowongan magang yang dibuka untuk peserta.</p>
        </div>
        <a href="{{ route('dinas.rekrutmen.create') }}" class="bg-primary text-white px-5 py-2.5 rounded-full text-label-md font-label-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined text-[18px]">add</span>
            Buka Lowongan Baru
        </a>
    </div>

    @if(session('success_swal'))
    <div class="p-4 rounded-xl bg-[#10B981]/10 text-[#10B981] font-semibold flex items-center gap-2 border border-[#10B981]/20 mb-6">
        <span class="material-symbols-outlined text-[20px]">check_circle</span>
        <span>{{ session('success_swal') }}</span>
    </div>
    @endif

    <div class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama (Bidang/Unit Kerja)</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Deskripsi</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-center">Kuota</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-center">Status</th>
                        <th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30 text-body-md font-body-md text-on-surface">
                    @forelse($rekrutmen as $r)
                    <tr class="hover:bg-primary/5 transition-colors group">
                        <td class="p-4 pl-6 font-semibold">
                            {{ $r->bidang->name ?? $r->judul }}
                        </td>
                        <td class="p-4 text-on-surface-variant max-w-xs truncate" title="{{ $r->deskripsi_persyaratan }}">
                            {{ $r->deskripsi_persyaratan ?? '-' }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[32px] h-8 bg-primary/10 text-primary font-bold rounded-full text-sm">
                                {{ $r->kuota }}
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            @if($r->is_active)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                                Aktif / Dibuka
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-error/10 text-error border border-error/20">
                                Ditutup
                            </span>
                            @endif
                        </td>
                        <td class="p-4 pr-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('dinas.rekrutmen.edit', $r->id) }}" class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <div x-data="{ showModal: false }" class="inline-block">
                                    <button @click="showModal = true" type="button" class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-error hover:bg-error hover:text-white transition-colors" title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div x-show="showModal" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
                                        <div @click.away="showModal = false" x-transition.scale.origin.bottom class="bg-white rounded-2xl p-6 w-80 shadow-2xl text-center relative border-t-4 border-error">
                                            <div class="mb-4 text-error">
                                                <span class="material-symbols-outlined text-[48px]">warning</span>
                                            </div>
                                            <h3 class="text-title-lg font-bold mb-6 text-on-surface">Apakah anda yakin untuk menghapus data ini?</h3>
                                            <div class="flex justify-center gap-3">
                                                <button @click="showModal = false" type="button" class="px-5 py-2.5 bg-surface-container text-on-surface-variant rounded-xl font-bold hover:bg-surface-container-high transition-colors">Batal</button>
                                                <form action="{{ route('dinas.rekrutmen.destroy', $r->id) }}" method="POST" class="inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-5 py-2.5 bg-error text-white rounded-xl font-bold hover:bg-error/90 transition-colors">Ya, Hapus</button>
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
                        <td colspan="5" class="py-6 px-4 text-center text-on-surface-variant">Belum ada lowongan rekrutmen yang dibuat.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
