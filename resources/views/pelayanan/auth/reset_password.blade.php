@extends('pelayanan.layouts.app')

@section('title', 'Reset Kata Sandi - LENTERA Kabupaten Bogor')

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
<main class="flex-grow min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 bg-slate-50 dark:bg-[#0b1c30] transition-colors duration-300">
    <div class="max-w-md w-full bg-white dark:bg-[#0c1f36] border border-slate-200 dark:border-slate-800 rounded-3xl p-8 sm:p-10 shadow-2xl flex flex-col gap-6 relative overflow-hidden transition-all">
        
        <!-- Top Decorative Gradient Line -->
        <div class="absolute top-0 left-0 right-0 h-2 bg-gradient-to-r from-blue-600 via-indigo-500 to-blue-400"></div>

        <!-- Header -->
        <div class="text-center flex flex-col items-center gap-3">
            <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-950/70 text-blue-600 dark:text-blue-400 flex items-center justify-center border-4 border-blue-500/20 shadow-md">
                <span class="material-symbols-outlined text-[34px]">lock_reset</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                    Buat Kata Sandi Baru
                </h1>
                <p class="text-sm text-slate-600 dark:text-slate-300 mt-1">
                    Silakan masukkan kata sandi baru untuk akun <strong class="text-blue-600 dark:text-blue-400 font-semibold break-all">{{ $email }}</strong>.
                </p>
            </div>
        </div>

        @if(session('error'))
            <div class="bg-rose-50 dark:bg-rose-950/50 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 p-4 rounded-2xl text-sm flex items-start gap-2.5 shadow-sm">
                <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-blue-600 dark:text-blue-400">key</span>
                    Kata Sandi Baru
                </label>
                <input name="password" type="password" required autofocus placeholder="Minimal 8 karakter" class="w-full bg-slate-50 dark:bg-[#0f243d] border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3.5 text-base text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
                @error('password')
                    <div class="text-rose-600 dark:text-rose-400 text-xs mt-1 font-medium">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-sm font-semibold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                    <span class="material-symbols-outlined text-[18px] text-blue-600 dark:text-blue-400">published_with_changes</span>
                    Konfirmasi Kata Sandi Baru
                </label>
                <input name="password_confirmation" type="password" required placeholder="Ulangi kata sandi baru" class="w-full bg-slate-50 dark:bg-[#0f243d] border border-slate-300 dark:border-slate-700 rounded-xl px-4 py-3.5 text-base text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-blue-600 focus:ring-2 focus:ring-blue-500/20 focus:outline-none transition-all">
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-4 px-6 rounded-2xl transition-all duration-300 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40 flex items-center justify-center gap-2 group text-base mt-2">
                <span>Simpan Kata Sandi Baru</span>
                <span class="material-symbols-outlined text-[22px] group-hover:translate-x-1 transition-transform duration-300">save</span>
            </button>

            <a href="{{ route('login.form') }}" class="text-center text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors mt-2">
                &larr; Batal dan kembali ke Login
            </a>
        </form>
    </div>
</main>
@endsection
