@extends('pelayanan.layouts.app')

@section('title', 'Akun Berhasil Diaktifkan - LENTERA Kabupaten Bogor')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#001e40",
                        secondary: "#115cb9",
                        "surface-container": "var(--tw-surface-container)",
                        "surface-container-low": "var(--tw-surface-container-low)",
                        "surface-container-lowest": "var(--tw-surface-container-lowest)",
                        "on-surface": "var(--tw-on-surface)",
                        "on-surface-variant": "var(--tw-on-surface-variant)",
                        "outline-variant": "var(--tw-outline-variant)",
                    }
                }
            }
        }
</script>
@endsection

@section('content')
<main class="flex-grow min-h-[75vh] flex items-center justify-center py-16 px-4 sm:px-6 bg-surface-container-low/40">
    <div class="max-w-lg w-full bg-surface-container-lowest border border-outline-variant/30 rounded-3xl p-8 sm:p-10 shadow-2xl text-center flex flex-col items-center gap-6 relative overflow-hidden transition-all">
        
        <!-- Top Decorative Gradient Line -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-blue-600 via-indigo-500 to-emerald-500"></div>

        <!-- Success Icon Ring -->
        <div class="relative flex items-center justify-center mt-3">
            <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-600 flex items-center justify-center border-4 border-emerald-500/20 shadow-md relative z-10">
                <span class="material-symbols-outlined text-[44px]">check_circle</span>
            </div>
        </div>

        <!-- Heading & Message -->
        <div class="flex flex-col gap-2">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-on-surface tracking-tight">
                Akun Berhasil Diaktifkan!
            </h1>
            <p class="text-sm sm:text-base text-on-surface-variant leading-relaxed max-w-md">
                Selamat, email Anda <strong class="text-secondary font-semibold break-all">{{ $user->email }}</strong> telah terverifikasi. Akun LENTERA Anda telah aktif dan siap digunakan.
            </p>
        </div>

        <!-- User Information Box -->
        <div class="w-full bg-surface-container-low rounded-2xl p-5 text-left flex flex-col gap-3.5 border border-outline-variant/20 shadow-inner">
            <div class="flex items-center justify-between gap-3 text-sm pb-3 border-b border-outline-variant/20">
                <span class="text-on-surface-variant font-medium flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[18px]">person</span>
                    Nama Lengkap
                </span>
                <span class="font-bold text-on-surface text-right truncate max-w-[220px]">{{ $user->name ?? $user->nama }}</span>
            </div>
            
            <div class="flex items-center justify-between gap-3 text-sm">
                <span class="text-on-surface-variant font-medium flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-secondary text-[18px]">verified_user</span>
                    Status Akun
                </span>
                <span class="inline-flex items-center gap-1.5 text-xs font-extrabold bg-[#E8F5E9] text-[#2E7D32] px-3 py-1 rounded-full border border-[#C8E6C9] shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-[#2E7D32] animate-pulse"></span>
                    Aktif / Terverifikasi
                </span>
            </div>
        </div>

        <!-- Action Button -->
        <a href="{{ route('login.form') }}" class="w-full bg-primary hover:bg-secondary text-white font-bold py-3.5 px-6 rounded-2xl transition-all duration-300 shadow-md hover:shadow-lg flex items-center justify-center gap-2 group text-base mt-2">
            <span>Masuk ke Halaman Login</span>
            <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
        </a>

        <p class="text-xs text-on-surface-variant">
            Pemerintah Kabupaten Bogor &middot; Layanan Integrasi Izin Riset & Magang
        </p>
    </div>
</main>
@endsection
