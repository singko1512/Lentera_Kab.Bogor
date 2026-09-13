@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Welcome Section -->
    <section class="bg-white rounded-2xl p-8 shadow-soft relative overflow-hidden group flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="relative z-10">
            <h1 class="text-headline-lg-mobile md:text-headline-lg font-headline-lg text-on-surface mb-3 tracking-tight">Selamat Datang</h1>
            <p class="text-body-md font-body-md text-on-surface-variant max-w-2xl leading-relaxed">
                Kelola kuota, status penerimaan, dan pengajuan magang di instansi Anda.
            </p>
        </div>
        
        <!-- Status Magang Live Badge -->
        @php $badge = $dinas->status_badge; @endphp
        <div class="relative z-10 shrink-0 bg-surface-container-low p-4 rounded-xl border border-outline-variant/30">
            <div class="text-xs font-semibold text-on-surface-variant uppercase tracking-wider mb-2">Status Ketersediaan Magang:</div>
            <div class="flex items-center gap-1.5 px-3 py-1.5 {{ $badge['bg_class'] }} rounded-lg text-sm font-bold shadow-xs">
                <span class="material-symbols-outlined text-[18px]">{{ $badge['icon'] }}</span>
                <span>{{ $badge['label'] }}</span>
            </div>
        </div>

        <!-- Decorative element -->
        <div class="absolute right-0 -top-10 h-[150%] w-1/3 opacity-[0.03] group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-700 pointer-events-none">
            <svg class="w-full h-full" viewbox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path d="M47.7,-57.2C59.4,-44.6,64.8,-26.6,66.9,-8.5C69,9.5,67.8,27.7,58.7,42.5C49.6,57.3,32.6,68.7,13.6,71.8C-5.5,74.9,-26.6,69.7,-42.6,57.1C-58.5,44.5,-69.3,24.4,-70.7,4C-72,-16.3,-63.8,-36.3,-50.2,-49C-36.5,-61.6,-17.5,-67,0.1,-67.1C17.7,-67.2,35.9,-69.8,47.7,-57.2Z" fill="#115cb9" transform="translate(100 100)"></path>
            </svg>
        </div>
    </section>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-[#10B981]/10 text-[#10B981] font-semibold flex items-center gap-2 border border-[#10B981]/20">
            <span class="material-symbols-outlined text-[20px]">check_circle</span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Form Setting Status Magang Instansi -->
    <section class="bg-gradient-to-r from-primary/5 via-primary/5 to-white rounded-2xl p-6 border border-primary/10 shadow-soft">
        <form action="{{ route('dinas.status_magang.update') }}" method="POST" class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            @csrf
            <div class="flex flex-col gap-1">
                <label for="status_magang" class="text-title-md font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary text-[24px]">tune</span>
                    Pengaturan Status Ketersediaan Magang Instansi
                </label>
                <p class="text-body-md text-on-surface-variant">Pilih 'Otomatis' agar status disesuaikan dengan kuota lowongan aktif, atau tentukan status manual.</p>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <select name="status_magang" id="status_magang" onchange="this.form.submit()" class="bg-white text-on-surface text-label-md font-semibold rounded-xl border border-outline-variant focus:ring-primary focus:border-primary px-4 py-3 shadow-sm cursor-pointer w-full md:w-64">
                    <option value="otomatis" {{ ($dinas->status_magang ?? 'otomatis') == 'otomatis' ? 'selected' : '' }}>🔄 Otomatis (Cek Kuota)</option>
                    <option value="tersedia" {{ ($dinas->status_magang ?? '') == 'tersedia' ? 'selected' : '' }}>🟢 KUOTA TERSEDIA</option>
                    <option value="penuh" {{ ($dinas->status_magang ?? '') == 'penuh' ? 'selected' : '' }}>🔴 KUOTA PENUH</option>
                    <option value="tidak_tersedia" {{ ($dinas->status_magang ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>⚪ TIDAK TERSEDIA</option>
                </select>
                <button type="submit" class="px-5 py-3 bg-primary text-white text-label-md font-bold rounded-xl hover:bg-secondary transition-all shadow-md shrink-0 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan
                </button>
            </div>
        </form>
    </section>

    <!-- Statistics Grid (Bento Style) -->
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <a href="{{ route('dinas.applications.index') }}" class="block bg-white rounded-2xl p-6 shadow-soft card-hover flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden group cursor-pointer">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#8B5CF6]/5 rounded-full group-hover:scale-150 transition-transform duration-500"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Pengajuan Masuk</p>
                <div class="w-10 h-10 rounded-full bg-[#8B5CF6]/10 flex items-center justify-center text-[#8B5CF6] group-hover:bg-[#8B5CF6] group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined icon-filled text-[20px]">person_add</span>
                </div>
            </div>
            <div class="mt-auto relative z-10">
                <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $totalPengajuanLayanan }}</h3>
                <p class="text-xs font-semibold text-[#8B5CF6] mt-2 flex items-center gap-1">Verifikasi & ACC <span class="material-symbols-outlined text-[14px]">arrow_forward</span></p>
            </div>
        </a>
        
        <!-- Card 2 -->
        <div class="bg-white rounded-2xl p-6 shadow-soft flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-primary/5 rounded-full"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Total Kuota</p>
                <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined icon-filled text-[20px]">group</span>
                </div>
            </div>
            <div class="mt-auto relative z-10">
                <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $totalKuota }}</h3>
            </div>
        </div>
        
        <!-- Card 3 -->
        <div class="bg-white rounded-2xl p-6 shadow-soft flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#10B981]/5 rounded-full"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Sisa Slot</p>
                <div class="w-10 h-10 rounded-full bg-[#10B981]/10 flex items-center justify-center text-[#10B981]">
                    <span class="material-symbols-outlined icon-filled text-[20px]">event_seat</span>
                </div>
            </div>
            <div class="mt-auto relative z-10">
                <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $slotTersedia }}</h3>
            </div>
        </div>
        
        <!-- Card 4 -->
        <div class="bg-white rounded-2xl p-6 shadow-soft flex flex-col justify-between min-h-[140px] border border-outline-variant/30 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-[#F59E0B]/5 rounded-full"></div>
            <div class="flex justify-between items-start relative z-10">
                <p class="text-label-md font-label-md text-on-surface-variant tracking-wide">Peserta Aktif</p>
                <div class="w-10 h-10 rounded-full bg-[#F59E0B]/10 flex items-center justify-center text-[#F59E0B]">
                    <span class="material-symbols-outlined icon-filled text-[20px]">assignment_ind</span>
                </div>
            </div>
            <div class="mt-auto relative z-10">
                <h3 class="text-display-lg font-display-lg text-on-surface leading-none">{{ $pesertaAktif }}</h3>
            </div>
        </div>
    </section>

    <!-- Data Table Section -->
    <section class="bg-white rounded-2xl shadow-soft overflow-hidden flex flex-col border border-outline-variant/30">
        <div class="p-6 border-b border-outline-variant/40 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h3 class="text-title-lg font-title-lg text-on-surface font-bold">Daftar Peserta Resmi Magang</h3>
                <p class="text-caption text-on-surface-variant mt-1">Daftar mahasiswa/siswa yang telah disetujui dan sedang melaksanakan magang.</p>
            </div>
            <a href="{{ route('dinas.participants.index') }}" class="bg-primary text-white px-5 py-2.5 rounded-full text-label-md font-label-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                Lihat Semua
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="p-4 pl-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Email</th>
                        <th class="p-4 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Penempatan Bidang</th>
                        <th class="p-4 pr-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status Magang</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant/30">
                    @forelse($pesertaDiterima as $peserta)
                    <tr class="hover:bg-primary/5 transition-colors group cursor-pointer" onclick="window.location='{{ route('dinas.participants.show', $peserta->id) }}'">
                        <td class="p-4 pl-6 font-semibold group-hover:text-primary transition-colors">{{ $peserta->user->name }}</td>
                        <td class="p-4 text-on-surface-variant">{{ $peserta->user->email }}</td>
                        <td class="p-4 text-on-surface-variant text-sm">{{ $peserta->rekrutmen->bidang->nama ?? '-' }}</td>
                        <td class="p-4 pr-6">
                            <span class="inline-flex items-center justify-start gap-1.5 px-3 py-1.5 rounded-full text-[12px] font-bold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                                {{ ucfirst($peserta->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-6 text-center text-on-surface-variant">Belum ada peserta magang resmi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
