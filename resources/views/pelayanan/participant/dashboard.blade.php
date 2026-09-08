@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang, {{ $user->nama }}</h2>
        <p class="text-gray-600 mb-6">Dashboard Peserta Magang LENTERA Kab Bogor</p>

        <!-- Tracking Status Magang -->
        <div class="mb-8 p-4 border rounded-lg {{ $isAccepted ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
            <h3 class="font-bold text-lg mb-2">Status Magang Anda</h3>
            @if($participant)
                <p>Status: <strong>{{ $participant->booking->status->nama ?? 'Unknown' }}</strong></p>
                <p>Instansi: {{ $participant->booking->instansi->nama }}</p>
                
                @if($isAccepted)
                    <p class="mt-2 text-green-700 font-medium">Anda telah resmi terdaftar. Silakan gunakan menu absensi di bawah.</p>
                @else
                    <p class="mt-2 text-yellow-700">Booking Anda sedang diproses atau menunggu verifikasi pengajuan surat.</p>
                    <a href="{{ route('application.form') }}" class="inline-block mt-2 text-blue-600 hover:underline">Lengkapi Pengajuan Surat</a>
                @endif
            @else
                <p class="mb-2">Anda belum mendaftar/booking magang di instansi manapun.</p>
                <a href="{{ route('booking.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition inline-block">Cari Instansi Sekarang</a>
            @endif
        </div>

        <!-- Tampilan otomatis: Menu Absensi hanya muncul jika isAccepted -->
        @if($isAccepted)
        <div class="mb-8">
            <h3 class="font-bold text-lg mb-4">Fitur Magang (Telah Terbuka)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('absensi.index') }}" class="p-6 border rounded-xl shadow-sm hover:shadow-md transition text-center block bg-white">
                    <div class="text-3xl mb-2">📸</div>
                    <h4 class="font-bold text-gray-800">Absensi Harian</h4>
                    <p class="text-sm text-gray-500">Catat kehadiran dan log aktivitas Anda.</p>
                </a>
                <a href="#" class="p-6 border rounded-xl shadow-sm hover:shadow-md transition text-center block bg-white">
                    <div class="text-3xl mb-2">📄</div>
                    <h4 class="font-bold text-gray-800">Ajukan Izin</h4>
                    <p class="text-sm text-gray-500">Form pengajuan izin ketidakhadiran.</p>
                </a>
                <a href="#" class="p-6 border rounded-xl shadow-sm hover:shadow-md transition text-center block bg-white">
                    <div class="text-3xl mb-2">📅</div>
                    <h4 class="font-bold text-gray-800">Perpanjang Magang</h4>
                    <p class="text-sm text-gray-500">Ajukan perpanjangan durasi magang.</p>
                </a>
            </div>
        </div>
        @endif

        <!-- Lengkapi Profil -->
        <div>
            <h3 class="font-bold text-lg mb-4">Lengkapi Profil Anda</h3>
            <form action="{{ route('participant.profile.update') }}" method="POST" class="max-w-md">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $user->nama }}" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">No. Handphone / WA</label>
                    <input type="text" name="no_hp" value="{{ $user->no_hp }}" class="w-full border p-2 rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-1">Asal Instansi / Universitas</label>
                    <input type="text" name="asal_instansi" value="{{ $user->asal_instansi }}" class="w-full border p-2 rounded" required>
                </div>
                <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Simpan Profil</button>
            </form>
        </div>
    </div>
</div>
@endsection
