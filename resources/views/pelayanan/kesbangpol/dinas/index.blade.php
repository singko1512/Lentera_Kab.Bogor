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
                        <th class="p-4">Email Login Akun</th>
                        <th class="p-4">Bidang / Unit Kerja</th>
                        <th class="p-4 text-center">Status Login</th>
                        <th class="p-4 pr-6 text-center">Aksi / Pengelolaan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/30 text-body-md text-on-surface">
                    @forelse($dinasList as $dinas)
                    @php
                        $userAccount = $dinas->users->first();
                    @endphp
                    <tr class="hover:bg-primary/5 transition-colors">
                        <!-- Nama Dinas -->
                        <td class="p-4 pl-6">
                            <div class="font-bold text-on-surface text-base flex items-center gap-2">
                                <span class="material-symbols-outlined text-primary text-[20px]">domain</span>
                                <span>{{ $dinas->name }}</span>
                            </div>
                            @if($dinas->alamat)
                            <div class="text-xs text-on-surface-variant mt-0.5 line-clamp-1">
                                <span class="material-symbols-outlined text-[13px] align-middle">location_on</span>
                                {{ $dinas->alamat }}
                            </div>
                            @endif
                        </td>

                        <!-- Email Login -->
                        <td class="p-4">
                            <div class="font-semibold text-primary text-sm flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px]">mail</span>
                                <span>{{ $userAccount->email ?? ($dinas->email ?? 'Belum ada email') }}</span>
                            </div>
                            <div class="text-[11px] text-on-surface-variant mt-0.5">
                                Password Default: <code class="bg-surface-container px-1.5 py-0.5 rounded font-mono text-[11px]">password123</code>
                            </div>
                        </td>

                        <!-- Total Bidang -->
                        <td class="p-4 whitespace-nowrap">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-surface-container rounded-lg text-xs font-semibold text-on-surface whitespace-nowrap">
                                <span class="material-symbols-outlined text-[16px] shrink-0">corporate_fare</span>
                                <span>{{ $dinas->bidang->count() }} Bidang</span>
                            </span>
                        </td>

                        <!-- Status Account -->
                        <td class="p-4 text-center">
                            @if($userAccount)
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
                        <td class="p-4 pr-6 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Edit Button -->
                                <button type="button" @click="openEditModal({{ json_encode($dinas) }}, {{ json_encode($userAccount) }})" class="p-2 text-primary hover:bg-primary/10 rounded-lg transition-colors" title="Edit Akun & Data Dinas">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>

                                <!-- Reset Password Button -->
                                <form action="{{ route('kesbangpol.dinas.reset-password', $dinas->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-reset password akun {{ $dinas->name }} menjadi password123?');" class="inline-block">
                                    @csrf
                                    <button type="submit" class="p-2 text-[#F59E0B] hover:bg-[#F59E0B]/10 rounded-lg transition-colors" title="Reset Password ke 'password123'">
                                        <span class="material-symbols-outlined text-[20px]">lock_reset</span>
                                    </button>
                                </form>

                                <!-- Hapus Button -->
                                <form action="{{ route('kesbangpol.dinas.destroy', $dinas->id) }}" method="POST" onsubmit="return confirm('Hapus instansi {{ $dinas->name }} beserta seluruh akun login & data bidang terkait?');" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-error hover:bg-error/10 rounded-lg transition-colors" title="Hapus Akun Dinas">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
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

            <form action="{{ route('kesbangpol.dinas.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Instansi / Perangkat Daerah <span class="text-error">*</span></label>
                    <input type="text" name="name" required placeholder="Contoh: DINAS PENDIDIKAN" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Email Login (@dinas.com) <span class="text-error">*</span></label>
                    <input type="email" name="email" required placeholder="Contoh: dinas_pendidikan@dinas.com" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Password Login <span class="text-error">*</span></label>
                    <input type="text" name="password" required value="password123" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-mono">
                    <span class="text-[11px] text-on-surface-variant mt-1 block">Default password diset `password123`. Bisa disesuaikan jika perlu.</span>
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">No Telepon / Kontak (Opsional)</label>
                    <input type="text" name="telepon" placeholder="021-xxxxxxxx" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
                </div>

                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Alamat Kantor (Opsional)</label>
                    <textarea name="alamat" rows="2" placeholder="Alamat lengkap instansi dinas..." class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
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

            <form :action="'/kesbangpol/dinas/' + editData.id" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Nama Instansi / Perangkat Daerah <span class="text-error">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium">
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
                    <label class="block text-caption font-bold text-on-surface-variant uppercase mb-1">Alamat Kantor</label>
                    <textarea name="alamat" x-model="editData.alamat" rows="2" class="w-full px-4 py-2.5 rounded-xl border border-outline-variant/60 focus:border-primary focus:ring focus:ring-primary/20 text-sm font-medium"></textarea>
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
            editData: {
                id: '',
                name: '',
                email: '',
                telepon: '',
                alamat: '',
            },
            openCreateModal() {
                this.showCreateModal = true;
            },
            openEditModal(dinas, userAccount) {
                this.editData = {
                    id: dinas.id,
                    name: dinas.name,
                    email: userAccount ? userAccount.email : (dinas.email || ''),
                    telepon: dinas.telepon || '',
                    alamat: dinas.alamat || '',
                };
                this.showEditModal = true;
            }
        }
    }
</script>
@endsection
