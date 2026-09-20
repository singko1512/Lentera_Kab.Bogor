@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-headline-md font-headline-md font-bold text-on-surface tracking-tight">Kelola Surat & Kop</h1>
            <p class="text-body-md font-body-md text-on-surface-variant mt-1">Kelola Kop Surat dan Nama Kepala Dinas untuk masing-masing instansi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-2xl shadow-sm border border-outline-variant/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/50">
                        <th class="px-6 py-4 font-label-md text-xs text-on-surface-variant uppercase tracking-wider">Nama Instansi</th>
                        <th class="px-6 py-4 font-label-md text-xs text-on-surface-variant uppercase tracking-wider">Kop Surat</th>
                        <th class="px-6 py-4 font-label-md text-xs text-on-surface-variant uppercase tracking-wider">Kepala Dinas</th>
                        <th class="px-6 py-4 font-label-md text-xs text-on-surface-variant uppercase tracking-wider">NIP</th>
                        <th class="px-6 py-4 font-label-md text-xs text-on-surface-variant uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30">
                    @foreach($dinas as $d)
                    <tr class="hover:bg-surface-container-low/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-bold text-on-surface">{{ $d->name }}</span>
                            @if($d->is_kesbangpol)
                                <span class="ml-2 px-2 py-0.5 text-[10px] font-bold bg-primary/10 text-primary rounded-full uppercase">Kesbangpol</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($d->kop_surat)
                                <img src="{{ asset($d->kop_surat) }}" alt="Kop Surat" class="h-10 object-contain rounded border border-outline-variant/50">
                            @else
                                <span class="text-sm text-on-surface-variant italic">Belum diatur</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-on-surface">{{ $d->nama_kepala ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-on-surface">{{ $d->nip_kepala ?? '-' }}</td>

                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.surat.edit', $d->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary/20 rounded-lg text-sm font-semibold transition-colors">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                                Edit
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
