@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Dashboard Dinas: {{ $dinas->name ?? $dinas->nama }}</h2>
            <p class="text-sm text-gray-500">Kelola kuota, status penerimaan, dan pengajuan magang.</p>
        </div>
        
        <!-- Status Magang Live Badge & Quick Control -->
        @php $badge = $dinas->status_badge; @endphp
        <div class="flex items-center gap-3 bg-surface-container-low p-3 rounded-xl border border-outline-variant/30">
            <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Saat Ini:</div>
            <div class="flex items-center gap-1.5 px-3 py-1.5 {{ $badge['bg_class'] }} rounded-lg text-xs font-bold shadow-xs">
                <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
                <span>{{ $badge['label'] }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 flex items-center gap-2" role="alert">
            <span class="material-symbols-outlined text-[18px]">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Setting Status Magang Instansi -->
    <div class="bg-gradient-to-r from-blue-50/70 via-indigo-50/50 to-white rounded-xl p-5 border border-blue-100 mb-8">
        <form action="{{ route('dinas.status_magang.update') }}" method="POST" class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            @csrf
            <div class="flex flex-col gap-1">
                <label for="status_magang" class="text-sm font-bold text-gray-800 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[20px]">tune</span>
                    <span>Pengaturan Status Ketersediaan Magang Instansi</span>
                </label>
                <p class="text-xs text-gray-500">Pilih 'Otomatis' agar status disesuaikan dengan kuota lowongan aktif, atau tentukan status secara manual.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select name="status_magang" id="status_magang" onchange="this.form.submit()" class="bg-white text-gray-800 text-sm font-semibold rounded-lg border border-gray-300 focus:ring-secondary focus:border-secondary px-4 py-2.5 shadow-sm cursor-pointer w-full md:w-56">
                    <option value="otomatis" {{ ($dinas->status_magang ?? 'otomatis') == 'otomatis' ? 'selected' : '' }}>🔄 Otomatis (Cek Kuota)</option>
                    <option value="tersedia" {{ ($dinas->status_magang ?? '') == 'tersedia' ? 'selected' : '' }}>🟢 KUOTA TERSEDIA</option>
                    <option value="penuh" {{ ($dinas->status_magang ?? '') == 'penuh' ? 'selected' : '' }}>🔴 KUOTA PENUH</option>
                    <option value="tidak_tersedia" {{ ($dinas->status_magang ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>⚪ TIDAK TERSEDIA</option>
                </select>
                <button type="submit" class="px-4 py-2.5 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary/90 transition-all shadow-sm shrink-0 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    <span>Simpan</span>
                </button>
            </div>
        </form>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <a href="{{ route('dinas.applications.index') }}" class="p-4 bg-purple-50 rounded-lg border border-purple-100 hover:bg-purple-100/70 transition-colors block cursor-pointer">
            <h3 class="text-purple-800 font-semibold mb-1 flex items-center justify-between">
                <span>Pengajuan Masuk</span>
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </h3>
            <p class="text-3xl font-bold text-purple-900">{{ $totalPengajuanLayanan }}</p>
            <p class="text-xs text-purple-700 mt-1">Verifikasi & ACC</p>
        </a>
        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
            <h3 class="text-blue-800 font-semibold mb-1">Total Kuota</h3>
            <p class="text-3xl font-bold text-blue-900">{{ $totalKuota }}</p>
        </div>
        <div class="p-4 bg-green-50 rounded-lg border border-green-100">
            <h3 class="text-green-800 font-semibold mb-1">Sisa Slot</h3>
            <p class="text-3xl font-bold text-green-900">{{ $slotTersedia }}</p>
        </div>
        <div class="p-4 bg-yellow-50 rounded-lg border border-yellow-100">
            <h3 class="text-yellow-800 font-semibold mb-1">Peserta Aktif</h3>
            <p class="text-3xl font-bold text-yellow-900">{{ $pesertaAktif }}</p>
        </div>
        <a href="{{ route('absensi.admin.dashboard', ['tab' => 'sertifikat']) }}" class="p-4 bg-amber-50 rounded-lg border border-amber-200 hover:bg-amber-100/70 transition-colors block cursor-pointer">
            <h3 class="text-amber-800 font-semibold mb-1 flex items-center justify-between">
                <span>Kelola Sertifikat</span>
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span>
            </h3>
            <p class="text-xs font-semibold text-amber-900 mt-2">Cetak & Draf Sertifikat</p>
            <p class="text-xs text-amber-700 mt-1">Menu Sertifikat Magang &rarr;</p>
        </a>
    </div>

    <!-- Daftar Peserta Diterima -->
    <div>
        <h3 class="font-bold text-lg mb-4">Daftar Peserta Resmi Magang</h3>
        <table class="w-full text-left border-collapse border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">Nama Peserta</th>
                    <th class="p-3 border">Email</th>
                    <th class="p-3 border">Penempatan Bidang</th>
                    <th class="p-3 border">Status Magang</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesertaDiterima as $peserta)
                <tr>
                    <td class="p-3 border">{{ $peserta->user->nama ?? ($peserta->user->name ?? '-') }}</td>
                    <td class="p-3 border">{{ $peserta->user->email ?? '-' }}</td>
                    <td class="p-3 border text-gray-700 font-semibold">{{ $peserta->bidang->name ?? (optional(optional($peserta->rekrutmen)->bidang)->name ?? 'Belum Ditempatkan') }}</td>
                    <td class="p-3 border text-green-600 font-medium capitalize">{{ $peserta->status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
