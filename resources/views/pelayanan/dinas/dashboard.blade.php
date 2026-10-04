@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12">
    <!-- Welcome & Status Banner (Compact & Integrated) -->
    <section class="bg-white rounded-2xl p-5 md:p-6 shadow-soft border border-outline-variant/40 relative overflow-hidden group">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative z-10">
            <!-- Left: Welcome & Instansi Information -->
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 text-primary flex items-center justify-center shrink-0 shadow-2xs">
                    <span class="material-symbols-outlined text-[26px]">domain</span>
                </div>
                <div>
                    <div class="flex items-center gap-2 mb-1 flex-wrap">
                        <h1 class="text-xl md:text-2xl font-bold text-on-surface tracking-tight">Selamat Datang</h1>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary/10 text-primary border border-primary/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span> {{ $dinas->name ?? 'Instansi' }}
                        </span>
                    </div>
                    <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed">
                        Kelola kuota lowongan, verifikasi penerimaan, dan pengajuan magang di instansi Anda.
                    </p>
                </div>
            </div>

            <!-- Right: Integrated Status Ketersediaan & Quick Setting -->
            @php $badge = $dinas->status_badge; @endphp
            <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-3 bg-surface-container-low p-2 rounded-xl border border-outline-variant/50">
                <div class="flex items-center gap-2 px-2 py-1">
                    <span class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Status:</span>
                    <div class="flex items-center gap-1 px-2.5 py-1 {{ $badge['bg_class'] }} rounded-lg text-xs font-bold shadow-2xs">
                        <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
                        <span>{{ $badge['label'] }}</span>
                    </div>
                </div>

                <div class="hidden sm:block h-6 w-px bg-outline-variant"></div>

                <form action="{{ route('dinas.status_magang.update') }}" method="POST" class="flex items-center gap-2">
                    @csrf
                    <select name="status_magang" id="status_magang" onchange="this.form.submit()" class="bg-white text-on-surface text-xs font-semibold rounded-lg border border-outline-variant focus:ring-primary focus:border-primary px-3 py-1.5 shadow-2xs cursor-pointer">
                        <option value="otomatis" {{ ($dinas->status_magang ?? 'otomatis') == 'otomatis' ? 'selected' : '' }}>🔄 Otomatis (Cek Kuota)</option>
                        <option value="tersedia" {{ ($dinas->status_magang ?? '') == 'tersedia' ? 'selected' : '' }}>🟢 Kuota Tersedia</option>
                        <option value="penuh" {{ ($dinas->status_magang ?? '') == 'penuh' ? 'selected' : '' }}>🔴 Kuota Penuh</option>
                        <option value="tidak_tersedia" {{ ($dinas->status_magang ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>⚪ Tidak Tersedia</option>
                    </select>
                    <button type="submit" class="px-3.5 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-secondary transition-all shadow-xs shrink-0 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px]">save</span>
                        <span>Simpan</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Decorative subtle element -->
        <div class="absolute right-0 -top-10 h-[150%] w-1/4 opacity-[0.03] group-hover:scale-105 group-hover:-rotate-3 transition-transform duration-700 pointer-events-none">
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
    <section class="bg-white rounded-2xl shadow-soft overflow-hidden border border-outline-variant/30 min-h-[480px] flex flex-col">
        <div class="p-5 md:p-6 border-b border-outline-variant/40 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <div>
                <h3 class="text-title-lg font-title-lg text-on-surface font-bold">Daftar Peserta Resmi Magang</h3>
                <p class="text-caption text-on-surface-variant mt-0.5">Daftar mahasiswa/siswa yang telah disetujui dan sedang melaksanakan magang (5 data terbaru).</p>
            </div>
            <a href="{{ route('dinas.participants.index') }}" class="bg-primary text-white px-5 py-2.5 rounded-full text-label-md font-label-md hover:bg-primary/90 hover:shadow-lg hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                Lihat Semua
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40">
                        <th class="py-3 px-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Nama Peserta</th>
                        <th class="py-3 px-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Email</th>
                        <th class="py-3 px-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Penempatan Bidang</th>
                        <th class="py-3 px-6 text-[13px] font-bold text-on-surface-variant uppercase tracking-wider">Status Magang</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant/30">
                    @forelse($pesertaDiterima->take(5) as $peserta)
                    <tr class="hover:bg-primary/5 transition-colors group cursor-pointer" onclick="window.location='{{ route('dinas.participants.show', $peserta->id) }}'">
                        <td class="py-3.5 px-6 font-semibold group-hover:text-primary transition-colors">{{ $peserta->user->name ?? ($peserta->user->nama ?? '-') }}</td>
                        <td class="py-3.5 px-6 text-on-surface-variant text-sm">{{ $peserta->user->email ?? '-' }}</td>
                        <td class="py-3.5 px-6 text-on-surface-variant text-sm">{{ $peserta->rekrutmen->bidang->nama ?? ($peserta->bidang->name ?? '-') }}</td>
                        <td class="py-3.5 px-6">
                            <span class="inline-flex items-center justify-start gap-1.5 px-3 py-1 rounded-full text-[12px] font-bold bg-[#10B981]/10 text-[#10B981] border border-[#10B981]/20">
                                {{ ucfirst($peserta->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="py-24 px-6 text-center">
                            <div class="flex flex-col items-center justify-center max-w-sm mx-auto">
                                <div class="w-14 h-14 rounded-2xl bg-surface-container-low border border-outline-variant/60 flex items-center justify-center text-on-surface-variant/60 mb-3 shadow-2xs">
                                    <span class="material-symbols-outlined text-[28px] text-primary/70">school</span>
                                </div>
                                <h4 class="text-base font-bold text-on-surface mb-1">Belum Ada Peserta Resmi</h4>
                                <p class="text-xs md:text-sm text-on-surface-variant leading-relaxed mb-4">
                                    Peserta yang telah disetujui (ACC) dan aktif melaksanakan magang di instansi Anda akan tampil di tabel ini.
                                </p>
                                <a href="{{ route('dinas.applications.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold text-primary bg-primary/10 hover:bg-primary hover:text-white transition-all">
                                    <span class="material-symbols-outlined text-[16px]">how_to_reg</span>
                                    <span>Cek Pengajuan Masuk</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
