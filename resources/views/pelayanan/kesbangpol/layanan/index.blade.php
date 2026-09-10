@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Daftar Permohonan Layanan (Kesbangpol)</h2>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse border border-gray-200 rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">ID</th>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">Tanggal</th>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">Nama Lengkap</th>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">Jenis Layanan</th>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">Status</th>
                    <th class="p-4 border-b border-gray-200 font-semibold text-gray-700 text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($layanans as $layanan)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="p-4 border-b border-gray-100 text-sm font-medium text-gray-900">#{{ $layanan->id }}</td>
                    <td class="p-4 border-b border-gray-100 text-sm text-gray-600">{{ $layanan->created_at->format('d/m/Y H:i') }}</td>
                    <td class="p-4 border-b border-gray-100 text-sm text-gray-800 font-medium">{{ $layanan->atas_nama }}</td>
                    <td class="p-4 border-b border-gray-100 text-sm text-gray-600 capitalize">{{ $layanan->jenisLayanan->nama ?? '-' }}</td>
                    <td class="p-4 border-b border-gray-100 text-sm">
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
                        <div class="flex flex-col items-start gap-1">
                            <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full whitespace-nowrap {{ $badgeClass }}">
                                {{ $layanan->statusMaster->nama ?? 'Unknown' }}
                            </span>
                            @if($layanan->isRevisiSelesai())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-bold rounded-md bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-xs">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                                    Revisi Baru Masuk
                                </span>
                            @elseif($layanan->isMenungguRevisiUser())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[11px] font-semibold rounded-md bg-amber-50 text-amber-700 border border-amber-200">
                                    ⏳ Menunggu Pemohon
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="p-4 border-b border-gray-100 text-sm">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('kesbangpol.layanan.show', $layanan->id) }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                <span class="material-symbols-outlined text-[18px] mr-1">search</span>
                                Periksa
                            </a>
                            <a href="{{ route('kesbangpol.layanan.generate_docx', $layanan->id) }}" target="_blank" title="Generate & Download Surat Rekomendasi (.docx)" class="inline-flex items-center justify-center p-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors border border-blue-200 shadow-sm">
                                <span class="material-symbols-outlined text-[18px]">description</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-gray-500 text-sm border-b border-gray-100">
                        <div class="flex flex-col items-center justify-center">
                            <span class="material-symbols-outlined text-4xl mb-2 text-gray-300">inbox</span>
                            <p>Belum ada permohonan layanan.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $layanans->links() }}
    </div>
</div>
@endsection
