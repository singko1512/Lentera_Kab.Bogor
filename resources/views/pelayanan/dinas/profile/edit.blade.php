@extends('pelayanan.layouts.dinas_stitch')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div>
        <h1 class="text-headline-lg font-headline-lg text-on-background">Profil Akun</h1>
        <p class="text-body-lg text-on-surface-variant mt-1">Kelola informasi pribadi dan keamanan akun Admin Dinas Anda.</p>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <span class="font-medium">Gagal!</span> {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Profile Form -->
    <div class="bg-surface rounded-2xl shadow-soft border border-outline-variant/30 overflow-hidden">
        <form action="{{ route('dinas.profile.update') }}" method="POST" class="p-6 md:p-8 space-y-8">
            @csrf
            
            <!-- Informasi Akun -->
            <div class="space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[28px]">person</span>
                    <h2 class="text-title-lg font-title-lg text-on-surface">Informasi Akun</h2>
                </div>
                
                <div>
                    <label for="email" class="block text-label-md font-label-md text-on-surface mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-xl border-outline-variant/30 text-body-md focus:ring-primary focus:border-primary bg-surface-container-lowest" required>
                </div>
            </div>

            <!-- Pengaturan Status Instansi -->
            @if(isset($dinas) && $dinas)
            <div class="space-y-6 pt-6 border-t border-outline-variant/30">
                <div class="flex items-center gap-3 pb-4 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[28px]">tune</span>
                    <div>
                        <h2 class="text-title-lg font-title-lg text-on-surface">Status Ketersediaan Magang Instansi</h2>
                        <p class="text-body-sm text-on-surface-variant mt-1">Status ini akan ditampilkan pada kartu instansi di halaman utama dan katalog instansi.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div>
                        <label for="status_magang" class="block text-label-md font-label-md text-on-surface mb-2">Status Magang Instansi</label>
                        <select id="status_magang" name="status_magang" class="w-full rounded-xl border-outline-variant/30 text-body-md focus:ring-primary focus:border-primary bg-surface-container-lowest font-medium">
                            <option value="otomatis" {{ old('status_magang', $dinas->status_magang ?? 'otomatis') == 'otomatis' ? 'selected' : '' }}>🔄 Otomatis (Sesuai Slot Lowongan)</option>
                            <option value="tersedia" {{ old('status_magang', $dinas->status_magang ?? '') == 'tersedia' ? 'selected' : '' }}>🟢 KUOTA TERSEDIA</option>
                            <option value="penuh" {{ old('status_magang', $dinas->status_magang ?? '') == 'penuh' ? 'selected' : '' }}>🔴 KUOTA PENUH</option>
                            <option value="tidak_tersedia" {{ old('status_magang', $dinas->status_magang ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>⚪ TIDAK TERSEDIA</option>
                        </select>
                    </div>
                    
                    <div>
                        @php $badge = $dinas->status_badge; @endphp
                        <label class="block text-label-md font-label-md text-on-surface mb-2">Pratinjau Badge Status</label>
                        <div class="inline-flex items-center gap-1.5 px-4 py-2 {{ $badge['bg_class'] }} rounded-xl text-xs font-bold shadow-xs">
                            <span class="material-symbols-outlined text-[16px]">{{ $badge['icon'] }}</span>
                            <span>{{ $badge['label'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Keamanan Akun -->
            <div class="space-y-6 pt-6">
                <div class="flex items-center gap-3 pb-4 border-b border-outline-variant/30">
                    <span class="material-symbols-outlined text-primary text-[28px]">lock</span>
                    <div>
                        <h2 class="text-title-lg font-title-lg text-on-surface">Keamanan Akun</h2>
                        <p class="text-body-sm text-on-surface-variant mt-1">Kosongkan jika Anda tidak ingin mengubah password.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-label-md font-label-md text-on-surface mb-2">Password Baru</label>
                        <input type="password" id="password" name="password" class="w-full rounded-xl border-outline-variant/30 text-body-md focus:ring-primary focus:border-primary bg-surface-container-lowest" minlength="8">
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-label-md font-label-md text-on-surface mb-2">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="w-full rounded-xl border-outline-variant/30 text-body-md focus:ring-primary focus:border-primary bg-surface-container-lowest">
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-outline-variant/30">
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-label-md hover:bg-primary-dark hover:shadow-md transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
