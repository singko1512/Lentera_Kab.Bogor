@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-primary/10 text-primary rounded-full text-xs font-bold uppercase tracking-wider mb-1.5">
                <span class="material-symbols-outlined text-[16px]">rate_review</span>
                <span>Uji Coba &amp; Evaluasi Pengguna</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">
                Rekapitulasi Ulasan Aplikasi
            </h1>
            <p class="text-sm text-on-surface-variant mt-1 max-w-2xl">
                Hasil evaluasi kepuasan mahasiswa/pemohon setelah mengajukan surat rekomendasi izin riset dan magang.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-low border border-outline-variant/40 rounded-xl text-xs font-semibold text-on-surface">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>Fitur Uji Coba Aktif</span>
            </span>
        </div>
    </div>

    <!-- Quick Summary KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <!-- Card 1: Total Ulasan -->
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-on-surface-variant mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-outline">Total Ulasan</span>
                <span class="material-symbols-outlined text-primary text-[22px]">reviews</span>
            </div>
            <div>
                <span class="text-2xl font-extrabold text-on-surface">{{ number_format($totalReviews, 0, ',', '.') }}</span>
                <span class="text-xs text-on-surface-variant block mt-0.5">Responden</span>
            </div>
        </div>

        <!-- Card 2: Skor UI/UX -->
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-on-surface-variant mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-outline">UI / UX</span>
                <span class="material-symbols-outlined text-blue-600 text-[22px]">palette</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-extrabold text-blue-600">{{ $avgUiUx }}</span>
                    <span class="text-xs text-outline font-semibold">/10</span>
                </div>
                <span class="text-[11px] text-on-surface-variant block mt-0.5">Desain &amp; Antarmuka</span>
            </div>
        </div>

        <!-- Card 3: Kemudahan -->
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-on-surface-variant mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-outline">Kemudahan</span>
                <span class="material-symbols-outlined text-emerald-600 text-[22px]">touch_app</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-extrabold text-emerald-600">{{ $avgKemudahan }}</span>
                    <span class="text-xs text-outline font-semibold">/10</span>
                </div>
                <span class="text-[11px] text-on-surface-variant block mt-0.5">Pengoperasian Sistem</span>
            </div>
        </div>

        <!-- Card 4: Kecepatan -->
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-on-surface-variant mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-outline">Kecepatan</span>
                <span class="material-symbols-outlined text-amber-600 text-[22px]">speed</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-extrabold text-amber-600">{{ $avgKecepatan }}</span>
                    <span class="text-xs text-outline font-semibold">/10</span>
                </div>
                <span class="text-[11px] text-on-surface-variant block mt-0.5">Kinerja &amp; Upload</span>
            </div>
        </div>

        <!-- Card 5: Kejelasan -->
        <div class="bg-surface-container-lowest p-5 rounded-2xl border border-outline-variant/30 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between text-on-surface-variant mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-outline">Kejelasan</span>
                <span class="material-symbols-outlined text-purple-600 text-[22px]">info</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-2xl font-extrabold text-purple-600">{{ $avgKejelasan }}</span>
                    <span class="text-xs text-outline font-semibold">/10</span>
                </div>
                <span class="text-[11px] text-on-surface-variant block mt-0.5">Petunjuk Alur</span>
            </div>
        </div>

        <!-- Card 6: Rata-Rata Total -->
        <div class="bg-gradient-to-br from-primary to-secondary p-5 rounded-2xl text-white shadow-md flex flex-col justify-between">
            <div class="flex items-center justify-between text-white/80 mb-2">
                <span class="text-xs font-bold uppercase tracking-wider text-white/90">Skor Rerata</span>
                <span class="material-symbols-outlined text-amber-300 text-[24px]">grade</span>
            </div>
            <div>
                <div class="flex items-baseline gap-1">
                    <span class="text-3xl font-extrabold text-white">{{ $overallAvg }}</span>
                    <span class="text-xs text-white/70 font-bold">/10</span>
                </div>
                <span class="text-[11px] text-white/80 block mt-0.5">Indeks Kepuasan Total</span>
            </div>
        </div>
    </div>

    <!-- Search & Filter Card -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-outline-variant/30 shadow-xs">
        <form action="{{ route('superadmin.reviews.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <div class="md:col-span-8">
                <label for="search" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                    Cari Responden / Masukan
                </label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline text-[20px]">search</span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Cari nama pemohon, kampus, atau isi kritik &amp; saran..." class="w-full pl-10 pr-4 py-2.5 bg-surface border border-outline-variant/60 rounded-xl text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                </div>
            </div>

            <div class="md:col-span-3">
                <label for="min_score" class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant mb-2">
                    Filter Skor Minimal
                </label>
                <select name="min_score" id="min_score" class="w-full px-3.5 py-2.5 bg-surface border border-outline-variant/60 rounded-xl text-sm text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all">
                    <option value="">Semua Nilai</option>
                    <option value="9" {{ request('min_score') == '9' ? 'selected' : '' }}>Skor &ge; 9.0 (Sangat Puas)</option>
                    <option value="8" {{ request('min_score') == '8' ? 'selected' : '' }}>Skor &ge; 8.0 (Puas)</option>
                    <option value="7" {{ request('min_score') == '7' ? 'selected' : '' }}>Skor &ge; 7.0 (Cukup)</option>
                    <option value="5" {{ request('min_score') == '5' ? 'selected' : '' }}>Skor &ge; 5.0</option>
                </select>
            </div>

            <div class="md:col-span-1 flex gap-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white rounded-xl text-sm font-semibold hover:opacity-95 transition-opacity flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">filter_alt</span>
                </button>
                @if(request()->hasAny(['search', 'min_score']))
                <a href="{{ route('superadmin.reviews.index') }}" class="py-2.5 px-3 bg-surface-container-low text-on-surface-variant hover:text-error rounded-xl text-sm flex items-center justify-center" title="Reset Filter">
                    <span class="material-symbols-outlined text-[20px]">refresh</span>
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Table of Reviews -->
    <div class="bg-surface-container-lowest rounded-2xl border border-outline-variant/30 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-outline-variant/20 flex items-center justify-between">
            <h3 class="font-bold text-base text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary text-[22px]">list_alt</span>
                Daftar Masukan &amp; Ulasan Pengguna
            </h3>
            <span class="text-xs text-on-surface-variant font-medium">
                Menampilkan {{ $reviews->count() }} dari {{ $reviews->total() }} ulasan
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-[11px] font-bold uppercase tracking-wider border-b border-outline-variant/30">
                        <th class="px-5 py-3.5 whitespace-nowrap">Waktu</th>
                        <th class="px-5 py-3.5 whitespace-nowrap">Mahasiswa / Pemohon</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">UI/UX</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Kemudahan</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Kecepatan</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Kejelasan</th>
                        <th class="px-4 py-3.5 text-center whitespace-nowrap">Rerata</th>
                        <th class="px-6 py-3.5 whitespace-nowrap min-w-[280px]">Kritik &amp; Saran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/20 text-sm">
                    @forelse($reviews as $review)
                    @php
                        $avg = $review->average_score;
                        $badgeClass = $avg >= 8 ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($avg >= 6 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-red-50 text-red-700 border-red-200');
                    @endphp
                    <tr class="hover:bg-surface-container-low/40 transition-colors">
                        <!-- Waktu -->
                        <td class="px-5 py-4 whitespace-nowrap text-xs text-on-surface-variant">
                            <div>{{ $review->created_at->format('d M Y') }}</div>
                            <div class="text-[11px] text-outline mt-0.5">{{ $review->created_at->format('H:i') }} WIB</div>
                        </td>

                        <!-- Mahasiswa -->
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="font-bold text-on-surface">{{ $review->user->name ?? 'Pengguna Anonim' }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $review->user->asal_instansi ?? '-' }}</div>
                            @if($review->permohonanLayanan)
                            <div class="text-[11px] text-primary mt-1 font-semibold">
                                #{{ $review->permohonanLayanan->id }} &bull; {{ $review->permohonanLayanan->jenisLayanan->nama ?? $review->permohonanLayanan->jenis_permohonan }}
                            </div>
                            @endif
                        </td>

                        <!-- Rating UI/UX -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-xs {{ $review->rating_ui_ux >= 8 ? 'bg-blue-50 text-blue-700 border border-blue-200' : ($review->rating_ui_ux >= 6 ? 'bg-slate-100 text-slate-700' : 'bg-red-50 text-red-700') }}">
                                {{ $review->rating_ui_ux }}
                            </span>
                        </td>

                        <!-- Rating Kemudahan -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-xs {{ $review->rating_kemudahan >= 8 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($review->rating_kemudahan >= 6 ? 'bg-slate-100 text-slate-700' : 'bg-red-50 text-red-700') }}">
                                {{ $review->rating_kemudahan }}
                            </span>
                        </td>

                        <!-- Rating Kecepatan -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-xs {{ $review->rating_kecepatan >= 8 ? 'bg-amber-50 text-amber-700 border border-amber-200' : ($review->rating_kecepatan >= 6 ? 'bg-slate-100 text-slate-700' : 'bg-red-50 text-red-700') }}">
                                {{ $review->rating_kecepatan }}
                            </span>
                        </td>

                        <!-- Rating Kejelasan -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg font-bold text-xs {{ $review->rating_kejelasan >= 8 ? 'bg-purple-50 text-purple-700 border border-purple-200' : ($review->rating_kejelasan >= 6 ? 'bg-slate-100 text-slate-700' : 'bg-red-50 text-red-700') }}">
                                {{ $review->rating_kejelasan }}
                            </span>
                        </td>

                        <!-- Skor Rata-rata -->
                        <td class="px-4 py-4 text-center">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-extrabold border {{ $badgeClass }}">
                                <span class="material-symbols-outlined text-[14px]">star</span>
                                {{ number_format($avg, 1) }}
                            </span>
                        </td>

                        <!-- Kritik & Saran -->
                        <td class="px-6 py-4">
                            @if(!empty($review->kritik_saran))
                            <p class="text-xs text-on-surface leading-relaxed whitespace-pre-line bg-surface-container-low/60 p-3 rounded-xl border border-outline-variant/30">
                                {{ $review->kritik_saran }}
                            </p>
                            @else
                            <span class="text-xs text-outline italic">Tidak menyertakan catatan khusus.</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-on-surface-variant">
                            <div class="w-16 h-16 rounded-full bg-primary/10 text-primary flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-[32px]">rate_review</span>
                            </div>
                            <h4 class="font-bold text-on-surface text-base mb-1">Belum Ada Ulasan Masuk</h4>
                            <p class="text-xs text-outline max-w-md mx-auto">
                                Ulasan akan otomatis muncul di sini setelah mahasiswa atau pemohon selesai mengajukan permohonan surat izin rekomendasi.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reviews->hasPages())
        <div class="p-4 border-t border-outline-variant/20">
            {{ $reviews->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
