@extends('layouts.app')

@section('title', 'Detail Peserta Magang')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); border-radius: 1.25rem; }
</style>
@endsection

@section('content')
<div class="min-h-screen pt-8 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        
        <!-- Navigation -->
        <a href="{{ url('bidang/dashboard') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-indigo-600 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali ke Dashboard
        </a>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- Left Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Profile Card -->
                <div class="glass-card p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
                    <div class="relative pt-12 text-center">
                        <div class="w-24 h-24 mx-auto bg-white rounded-full p-1 shadow-md mb-4 border-4 border-white">
                            <div class="w-full h-full rounded-full bg-indigo-50 flex items-center justify-center text-3xl font-bold text-indigo-600">
                                {{ substr($peserta->user->name, 0, 1) }}
                            </div>
                        </div>
                        <h2 class="text-xl font-bold text-gray-900">{{ $peserta->user->name }}</h2>
                        <p class="text-sm font-medium text-indigo-600 mb-4">{{ $peserta->permohonanLayanan->asal_institusi_pendidikan ?? 'Instansi' }}</p>
                        
                        <div class="bg-gray-50 rounded-xl p-4 text-left space-y-3 mt-4 border border-gray-100">
                            <div>
                                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Posisi</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $peserta->rekrutmen->judul }}</p>
                            </div>
                            <div class="w-full h-px bg-gray-200"></div>
                            <div class="flex justify-between">
                                <div>
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Mulai</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Selesai</p>
                                    <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($peserta->tanggal_selesai)->format('d M y') }}</p>
                                </div>
                            </div>
                        </div>
                </div>

                <!-- Card Setting WFO / WFH per Hari -->
                <div class="glass-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white/50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-indigo-600">event_repeat</span>
                            Pengaturan WFO / WFH
                        </h3>
                        <span class="text-[11px] font-semibold text-gray-400 bg-gray-100 px-2.5 py-0.5 rounded-full">Admin Bidang</span>
                    </div>
                    <div class="p-6 bg-white">
                        <form action="{{ route('bidang.peserta.update_jadwal', $peserta->id) }}" method="POST" class="space-y-4">
                            @csrf
                            @php
                                $jadwalCurrent = $peserta->jadwal_wfh_wfo;
                                $hariList = [
                                    'senin' => 'Senin',
                                    'selasa' => 'Selasa',
                                    'rabu' => 'Rabu',
                                    'kamis' => 'Kamis',
                                    'jumat' => 'Jumat',
                                ];
                            @endphp

                            <div class="space-y-2.5">
                                @foreach($hariList as $key => $labelHari)
                                    @php $mode = strtolower($jadwalCurrent[$key] ?? 'wfo'); @endphp
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-100 hover:border-gray-200 transition-all">
                                        <span class="text-sm font-bold text-gray-700 w-20">{{ $labelHari }}</span>
                                        <div class="flex gap-2">
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="jadwal[{{ $key }}]" value="wfo" {{ $mode === 'wfo' ? 'checked' : '' }} class="hidden peer">
                                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all peer-checked:bg-indigo-600 peer-checked:text-white bg-gray-200 text-gray-600 hover:bg-gray-300">
                                                    🏢 WFO
                                                </span>
                                            </label>
                                            <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                <input type="radio" name="jadwal[{{ $key }}]" value="wfh" {{ $mode === 'wfh' ? 'checked' : '' }} class="hidden peer">
                                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all peer-checked:bg-purple-600 peer-checked:text-white bg-gray-200 text-gray-600 hover:bg-gray-300">
                                                    🏠 WFH
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="w-full mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-xl text-sm transition-colors shadow-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Simpan Jadwal WFO/WFH
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Absensi Info -->
                <div class="glass-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-white/50">
                        <h3 class="font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-gray-500">calendar_month</span>
                            Riwayat Absensi
                        </h3>
                    </div>
                    <div class="p-0">
                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100">
                            @forelse($peserta->absensis as $abs)
                                <div class="p-4 hover:bg-gray-50 transition-colors flex justify-between items-center bg-white">
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($abs->tanggal)->format('d M') }}</p>
                                        <p class="text-xs text-gray-500 font-medium">{{ ucfirst($abs->status) }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-semibold text-green-600">In: {{ substr($abs->waktu_masuk, 0, 5) ?? '--:--' }}</p>
                                        <p class="text-xs font-semibold text-orange-600">Out: {{ substr($abs->waktu_pulang, 0, 5) ?? '--:--' }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-gray-400 text-sm">
                                    Belum ada rekam absensi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content (Jurnal) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="glass-card overflow-hidden h-full flex flex-col">
                    <div class="px-8 py-6 border-b border-gray-100 bg-white/50 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <span class="material-symbols-outlined text-purple-600">menu_book</span>
                            Jurnal Kegiatan Harian
                        </h3>
                    </div>
                    
                    <div class="flex-1 bg-white p-6 md:p-8">
                        <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                            @forelse($peserta->jurnals as $jurnal)
                                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-100 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                        @if($jurnal->status_verifikasi)
                                            <span class="material-symbols-outlined text-green-500 text-xl">check_circle</span>
                                        @else
                                            <span class="material-symbols-outlined text-amber-500 text-xl">pending</span>
                                        @endif
                                    </div>
                                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow">
                                        <div class="flex items-center justify-between mb-2">
                                            <time class="font-bold text-indigo-600 text-sm">{{ \Carbon\Carbon::parse($jurnal->tanggal)->format('d F Y') }}</time>
                                        </div>
                                        <div class="text-gray-600 text-sm mb-4 leading-relaxed">{{ $jurnal->kegiatan }}</div>
                                        
                                        @if(!$jurnal->status_verifikasi)
                                            <form action="{{ url('bidang/jurnal/'.$jurnal->id.'/verify') }}" method="POST" class="mt-3">
                                                @csrf
                                                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-2 px-4 rounded-lg text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                                                    <span class="material-symbols-outlined text-[18px]">verified</span> Verifikasi Jurnal Ini
                                                </button>
                                            </form>
                                        @else
                                            <div class="mt-3 bg-gray-50 text-gray-500 font-semibold py-2 px-4 rounded-lg text-sm border border-gray-100 text-center">
                                                Telah Diverifikasi
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12 relative z-10 bg-white">
                                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <span class="material-symbols-outlined text-4xl text-gray-300">edit_off</span>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-700">Belum Ada Jurnal</h4>
                                    <p class="text-gray-500">Peserta belum mengisi jurnal harian apapun.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
