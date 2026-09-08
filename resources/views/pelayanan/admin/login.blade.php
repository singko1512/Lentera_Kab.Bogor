@extends('pelayanan.layouts.admin')

@section('title', $activeAuthMode === 'register' ? 'Register Peserta Magang' : ($activeAuthMode === 'forgot' ? 'Lupa Password Peserta' : $title))

@section('styles')
<!-- Tailwind CSS & Config for Login Redesign -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                "colors": {
                    "outline": "#737780",
                    "on-surface-variant": "#43474f",
                    "surface-container": "#e5eeff",
                    "outline-variant": "#c3c6d1",
                    "on-primary": "#ffffff",
                    "surface-container-low": "#eff4ff",
                    "on-surface": "#0b1c30",
                    "background": "#f8f9ff",
                    "primary-container": "#003366",
                    "on-primary-container": "#799dd6",
                    "surface-container-lowest": "#ffffff",
                    "secondary": "#115cb9",
                    "primary": "#001e40",
                    "error": "#ba1a1a",
                },
                "spacing": {
                    "stack-sm": "0.5rem",
                    "stack-md": "1rem",
                    "stack-lg": "2rem",
                    "margin-desktop": "2.5rem",
                    "margin-mobile": "1rem"
                },
                "fontFamily": {
                    "body-lg": ["Plus Jakarta Sans"],
                    "headline-lg": ["Plus Jakarta Sans"],
                    "caption": ["Plus Jakarta Sans"],
                    "display-lg": ["Plus Jakarta Sans"],
                    "headline-lg-mobile": ["Plus Jakarta Sans"],
                    "title-lg": ["Plus Jakarta Sans"],
                    "body-md": ["Plus Jakarta Sans"],
                    "label-md": ["Plus Jakarta Sans"],
                    "headline-md": ["Plus Jakarta Sans"]
                }
            }
        }
    }
</script>
<style>
    /* Prevent default admin layout conflicts */
    body { background-color: #f8f9ff !important; margin: 0; padding: 0; overflow-x: hidden; }
    #wrapper, .page-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; min-height: 100vh !important; }
    .navbar, footer { display: none !important; }
    
    .ambient-shadow-level-1 {
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.05);
    }
    .input-focus-glow:focus {
        box-shadow: 0 0 0 3px rgba(0, 86, 179, 0.2);
    }
</style>
@endsection

@section('content')
<div class="h-screen overflow-hidden flex flex-col md:flex-row w-full m-0 p-0 absolute top-0 left-0 bg-background">
    <!-- Left Side: Branding / Imagery -->
    <div class="hidden md:flex flex-col md:w-5/12 lg:w-1/2 bg-primary-container relative overflow-hidden text-on-primary p-margin-desktop justify-between h-full">
        <div class="absolute inset-0 z-0 mix-blend-overlay opacity-30 bg-cover bg-center" style="background-image: url('{{ asset('assets/tugu_pancakarsa.jpg') }}')"></div>
        
        <div class="relative z-10 flex items-center gap-3">
                <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" class="h-8 w-auto object-contain">
                <span class="text-[24px] font-bold tracking-tight">LENTERA</span>
            </div>
            
            <h1 class="text-[32px] md:text-[48px] font-bold mt-12 max-w-lg leading-tight">
                Mulai Perjalanan Karir Anda Bersama Kami.
            </h1>
            <p class="text-[18px] text-on-primary-container mt-stack-md max-w-md">
                Layanan Integrasi Izin Riset dan Magang Kabupaten Bogor membuka peluang bagi talenta terbaik untuk berkontribusi dan berkembang.
            </p>
        

    </div>

    <!-- Right Side: Form Content -->
    <div class="w-full md:w-7/12 lg:w-1/2 flex flex-col items-center p-margin-mobile md:p-margin-desktop bg-background h-full overflow-y-auto">
        <div class="w-full max-w-xl bg-surface-container-lowest rounded-xl ambient-shadow-level-1 border-t-4 border-primary-container p-6 md:p-stack-lg my-auto">
            
            <div class="md:hidden flex items-center gap-2 mb-stack-md text-primary-container">
                <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" class="h-8 w-auto object-contain">
                <span class="text-[24px] font-bold">LENTERA</span>
            </div>

            <!-- Form Header -->
            <div class="mb-stack-lg">
                <h2 class="text-[24px] md:text-[32px] font-bold text-on-surface mb-2">
                    {{ $activeAuthMode === 'register' ? 'Buat Akun' : ($activeAuthMode === 'forgot' ? 'Lupa Kata Sandi' : $title) }}
                </h2>
                @if($activeAuthMode === 'register')
                    <p class="text-[16px] text-on-surface-variant">
                        Daftar untuk mengakses layanan izin riset dan magang Pemerintah Kabupaten Bogor.
                    </p>
                @elseif($activeAuthMode === 'forgot')
                    <p class="text-[16px] text-on-surface-variant">
                        Masukkan NIK dan Email yang Anda daftarkan. Tautan untuk menyetel ulang kata sandi akan dikirimkan ke email Anda.
                    </p>
                @else
                    <p class="text-[16px] text-on-surface-variant">
                        Masuk untuk mengakses layanan LENTERA.
                    </p>
                @endif
            </div>

            <!-- Flash Alerts & Link Previews -->
            @if(session('success'))
                <div class="bg-success/15 border border-success/30 text-success p-4 rounded-xl text-sm mb-4 flex flex-col gap-2">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">check_circle</span>
                        <span>{{ session('success') }}</span>
                    </div>
                    @if(session('activation_url') || session('reset_url') || str_contains(session('success'), 'email') || str_contains(session('success'), 'Email'))
                        <div class="mt-2 pt-1">
                            <a href="https://mail.google.com" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2 bg-[#ea4335] hover:bg-[#d93025] text-white font-bold text-xs rounded-xl transition-all shadow-sm">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                    <path d="M24 5.457v13.909c0 .904-.732 1.636-1.636 1.636h-3.819V11.73L12 16.64l-6.545-4.91v9.273H1.636A1.636 1.636 0 0 1 0 19.366V5.457c0-2.023 2.309-3.178 3.927-1.964L12 9.545l8.073-6.052c1.618-1.214 3.927-.059 3.927 1.964z"/>
                                </svg>
                                Buka Gmail
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('error'))
                <div class="bg-error/15 border border-error/30 text-error p-4 rounded-xl text-sm mb-4 flex flex-col gap-2">
                    <div class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-[20px] shrink-0 mt-0.5">warning</span>
                        <span>{{ session('error') }}</span>
                    </div>
                    @if(session('resend_user_id'))
                        <form action="{{ route('account.activate.resend') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ session('resend_user_id') }}">
                            <button type="submit" class="text-xs bg-error text-white px-3.5 py-1.5 rounded-lg font-bold hover:bg-error/90 transition-colors inline-flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[14px]">send</span>
                                Kirim Ulang Link Aktivasi Email
                            </button>
                        </form>
                    @endif
                </div>
            @endif

            @if($activeAuthMode === 'register')
                <!-- REGISTRATION FORM -->
                <form action="{{ route('register.store') }}" method="POST" class="flex flex-col gap-stack-md">
                    @csrf
                    <!-- Grid Layout for 2 columns -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Nama Lengkap</label>
                            <input name="nama" type="text" value="{{ old('nama') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('nama')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Email</label>
                            <input name="email" type="email" value="{{ old('email') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('email')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">NIK</label>
                            <input name="nik" type="text" value="{{ old('nik') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('nik')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">No WhatsApp</label>
                            <input name="no_hp" type="text" value="{{ old('no_hp') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('no_hp')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Tempat Lahir</label>
                            <input name="tempat_lahir" type="text" value="{{ old('tempat_lahir') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('tempat_lahir')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Tanggal Lahir</label>
                            <input name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('tanggal_lahir')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Asal Kampus / Sekolah</label>
                            <input name="asal_instansi" type="text" value="{{ old('asal_instansi') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('asal_instansi')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Program Studi / Jurusan</label>
                            <input name="program_studi" type="text" value="{{ old('program_studi') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('program_studi')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-[14px] font-medium text-on-surface">Nomor Induk (NIM / NIS / NISN) <span class="text-on-surface-variant text-[12px] font-normal">(Opsional)</span></label>
                            <input name="nim" type="text" value="{{ old('nim') }}" class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('nim')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2 md:col-span-2">
                            <label class="text-[14px] font-medium text-on-surface">Alamat Lengkap</label>
                            <textarea name="alamat" required rows="2" class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">{{ old('alamat') }}</textarea>
                            @error('alamat')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Kata Sandi</label>
                            <input name="password" type="password" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                            @error('password')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="flex flex-col gap-2">
                            <label class="text-[14px] font-medium text-on-surface">Konfirmasi Sandi</label>
                            <input name="password_confirmation" type="password" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                        </div>
                    </div>

                    <!-- Actions -->
                    <button type="submit" class="w-full bg-primary-container text-on-primary text-[14px] font-semibold py-3 px-6 rounded-lg hover:bg-primary transition-colors flex justify-center items-center gap-2 mt-4">
                        Daftar Sekarang
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                    <p class="text-center text-[16px] text-on-surface-variant mt-2">
                        Sudah punya akun? <a href="{{ route('login.form') }}" class="text-secondary font-medium hover:underline">Masuk di sini</a>
                    </p>
                </form>

            @elseif($activeAuthMode === 'forgot')
                <!-- FORGOT PASSWORD FORM (NIK + EMAIL) -->
                <form action="{{ route('password.verify') }}" method="POST" class="flex flex-col gap-stack-md">
                    @csrf
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-medium text-on-surface">NIK (Nomor Induk Kependudukan) <span class="text-error">*</span></label>
                        <input name="nik" type="text" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK Anda" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                        @error('nik')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-medium text-on-surface">Email Terdaftar (Gmail / Email) <span class="text-error">*</span></label>
                        <input name="email" type="email" value="{{ old('email') }}" placeholder="contoh@email.com" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                        @error('email')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 mt-4">
                        <button type="submit" class="w-full bg-primary-container text-on-primary text-[14px] font-semibold py-3 px-6 rounded-lg hover:bg-primary transition-colors flex justify-center items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">send</span>
                            Kirim Tautan Reset ke Email
                        </button>
                        <a href="{{ route('login.form') }}" class="w-full bg-white border border-outline text-on-surface text-[14px] font-semibold py-3 px-6 rounded-lg hover:bg-gray-50 transition-colors flex justify-center items-center gap-2">
                            Kembali
                        </a>
                    </div>
                </form>

            @else
                <!-- LOGIN FORM -->
                <form action="{{ $action }}" method="POST" class="flex flex-col gap-stack-md">
                    @csrf
                    @if(in_array($loginRole, ['admin', 'superadmin'], true))
                        <input type="hidden" name="expected_role" value="{{ $loginRole }}">
                    @endif
                    @php
                        $loginLabel = 'Email / Username';
                        $loginPlaceholder = 'Masukkan Email atau Username Anda';
                    @endphp
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-medium text-on-surface">{{ $loginLabel }}</label>
                        <input name="login" type="text" value="{{ old('login', old('username')) }}" placeholder="{{ $loginPlaceholder }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                        @error('login')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <div class="flex justify-between items-center">
                            <label class="text-[14px] font-medium text-on-surface">Kata Sandi</label>
                            @if(! in_array($loginRole, ['admin', 'superadmin'], true))
                                <a href="{{ route('password.request') }}" class="text-[12px] text-secondary font-medium hover:underline">Lupa sandi?</a>
                            @endif
                        </div>
                        <input name="password" type="password" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                        @error('password')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                    </div>

                    <button type="submit" class="w-full bg-primary-container text-on-primary text-[14px] font-semibold py-3 px-6 rounded-lg hover:bg-primary transition-colors flex justify-center items-center gap-2 mt-4">
                        Masuk Sistem
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>
                    
                    @if(! in_array($loginRole, ['admin', 'superadmin'], true))
                        <p class="text-center text-[16px] text-on-surface-variant mt-2">
                            Belum punya akun? <a href="{{ route('login.form', ['mode' => 'register']) }}" class="text-secondary font-medium hover:underline">Daftar di sini</a>
                        </p>
                    @endif
                    <div class="mt-4 text-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-[14px] text-outline hover:text-on-surface transition-colors">
                            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                            Kembali ke Beranda
                        </a>
                    </div>
                </form>
            @endif

        </div>
    </div>
</div>
@endsection
