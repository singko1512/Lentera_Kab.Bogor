@extends('layouts.app')

@section('title', 'Dashboard Peserta Magang')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); border-radius: 1.25rem; }
    .gradient-text { background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
</style>
@endsection

@section('content')
<div class="min-h-screen pt-12 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto space-y-8">
        
        <!-- Header -->
        <div class="glass-card p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Halo, {{ Auth::user()->nama ?? Auth::user()->name }} 👋</h1>
                @if($magang)
                <p class="text-gray-500 font-medium">Dashboard Program Magang: <span class="gradient-text font-bold">{{ optional($magang->rekrutmen)->judul ?? 'Program Magang / PKL' }}</span></p>
                <div class="flex items-center gap-2 mt-3 text-sm text-gray-500 bg-gray-50 px-3 py-1.5 rounded-full inline-flex w-fit border border-gray-100">
                    <span class="material-symbols-outlined text-[18px]">domain</span>
                    {{ $magang->dinas->name ?? (optional(optional($magang->rekrutmen)->dinas)->name ?? 'Dinas Tujuan') }} — {{ $magang->bidang->name ?? (optional(optional($magang->rekrutmen)->bidang)->name ?? 'Penempatan Bidang') }}
                </div>
                @else
                <p class="text-gray-500 font-medium">Anda belum memiliki program magang yang aktif saat ini.</p>
                <div class="mt-4">
                    <a href="{{ route('landing.instansi') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white rounded-xl font-medium hover:bg-primary-dark transition-colors">
                        <span class="material-symbols-outlined">search</span> Cari Program Magang
                    </a>
                </div>
                @endif
            </div>
            <div class="text-right">
                @if($magang)
                <div class="inline-flex items-center justify-center px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl border border-indigo-100 font-semibold gap-2 shadow-sm">
                    <span class="material-symbols-outlined">event_available</span>
                    Status: Aktif
                </div>
                @else
                <div class="inline-flex items-center justify-center px-4 py-2 bg-gray-50 text-gray-500 rounded-xl border border-gray-200 font-semibold gap-2 shadow-sm">
                    <span class="material-symbols-outlined">info</span>
                    Belum Terdaftar
                </div>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined">error</span>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        @endif

        @if($magang)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Check In/Out Card -->
            <div class="glass-card p-8 relative overflow-hidden group">
                <div class="absolute -right-10 -top-10 bg-gradient-to-br from-blue-50 to-indigo-50 w-40 h-40 rounded-full opacity-50 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-indigo-600">how_to_reg</span>
                    Absensi Hari Ini
                </h2>
                
                <div class="text-center bg-white rounded-2xl p-6 border border-gray-100 shadow-sm relative z-10">
                    <div class="text-sm font-medium text-gray-500 mb-1">{{ \Carbon\Carbon::today()->locale('id')->translatedFormat('l, d F Y') }}</div>

                    <!-- Display Assigned Project (Read Only) -->
                    <div class="mt-3 mb-4 text-left bg-indigo-50/70 p-3.5 rounded-xl border border-indigo-100">
                        <label class="block text-xs font-bold text-indigo-900 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[16px] text-indigo-600">assignment</span>
                            Proyek / Modul Pekerjaan
                        </label>
                        <div class="flex items-center justify-between gap-2 mt-1">
                            <span class="font-bold text-gray-900 text-sm">
                                {{ $projects->first()->nama ?? 'Website absensi' }}
                            </span>
                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-indigo-100 text-indigo-700 px-2.5 py-0.5 rounded-full">
                                <span class="material-symbols-outlined text-[13px]">lock</span> Read Only
                            </span>
                        </div>
                    </div>
                    
                    @if($absensiHariIni)
                        <div class="mt-4 mb-6 inline-flex flex-col gap-1 items-center bg-green-50 px-6 py-3 rounded-full border border-green-100">
                            <span class="text-green-700 font-bold uppercase tracking-wider text-sm">✓ {{ $absensiHariIni->status }}</span>
                        </div>
                        
                        <div class="flex justify-center gap-8 mb-6 text-left">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Check In</p>
                                <p class="text-lg font-bold text-gray-800">{{ $absensiHariIni->waktu_masuk ?? '--:--' }}</p>
                            </div>
                            <div class="w-px bg-gray-200"></div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-semibold mb-1">Check Out</p>
                                <p class="text-lg font-bold text-gray-800">{{ $absensiHariIni->waktu_pulang ?? '--:--' }}</p>
                            </div>
                        </div>

                        @if(is_null($absensiHariIni->waktu_pulang))
                            <form action="{{ url('peserta/check-out') }}" method="POST" enctype="multipart/form-data" class="space-y-4" onsubmit="return setLocation(this);">
                                @csrf
                                <input type="hidden" name="lokasi" class="lokasi-input">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Selfie Check Out</label>
                                    <input type="file" name="foto" accept="image/*" capture="user" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100 mb-3">
                                </div>
                                <button type="submit" class="w-full bg-gradient-to-r from-orange-400 to-rose-500 hover:from-orange-500 hover:to-rose-600 text-white font-bold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex justify-center items-center gap-2">
                                    <span class="material-symbols-outlined">logout</span> Check Out Sekarang
                                </button>
                            </form>
                        @else
                            <div class="w-full bg-gray-100 text-gray-500 font-bold py-3 px-6 rounded-xl flex justify-center items-center gap-2">
                                <span class="material-symbols-outlined">task_alt</span> Absensi Selesai
                            </div>
                        @endif
                    @else
                        <div class="mt-4 mb-8">
                            <div class="w-16 h-16 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-3">
                                <span class="material-symbols-outlined text-3xl">fingerprint</span>
                            </div>
                            <p class="text-gray-600 font-medium">Anda belum melakukan absensi masuk hari ini.</p>
                        </div>
                        <form action="{{ url('peserta/check-in') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4" onsubmit="return setLocation(this);">
                            @csrf
                            <input type="hidden" name="lokasi" class="lokasi-input">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Selfie Check In</label>
                                <input type="file" name="foto" accept="image/*" capture="user" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 mb-3">
                            </div>
                            <button type="submit" class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 hover:from-indigo-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 flex justify-center items-center gap-2 transform hover:-translate-y-0.5">
                                <span class="material-symbols-outlined">login</span> Check In Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Catatan Jurnal Harian -->
            <div class="glass-card p-8 relative overflow-hidden group">
                <div class="absolute -right-10 -bottom-10 bg-gradient-to-br from-purple-50 to-pink-50 w-40 h-40 rounded-full opacity-50 blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <span class="material-symbols-outlined text-purple-600">edit_note</span>
                    Jurnal Harian
                </h2>

                <form action="{{ url('peserta/jurnal') }}" method="POST" class="relative z-10 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kegiatan Hari Ini</label>
                        <textarea name="kegiatan" rows="3" required class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all" placeholder="Tuliskan detail kegiatan magang Anda hari ini..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-6 rounded-xl shadow-md transition-colors flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">save</span> Simpan Jurnal
                    </button>
                </form>
                <div class="mt-6 relative z-10">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Riwayat Jurnal Terakhir</h3>
                    <div class="space-y-3">
                        @forelse($jurnals as $jurnal)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex items-start gap-3">
                                <div class="mt-1">
                                    @if($jurnal->status_verifikasi)
                                        <span class="material-symbols-outlined text-green-500 text-[20px]" title="Terverifikasi">verified</span>
                                    @else
                                        <span class="material-symbols-outlined text-gray-400 text-[20px]" title="Menunggu Verifikasi">pending</span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs font-semibold text-gray-500 mb-1">{{ \Carbon\Carbon::parse($jurnal->tanggal)->translatedFormat('d M Y') }}</p>
                                    <p class="text-sm text-gray-800 line-clamp-2">{{ $jurnal->kegiatan }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400 text-sm">
                                Belum ada riwayat jurnal.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Proyek & Modul Pekerjaan -->
        <div class="glass-card p-8 relative overflow-hidden">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-600">account_tree</span>
                        Timeline Proyek & Modul Pekerjaan
                    </h2>
                    <p class="text-xs text-gray-500 mt-1">Pantau progres pengerjaan proyek dan modul tugas magang Anda.</p>
                </div>
            </div>

            @forelse($projects as $project)
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm mb-6 last:mb-0">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-3 pb-4 mb-4 border-b border-gray-100">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded-full border border-indigo-100">Proyek #{{ $project->id }}</span>
                                <h3 class="text-lg font-bold text-gray-800">{{ $project->nama }}</h3>
                            </div>
                            @if($project->kebutuhan)
                                <p class="text-xs text-gray-500 mt-1">{{ $project->kebutuhan }}</p>
                            @endif
                        </div>
                        <div class="text-left md:text-right">
                            <div class="text-xs font-semibold text-gray-400">Periode Proyek</div>
                            <div class="text-xs font-bold text-gray-700">
                                {{ $project->tanggal_mulai ? $project->tanggal_mulai->format('d M Y') : '-' }} s/d {{ $project->tanggal_selesai ? $project->tanggal_selesai->format('d M Y') : '-' }}
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar Overall -->
                    <div class="mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="flex justify-between items-center text-xs font-bold text-gray-700 mb-1.5">
                            <span>Total Progres Proyek</span>
                            <span class="text-indigo-600">{{ number_format($project->actual_progress, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 h-3 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full rounded-full transition-all duration-500" style="width: {{ min(100, max(0, $project->actual_progress)) }}%"></div>
                        </div>
                    </div>

                    <!-- Timeline Modul Rows -->
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Daftar Modul Pekerjaan</h4>
                    <div class="space-y-3">
                        @forelse($project->modules as $modul)
                            <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-3">
                                <div class="flex-grow">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full {{ $modul->progress >= 100 ? 'bg-green-500' : ($modul->progress > 0 ? 'bg-amber-500' : 'bg-gray-300') }}"></span>
                                        <span class="text-sm font-bold text-gray-800">{{ $modul->nama }}</span>
                                        <span class="text-[11px] px-2 py-0.5 rounded-full font-semibold {{ $modul->progress >= 100 ? 'bg-green-100 text-green-700' : ($modul->progress > 0 ? 'bg-amber-100 text-amber-700' : 'bg-gray-200 text-gray-600') }}">
                                            {{ $modul->progress >= 100 ? 'Selesai' : ($modul->progress > 0 ? 'Dalam Proses' : 'Belum Dimulai') }}
                                        </span>
                                    </div>
                                    @if($modul->deskripsi)
                                        <p class="text-xs text-gray-500 mt-1 pl-4.5">{{ $modul->deskripsi }}</p>
                                    @endif
                                </div>
                                <div class="w-full md:w-48 shrink-0">
                                    <div class="flex justify-between items-center text-[11px] font-bold text-gray-500 mb-1">
                                        <span>Progres</span>
                                        <span>{{ number_format($modul->progress, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 h-2 rounded-full overflow-hidden">
                                        <div class="bg-indigo-500 h-full rounded-full transition-all duration-300" style="width: {{ min(100, max(0, $modul->progress)) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-200 text-gray-400 text-xs">
                                Belum ada modul pekerjaan yang didaftarkan untuk proyek ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center py-8 bg-white rounded-2xl border border-dashed border-gray-200 p-6">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-2xl">event_note</span>
                    </div>
                    <h3 class="text-sm font-bold text-gray-800 mb-1">Belum Ada Proyek Ditugaskan</h3>
                    <p class="text-xs text-gray-500 max-w-md mx-auto">Proyek dan modul pekerjaan yang diberikan oleh Pembimbing/Admin Bidang akan muncul di sini beserta lini masa (*timeline*) progresnya.</p>
                </div>
            @endforelse
        </div>
        @endif
    </div>
</div>

<script>
    let currentLocation = "";

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                currentLocation = position.coords.latitude + "," + position.coords.longitude;
            },
            (error) => {
                console.warn("Geolocation error:", error.message);
                currentLocation = "Lokasi tidak diizinkan";
            }
        );
    } else {
        currentLocation = "Geolocation tidak didukung";
    }

    function setLocation(form) {
        if(!currentLocation || currentLocation === "Lokasi tidak diizinkan" || currentLocation === "Geolocation tidak didukung") {
            alert("Harap izinkan akses lokasi (GPS) untuk melakukan absensi!");
            // Try to get location again
            navigator.geolocation.getCurrentPosition((pos) => {
                currentLocation = pos.coords.latitude + "," + pos.coords.longitude;
                form.querySelector('.lokasi-input').value = currentLocation;
                form.submit();
            });
            return false;
        }
        form.querySelector('.lokasi-input').value = currentLocation;
        return true;
    }
</script>
@endsection
