@extends('pelayanan.layouts.kesbangpol_stitch')

@section('content')
<div class="max-w-container-max mx-auto space-y-8 pb-12" x-data="dinasManager()">

    <!-- Flash Notifications -->
    @if(session('success'))
    <div class="bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] p-4 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px]">check_circle</span>
            <span class="font-medium text-sm">{{ session('success') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="text-[#2E7D32] hover:opacity-75">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2] p-4 rounded-xl flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-[24px]">error</span>
            <span class="font-medium text-sm">{{ session('error') }}</span>
        </div>
        <button type="button" @click="$el.parentElement.remove()" class="text-[#C62828] hover:opacity-75">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-[#FFEBEE] text-[#C62828] border border-[#FFCDD2] p-4 rounded-xl shadow-sm">
        <div class="flex items-center gap-2 font-bold mb-2">
            <span class="material-symbols-outlined">warning</span>
            <span>Gagal Menimpan Data:</span>
        </div>
        <ul class="list-disc list-inside text-xs space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header Section -->
    <section class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-6 md:p-8 rounded-2xl shadow-soft border border-outline-variant/30">
        <div>
            <h1 class="text-headline-md font-bold text-on-surface flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-[32px]">admin_panel_settings</span>
                Kelola Akun Dinas / Perangkat Daerah
            </h1>
            <p class="text-body-md text-on-surface-variant mt-1">
                Kelola pendaftaran akun instansi dinas, buat akun login baru, atur ulang password, dan perbarui akses login.
            </p>
        </div>

        <button @click="openCreateModal()" type="button" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-primary text-white font-label-md font-bold rounded-xl hover:bg-secondary transition-all shadow-md hover:shadow-lg cursor-pointer shrink-0">
            <span class="material-symbols-outlined text-[20px]">add_business</span>
            <span>Tambah Akun Dinas Baru</span>
        </button>
    </section>

    <!-- Stats Bento Cards -->
    <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-soft border border-outline-variant/30 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[32px]">domain</span>
            </div>
            <div>
                <p class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Total Instansi Dinas</p>
                <h3 class="text-headline-lg font-bold text-on-surface mt-0.5">{{ number_format($totalDinas, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-soft border border-outline-variant/30 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#10B981]/10 text-[#10B981] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[32px]">badge</span>
            </div>
            <div>
                <p class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Akun Login Terdaftar</p>
                <h3 class="text-headline-lg font-bold text-on-surface mt-0.5">{{ number_format($totalAkun, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-soft border border-outline-variant/30 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-[#F59E0B]/10 text-[#F59E0B] flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-[32px]">corporate_fare</span>
            </div>
            <div>
                <p class="text-caption font-semibold text-on-surface-variant uppercase tracking-wider">Total Bidang / Unit Kerja</p>
                <h3 class="text-headline-lg font-bold text-on-surface mt-0.5">{{ number_format($totalBidang, 0, ',', '.') }}</h3>
            </div>
        </div>
    </section>

    <!-- Table Section -->
    <section class="bg-white rounded-2xl shadow-soft border border-outline-variant/30 overflow-hidden">
        <!-- Search & Filter Bar -->
        <div class="p-6 border-b border-outline-variant/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <form action="{{ route('kesbangpol.dinas.index') }}" method="GET" class="flex items-center gap-3 w-full md:w-96">
                <div class="relative w-full">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama dinas atau email..." class="w-full pl-10 pr-4 py-2.5 bg-surface-container-low rounded-xl border border-outline-variant/40 text-sm focus:outline-none focus:border-primary transition-all">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-[20px]">search</span>
                </div>
                @if($search)
                <a href="{{ route('kesbangpol.dinas.index') }}" class="p-2.5 text-on-surface-variant hover:text-primary hover:bg-surface-container-low rounded-xl text-xs font-semibold shrink-0">
                    Reset
                </a>
                @endif
            </form>
            <div class="text-caption text-on-surface-variant font-medium">
                Menampilkan {{ $dinasList->firstItem() ?? 0 }} - {{ $dinasList->lastItem() ?? 0 }} dari {{ $dinasList->total() }} Dinas
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant/40 text-caption font-bold text-on-surface-variant uppercase tracking-wider">
                        <th class="p-4 pl-6">Instansi Dinas</th>
                        <th class="p-4">Username & Email Login</th>
                        <th class="p-4">Bidang / Unit Kerja</th>
                        <th class="p-4 text-center">Status Login</th>
                        <th class="p-4 pr-6 text-center">Aksi / Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30 text-body-md text-on-surface">
                    @forelse($dinasList as $dinas)
                    @php
                        $isKesbangpolDinas = (bool) $dinas->is_kesbangpol;
                        $layananAccount = $dinas->admins->where('role', 'kesbangpol')->first();
                        $internalAccount = $dinas->admins->where('role', 'dinas')->first() ?? $dinas->admins->first() ?? $dinas->users->first();
                        $accountsToShow = $isKesbangpolDinas 
                            ? collect([
                                ['role' => 'kesbangpol', 'title' => 'Layanan (Pelayanan Publik)', 'account' => $layananAccount, 'badge_bg' => 'bg-primary/10', 'badge_color' => 'text-primary'],
                                ['role' => 'dinas', 'title' => 'Dinas (Rekrutmen Internal)', 'account' => $internalAccount, 'badge_bg' => 'bg-secondary/10', 'badge_color' => 'text-secondary']
                              ])
                            : collect([
                                ['role' => 'dinas', 'title' => 'Admin Instansi', 'account' => $internalAccount, 'badge_bg' => 'bg-secondary/10', 'badge_color' => 'text-secondary']
                              ]);
                    @endphp
                    <tr class="hover:bg-primary/5 transition-colors">
                        <!-- Nama Dinas -->
                        <td class="p-4 pl-6 align-top">
                            <div class="font-bold text-on-surface text-base flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">domain</span>
                                <span>{{ $dinas->name }}</span>
                            </div>
                            @if($isKesbangpolDinas)
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-[#E0E7FF] text-[#3730A3] border border-[#C7D2FE]">
                                    <span class="material-symbols-outlined text-[13px]">verified</span>
                                    Instansi Induk Kesbangpol (2 Akun: Layanan & Dinas)
                                </span>
                            </div>
                            @endif
                            @if($dinas->alamat)
                            <div class="text-xs text-on-surface-variant mt-1.5 line-clamp-1">
                                <span class="material-symbols-outlined text-[13px] align-middle">location_on</span>
                                {{ $dinas->alamat }}
                            </div>
                            @endif
                        </td>

                        <!-- Akun Login: Username & Email -->
                        <td class="p-4 align-top">
                            <div class="space-y-3">
                                @foreach($accountsToShow as $item)
                                    @php $acc = $item['account']; @endphp
                                    <div class="p-2.5 rounded-xl border border-outline-variant/40 bg-surface-container-low/40">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ $item['badge_bg'] }} {{ $item['badge_color'] }}">
                                                {{ $item['title'] }}
                                            </span>
                                            @if($acc)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#2E7D32]">
                                                    <span class="material-symbols-outlined text-[12px]">check_circle</span>
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#E65100]">
                                                    <span class="material-symbols-outlined text-[12px]">warning</span>
                                                    Belum Dibuat
                                                </span>
                                            @endif
                                        </div>

                                        @if($acc && $acc->username)
                                        <div class="font-bold text-on-surface text-xs flex items-center gap-1.5">
                                            <span class="material-symbols-outlined text-secondary text-[15px]">badge</span>
                                            <span>Username: <code class="bg-secondary/10 text-secondary px-1.5 py-0.5 rounded font-mono font-bold">{{ $acc->username }}</code></span>
                                        </div>
                                        @endif

                                        <div class="font-semibold text-primary text-xs flex items-center gap-1.5 mt-1">
                                            <span class="material-symbols-outlined text-[14px]">mail</span>
                                            <span>{{ $acc?->email ?? ($isKesbangpolDinas && $item['role'] === 'kesbangpol' ? 'bakesbangpol.layanan@dinas.com' : ($dinas->email ?? 'Belum ada email')) }}</span>
                                        </div>

                                        <div class="text-[11px] text-on-surface-variant mt-1 flex items-center gap-1">
                                            <span class="material-symbols-outlined text-[13px] text-amber-600">lock</span>
                                            <span>Password Unik Terenkripsi</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        <!-- Total Bidang -->
                        <td class="p-4 whitespace-nowrap align-top">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-surface-container rounded-lg text-xs font-semibold text-on-surface whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px] shrink-0">corporate_fare</span>
                                <span>{{ $dinas->bidang->count() }} Bidang</span>
                            </span>
                        </td>

                        <!-- Status Account -->
                        <td class="p-4 text-center align-top">
                            @if($internalAccount || $layananAccount)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] rounded-full text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">check_circle</span>
                                    <span>Aktif</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-[#FFF3E0] text-[#E65100] border border-[#FFE0B2] rounded-full text-xs font-bold uppercase">
                                    <span class="material-symbols-outlined text-[14px]">warning</span>
                                    <span>Belum Dibuat</span>
                                </span>
                            @endif
                        </td>

                        <!-- Action Buttons -->
                        <td class="p-4 pr-6 text-center align-top">
                            <div class="flex flex-col items-center justify-center gap-2">
                                @foreach($accountsToShow as $item)
                                    @php $acc = $item['account']; @endphp
                                    <div class="flex items-center gap-1 bg-surface-container-low px-2 py-1 rounded-xl border border-outline-variant/30" title="{{ $item['title'] }}">
                                        @if($isKesbangpolDinas)
                                            <span class="text-[10px] font-bold text-on-surface-variant mr-1">{{ $item['role'] === 'kesbangpol' ? 'Layanan' : 'Dinas' }}:</span>
                                        @endif

                                        <!-- Edit Button -->
                                        <button type="button" @click="openEditModal({{ json_encode($dinas) }}, {{ json_encode($acc) }})" class="p-1.5 text-primary hover:bg-primary/10 rounded-lg transition-colors cursor-pointer" title="Edit Akun {{ $item['title'] }}">
                                            <span class="material-symbols-outlined text-[18px]">edit</span>
                                        </button>

                                        <!-- Reset Password Button -->
                                        <form action="{{ route('kesbangpol.dinas.reset-password', $dinas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset password akun {{ $item['title'] }} menjadi password acak unik baru?');" class="inline-block">
                                            @csrf
                                            @if($acc)
                                                <input type="hidden" name="admin_id" value="{{ $acc->id }}">
                                            @endif
                                            <button type="submit" class="p-1.5 text-[#F59E0B] hover:bg-[#F59E0B]/10 rounded-lg transition-colors cursor-pointer" title="Reset Password Unik">
                                                <span class="material-symbols-outlined text-[18px]">lock_reset</span>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach

                                @if(!$isKesbangpolDinas)
                                <!-- Hapus Button (Hanya untuk dinas selain Kesbangpol) -->
                                <form action="{{ route('kesbangpol.dinas.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Hapus instansi {{ $dinas->name }} beserta seluruh akun login & data bidang terkait?');" class="inline-block mt-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 text-error hover:bg-error/10 rounded-lg transition-colors cursor-pointer" title="Hapus Akun Dinas">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-on-surface-variant">
                            <span class="material-symbols-outlined text-[48px] opacity-40 mb-2">domain_disabled</span>
                            <p class="font-medium text-base">Belum ada data akun dinas terdaftar.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($dinasList->hasPages())
        <div class="p-4 border-t border-outline-variant/30 bg-surface-container-low flex justify-end">
            {{ $dinasList->links() }}
        </div>
        @endif
    </section>

    <!-- Modal Tambah Akun Dinas -->
    <div x-show="showCreateModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-6" @click.away="showCreateModal = false">
            <div class="flex items-center justify-between border-b border-outline-variant/30 pb-4">
                <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">add_business</span>
                    Tambah Akun Dinas Baru
                </h3>
                <button type="button" @click="showCreateModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form action="{{ route('kesbangpol.dinas.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Instansi / Perangkat Daerah <span class="text-error">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: DINAS PENDIDIKAN" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Username Login Akun <span class="text-error">*</span></label>
                    <input type="text" name="username" required placeholder="Contoh: DISDIK / RSUDCIAWI" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium uppercase">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Email Login (@dinas.com) <span class="text-error">*</span></label>
                    <input type="email" name="email" required placeholder="Contoh: disdik@dinas.com" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Password Login <span class="text-error">*</span></label>
                    <div class="flex items-center gap-2">
                        <input type="text" name="password" x-model="createPassword" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-mono font-bold text-primary">
                        <button type="button" @click="generateNewPassword()" class="px-3 py-2.5 rounded-xl bg-surface-container hover:bg-primary hover:text-white transition-colors text-xs font-bold flex items-center gap-1.5 shrink-0 cursor-pointer" title="Generate Password Unik Baru">
                            <span class="material-symbols-outlined text-[18px]">cached</span>
                            <span>Acak</span>
                        </button>
                    </div>
                    <span class="text-[11px] text-on-surface-variant mt-1 block">Password di-generate acak secara unik untuk keamanan akun dinas.</span>
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">No Telepon / Kontak (Opsional)</label>
                    <input type="text" name="telepon" placeholder="021-xxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Kepala Dinas (Opsional)</label>
                    <input type="text" name="nama_kepala" placeholder="Contoh: Dr. H. Fulan, M.Si" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">NIP Kepala Dinas (Opsional)</label>
                    <input type="text" name="nip_kepala" placeholder="Contoh: 19800101 200501 1 001" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Kop Surat (Gambar JPG/PNG) (Opsional)</label>
                    <input type="file" name="kop_surat" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                    <span class="text-[11px] text-on-surface-variant mt-1 block">Digunakan untuk kop surat penerimaan magang otomatis.</span>
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Alamat Kantor (Opsional)</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap instansi dinas..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" rows="2" placeholder="Deskripsi instansi atau layanan (otomatis diformat sesuai KBBI)..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                    <button type="button" @click="showCreateModal = false" class="px-5 py-2.5 rounded-xl border border-outline-variant/60 text-on-surface-variant text-sm font-bold hover:bg-surface-container-low transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-secondary transition-all shadow-md">
                        Simpan Akun Dinas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Akun Dinas -->
    <div x-show="showEditModal" class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-black/50 backdrop-blur-xs" x-cloak>
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl border border-outline-variant/30 space-y-6" @click.away="showEditModal = false">
            <div class="flex items-center justify-between border-b border-outline-variant/30 pb-4">
                <h3 class="text-title-lg font-bold text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">edit</span>
                    Edit Akun & Data Dinas
                </h3>
                <button type="button" @click="showEditModal = false" class="text-on-surface-variant hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <form :action="'/kesbangpol/dinas/' + editData.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="admin_id" x-model="editData.admin_id">
                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Instansi / Perangkat Daerah <span class="text-error">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Username Login Akun <span class="text-error">*</span></label>
                    <input type="text" name="username" x-model="editData.username" required placeholder="Contoh: DISDIK / RSUDCIAWI" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium uppercase">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Email Login (@dinas.com) <span class="text-error">*</span></label>
                    <input type="email" name="email" x-model="editData.email" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Ubah Password Baru (Kosongkan jika tidak diubah)</label>
                    <input type="text" name="password" placeholder="Isi hanya jika ingin ganti password baru" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-mono">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">No Telepon / Kontak</label>
                    <input type="text" name="telepon" x-model="editData.telepon" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Kepala Dinas</label>
                    <input type="text" name="nama_kepala" x-model="editData.nama_kepala" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">NIP Kepala Dinas</label>
                    <input type="text" name="nip_kepala" x-model="editData.nip_kepala" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Upload Kop Surat Baru (Abaikan jika tidak ingin mengubah)</label>
                    <input type="file" name="kop_surat" accept="image/*" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Alamat Kantor</label>
                    <textarea name="alamat" x-model="editData.alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Deskripsi Singkat (Opsional)</label>
                    <textarea name="deskripsi" x-model="editData.deskripsi" rows="2" placeholder="Deskripsi instansi atau layanan (otomatis diformat sesuai KBBI)..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/30">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 rounded-xl border border-outline-variant/60 text-on-surface-variant text-sm font-bold hover:bg-surface-container-low transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-primary text-white text-sm font-bold hover:bg-secondary transition-all shadow-md">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function dinasManager() {
        return {
            showCreateModal: false,
            showEditModal: false,
            createPassword: '',
            editData: {
                id: '',
                admin_id: '',
                name: '',
                username: '',
                email: '',
                telepon: '',
                alamat: '',
                deskripsi: '',
                nama_kepala: '',
                nip_kepala: '',
            },
            generatePassword(length = 12) {
                const uppers = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
                const lowers = 'abcdefghijkmnopqrstuvwxyz';
                const numbers = '23456789';
                const symbols = '@#%!*?';
                
                let pwd = [
                    uppers[Math.floor(Math.random() * uppers.length)],
                    lowers[Math.floor(Math.random() * lowers.length)],
                    numbers[Math.floor(Math.random() * numbers.length)],
                    symbols[Math.floor(Math.random() * symbols.length)]
                ];
                
                const all = uppers + lowers + numbers + symbols;
                for (let i = 4; i < length; i++) {
                    pwd.push(all[Math.floor(Math.random() * all.length)]);
                }
                
                return pwd.sort(() => Math.random() - 0.5).join('');
            },
            generateNewPassword() {
                this.createPassword = this.generatePassword();
            },
            openCreateModal() {
                this.createPassword = this.generatePassword();
                this.showCreateModal = true;
            },
            openEditModal(dinas, userAccount) {
                this.editData = {
                    id: dinas.id,
                    admin_id: userAccount ? (userAccount.id || '') : '',
                    name: dinas.name,
                    username: userAccount ? (userAccount.username || '') : '',
                    email: userAccount ? userAccount.email : (dinas.email || ''),
                    telepon: dinas.telepon || '',
                    alamat: dinas.alamat || '',
                    deskripsi: dinas.deskripsi || '',
                    nama_kepala: dinas.nama_kepala || '',
                    nip_kepala: dinas.nip_kepala || '',
                };
                this.showEditModal = true;
            }
        }
    }
</script>
@endsection
