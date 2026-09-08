@extends('pelayanan.layouts.app')

@section('title', 'Profil Saya - LENTERA Kab Bogor')

@section('styles')
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "outline": "#737780",
                        "on-surface-variant": "var(--tw-on-surface-variant)",
                        "on-tertiary-fixed": "#161c22",
                        "surface-container": "var(--tw-surface-container)",
                        "primary-fixed-dim": "#a7c8ff",
                        "outline-variant": "var(--tw-outline-variant)",
                        "on-primary": "#ffffff",
                        "surface-container-low": "var(--tw-surface-container-low)",
                        "tertiary-container": "#2e343a",
                        "on-surface": "var(--tw-on-surface)",
                        "background": "var(--tw-background)",
                        "tertiary-fixed-dim": "#c1c7cf",
                        "primary-container": "#003366",
                        "secondary-fixed-dim": "#acc7ff",
                        "on-tertiary": "#ffffff",
                        "on-secondary-fixed": "#001a40",
                        "on-tertiary-fixed-variant": "#41474e",
                        "surface-container-highest": "#d3e4fe",
                        "surface-container-lowest": "var(--tw-surface-container-lowest)",
                        "surface-bright": "#f8f9ff",
                        "inverse-on-surface": "#eaf1ff",
                        "surface-tint": "#3a5f94",
                        "on-primary-fixed-variant": "#1f477b",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#003370",
                        "secondary": "#115cb9",
                        "surface-variant": "var(--tw-surface-variant)",
                        "on-error": "#ffffff",
                        "on-primary-fixed": "#001b3c",
                        "on-error-container": "#93000a",
                        "secondary-container": "#659dfe",
                        "inverse-primary": "#a7c8ff",
                        "on-primary-container": "#799dd6",
                        "surface-dim": "#cbdbf5",
                        "on-tertiary-container": "#969ca4",
                        "tertiary-fixed": "#dde3eb",
                        "tertiary": "#191f25",
                        "surface": "var(--tw-background)",
                        "on-background": "var(--tw-on-surface)",
                        "inverse-surface": "#213145",
                        "secondary-fixed": "#d7e2ff",
                        "error": "#ba1a1a",
                        "primary": "var(--tw-primary)",
                        "surface-container-high": "#dce9ff",
                        "primary-fixed": "#d5e3ff",
                        "on-secondary-fixed-variant": "#004491",
                        "error-container": "#ffdad6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "1rem",
                        "stack-sm": "0.5rem",
                        "stack-lg": "2rem",
                        "margin-desktop": "2.5rem",
                        "gutter": "1.5rem",
                        "container-max": "1280px",
                        "stack-md": "1rem"
                    },
                    "fontFamily": {
                        "body-md": ["Plus Jakarta Sans"],
                        "headline-lg-mobile": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"],
                        "display-lg": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "headline-md": ["Plus Jakarta Sans"],
                        "caption": ["Plus Jakarta Sans"],
                        "title-lg": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "display-lg": ["48px", {"lineHeight": "60px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "700"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "caption": ["12px", {"lineHeight": "16px", "fontWeight": "400"}],
                        "title-lg": ["20px", {"lineHeight": "28px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "fontWeight": "500"}]
                    }
                }
            }
        }
    </script>
    <style>
        /* Custom styles */
        .input-focus-glow:focus {
            box-shadow: 0 0 0 3px rgba(17, 92, 185, 0.15);
        }
    </style>
@endsection

@section('content')
<main class="w-full max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-stack-lg flex flex-col gap-6">
    <!-- Header -->
    <div class="flex flex-col gap-1">
        <h1 class="font-display-lg text-display-lg text-on-surface">Data Diri & Profil Saya</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Kelola data profil Anda untuk kemudahan proses administrasi magang.</p>
    </div>

    <!-- Alert Success -->
    @if(session('success_swal'))
        <div class="bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] p-4 rounded-xl flex items-center gap-3 text-body-md">
            <span class="material-symbols-outlined">check_circle</span>
            <span>{{ session('success_swal') }}</span>
        </div>
    @endif

    @php
        $latestLayanan = \App\Models\PermohonanLayanan::where('user_id', auth()->id())->latest()->first();
        $latestApp = \App\Models\MagangApplication::with(['dinas', 'bidang'])->where('user_id', auth()->id())->latest()->first();
    @endphp

    @if($latestLayanan || $latestApp)
    <!-- Card Status Pengajuan & Surat Resmi -->
    <div class="bg-surface-container-lowest rounded-xl p-6 shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-primary/20 flex flex-col gap-4">
        <h2 class="text-title-lg font-bold text-primary flex items-center gap-2 border-b border-outline-variant/30 pb-3">
            <span class="material-symbols-outlined text-primary">assignment</span>
            Status Pengajuan & Surat Resmi
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($latestLayanan)
            <div class="p-4 rounded-xl border border-outline-variant/40 bg-surface-bright flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-on-surface-variant uppercase">Rekomendasi Kesbangpol</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-white" style="background-color: {{ $latestLayanan->statusMaster->warna ?? '#3A5F94' }}">
                        {{ $latestLayanan->statusMaster->nama ?? 'Menunggu' }}
                    </span>
                </div>
                <p class="text-sm font-semibold text-on-surface mt-1">{{ $latestLayanan->jenisLayanan->nama ?? 'Layanan Rekomendasi' }}</p>
                <p class="text-xs text-on-surface-variant">Lokasi/Tujuan: <strong>{{ $latestLayanan->tempat_kegiatan }}</strong></p>

                @if($latestLayanan->file_surat_keluaran)
                <a href="{{ Storage::url($latestLayanan->file_surat_keluaran) }}" target="_blank" class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[16px]">download</span> Unduh Surat Kesbangpol
                </a>
                @endif
            </div>
            @endif

            @if($latestApp)
            <div class="p-4 rounded-xl border border-secondary/30 bg-surface-container-low flex flex-col gap-2">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-bold text-secondary uppercase">Verifikasi Dinas Tujuan</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full text-white {{ $latestApp->status == 'diterima' || $latestApp->status == 'aktif' ? 'bg-green-600' : ($latestApp->status == 'ditolak' ? 'bg-red-600' : 'bg-amber-600') }}">
                        {{ ucfirst($latestApp->status) }}
                    </span>
                </div>
                <p class="text-sm font-semibold text-on-surface mt-1">{{ $latestApp->dinas->name ?? 'Dinas Tujuan' }}</p>
                <p class="text-xs text-on-surface-variant">Penempatan Bidang: <strong class="text-secondary">{{ $latestApp->bidang->name ?? 'Belum ditentukan' }}</strong></p>

                @if($latestApp->file_surat_penerimaan)
                <a href="{{ Storage::url($latestApp->file_surat_penerimaan) }}" target="_blank" class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white text-xs font-semibold rounded-lg hover:bg-green-700 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">download</span> Unduh Surat Penerimaan Dinas
                </a>
                @endif

                @if($latestApp->status == 'diterima' || $latestApp->status == 'aktif')
                <a href="{{ route('peserta.dashboard') }}" class="mt-2 inline-flex items-center justify-center gap-2 px-4 py-2 bg-secondary text-white text-xs font-semibold rounded-lg hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[16px]">how_to_reg</span> Masuk ke Halaman Absensi
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Profile Card & Form -->
    <div class="bg-surface-container-lowest rounded-xl p-6 md:p-8 shadow-[0_2px_15px_rgba(0,0,0,0.03)] border border-outline-variant/30">
        <form action="{{ route('landing.profile.update') }}" method="POST" class="flex flex-col gap-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Lengkap -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Nama Lengkap</label>
                    <input name="nama" type="text" value="{{ old('nama', $user->nama) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('nama')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Email -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Alamat Email</label>
                    <input name="email" type="email" value="{{ old('email', $user->email) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('email')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- NIK -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">NIK (Nomor Induk Kependudukan)</label>
                    <input name="nik" type="text" value="{{ old('nik', $user->nik) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('nik')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- No WhatsApp -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Nomor WhatsApp</label>
                    <input name="no_hp" type="text" value="{{ old('no_hp', $user->no_hp) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('no_hp')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Tempat Lahir -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Tempat Lahir</label>
                    <input name="tempat_lahir" type="text" value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('tempat_lahir')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Tanggal Lahir -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Tanggal Lahir</label>
                    <input name="tanggal_lahir" type="date" value="{{ old('tanggal_lahir', $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d') : '') }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('tanggal_lahir')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Asal Instansi -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Asal Kampus / Sekolah</label>
                    <input name="asal_instansi" type="text" value="{{ old('asal_instansi', $user->asal_instansi) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('asal_instansi')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Program Studi -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Program Studi / Jurusan</label>
                    <input name="program_studi" type="text" value="{{ old('program_studi', $user->program_studi) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('program_studi')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- NIM / NIS -->
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label class="text-[14px] font-semibold text-on-surface">Nomor Induk Mahasiswa (NIM) / Nomor Induk Siswa (NIS)</label>
                    <input name="nim" type="text" value="{{ old('nim', $user->nim) }}" required class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('nim')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Alamat -->
                <div class="flex flex-col gap-2 md:col-span-2">
                    <label class="text-[14px] font-semibold text-on-surface">Alamat Lengkap</label>
                    <textarea name="alamat" required rows="3" class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Divider -->
                <div class="md:col-span-2 border-t border-outline-variant/30 my-2"></div>

                <!-- Password Baru (Optional) -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Kata Sandi Baru <span class="text-on-surface-variant text-[12px] font-normal">(Kosongkan jika tidak ingin diubah)</span></label>
                    <input name="password" type="password" class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                    @error('password')<div class="text-error text-[12px] mt-1">{{ $message }}</div>@enderror
                </div>

                <!-- Konfirmasi Password -->
                <div class="flex flex-col gap-2">
                    <label class="text-[14px] font-semibold text-on-surface">Konfirmasi Kata Sandi Baru</label>
                    <input name="password_confirmation" type="password" class="bg-surface-container-lowest border border-outline-variant rounded-lg px-4 py-3 text-[16px] text-on-surface focus:border-secondary focus:outline-none input-focus-glow transition-all">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end gap-3 mt-4">
                <a href="{{ route('home') }}" class="px-6 py-3 border border-outline text-on-surface font-label-md text-label-md rounded-lg hover:bg-surface-container-low transition-colors">Batal</a>
                <button type="submit" class="px-8 py-3 bg-secondary text-on-secondary font-label-md text-label-md rounded-lg hover:bg-secondary-container hover:text-on-secondary-container transition-colors shadow-sm font-semibold">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</main>
@endsection
