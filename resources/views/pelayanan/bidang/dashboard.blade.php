@extends('layouts.app')

@section('title', 'Dashboard Admin Bidang')

@section('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
    .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05); border-radius: 1.25rem; }
    .gradient-text { background: linear-gradient(135deg, #0f172a 0%, #334155 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
</style>
@endsection

@section('content')
<div class="min-h-screen pt-12 pb-24 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">
        
        <div class="glass-card p-8 relative overflow-hidden">
            <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-50 rounded-full blur-3xl opacity-60 -translate-y-1/2 translate-x-1/3"></div>
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold gradient-text mb-2">Portal Admin Bidang</h1>
                    <p class="text-gray-500 font-medium">Monitoring dan manajemen peserta magang di bidang Anda.</p>
                </div>
                <div class="flex gap-3">
                    <div class="bg-indigo-50 text-indigo-700 px-4 py-2 rounded-xl border border-indigo-100 font-semibold flex items-center gap-2">
                        <span class="material-symbols-outlined">group</span>
                        Total: {{ count($pesertas) }} Peserta
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="px-8 py-6 border-b border-gray-100 bg-white/50">
                <h2 class="text-lg font-bold text-gray-800">Daftar Peserta Aktif</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-semibold border-b border-gray-100">
                            <th class="px-8 py-4">Peserta</th>
                            <th class="px-8 py-4">Institusi Asal</th>
                            <th class="px-8 py-4">Posisi / Rekrutmen</th>
                            <th class="px-8 py-4">Tanggal Mulai</th>
                            <th class="px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @forelse($pesertas as $peserta)
                            <tr class="hover:bg-gray-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-100 to-blue-100 text-indigo-600 flex items-center justify-center font-bold">
                                            {{ substr($peserta->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900">{{ $peserta->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $peserta->user->email ?? 'Peserta' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-600 font-medium">
                                    {{ $peserta->permohonanLayanan->asal_instansi ?? ($peserta->permohonanLayanan->asal_institusi_pendidikan ?? '-') }}
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                        {{ $peserta->rekrutmen->judul ?? 'Magang' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($peserta->tanggal_mulai)->format('d M Y') }}
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ url('bidang/peserta', $peserta->id) }}" class="inline-flex items-center gap-1 bg-white border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 text-gray-600 px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm">
                                        Detail <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-12 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                                        <span class="material-symbols-outlined text-gray-400 text-3xl">group_off</span>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada peserta magang yang diterima di bidang ini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
