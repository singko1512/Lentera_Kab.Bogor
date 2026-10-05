@extends('pelayanan.layouts.app')

@section('title', 'Verifikasi Keabsahan Surat Rekomendasi - LENTERA Kab. Bogor')

@section('content')
<main class="min-h-screen bg-slate-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
        <!-- Header Banner -->
        <div class="bg-gradient-to-r from-primary-900 via-primary to-primary-700 px-6 py-8 text-center text-white relative">
            <div class="inline-flex p-3 bg-white/10 backdrop-blur-md rounded-2xl mb-3 shadow-inner">
                <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Lambang Kabupaten Bogor" class="w-14 h-14 object-contain">
            </div>
            <h1 class="text-lg font-bold uppercase tracking-wide">Pemerintah Kabupaten Bogor</h1>
            <p class="text-xs text-white/80 font-medium">Badan Kesatuan Bangsa dan Politik (Bakesbangpol)</p>
            <p class="text-[11px] text-white/60 mt-1">Layanan Elektronik Terpadu Evaluasi dan Rekomendasi (LENTERA)</p>
        </div>

        <!-- Body Content -->
        <div class="p-6 sm:p-8">
            <div class="text-center mb-6">
                <span class="text-xs font-semibold uppercase tracking-wider text-slate-400">Verifikasi Dokumen Elektronik</span>
                <h2 class="text-xl font-extrabold text-slate-800 mt-1">Surat Rekomendasi Resmi</h2>
            </div>

            <!-- Status Indicator -->
            <div class="mb-6 flex justify-center">
                @if($isValid)
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm">
                        <span class="material-symbols-outlined text-[22px] text-emerald-600">verified</span>
                        <span class="font-bold text-sm tracking-wide">{{ $statusLabel }}</span>
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-rose-50 border border-rose-200 text-rose-700 shadow-sm">
                        <span class="material-symbols-outlined text-[22px] text-rose-600">cancel</span>
                        <span class="font-bold text-sm tracking-wide">{{ $statusLabel }}</span>
                    </div>
                @endif
            </div>

            <!-- Verification Table Details -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200/80 space-y-4 text-sm">
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Nomor Surat:</span>
                    <span class="text-slate-900 font-bold font-mono text-right mt-0.5 sm:mt-0">{{ $nomorSurat }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Nama Pemohon:</span>
                    <span class="text-slate-900 font-semibold text-right mt-0.5 sm:mt-0">{{ $namaPemohon }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1 border-b border-slate-200/60">
                    <span class="text-slate-500 font-medium">Instansi Tujuan:</span>
                    <span class="text-slate-900 font-semibold text-right mt-0.5 sm:mt-0 max-w-[280px]">{{ $instansiTujuan }}</span>
                </div>
                <div class="flex flex-col sm:flex-row sm:justify-between py-1">
                    <span class="text-slate-500 font-medium">Tanggal Terbit:</span>
                    <span class="text-slate-900 font-semibold text-right mt-0.5 sm:mt-0">{{ $tanggalTerbit }}</span>
                </div>
            </div>

            <!-- Catatan / Disclaimer -->
            <div class="mt-6 p-4 rounded-xl {{ $isValid ? 'bg-blue-50/70 border border-blue-100 text-blue-900' : 'bg-amber-50/70 border border-amber-100 text-amber-900' }} text-xs leading-relaxed flex items-start gap-3">
                <span class="material-symbols-outlined text-[18px] shrink-0 mt-0.5 {{ $isValid ? 'text-blue-600' : 'text-amber-600' }}">info</span>
                <div>
                    <p class="font-semibold mb-0.5">Informasi Dokumen:</p>
                    <p class="text-slate-600">{{ $catatan }}</p>
                </div>
            </div>

            <!-- Action Button -->
            <div class="mt-8 text-center">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-semibold text-xs transition-all shadow-md hover:shadow-lg">
                    <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                    <span>Kembali ke Beranda LENTERA</span>
                </a>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="bg-slate-100 px-6 py-3 border-t border-slate-200 text-center text-[11px] text-slate-500">
            &copy; {{ date('Y') }} Pemerintah Kabupaten Bogor. Sistem Layanan Terpadu LENTERA.
        </div>
    </div>
</main>
@endsection
