<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Dinas;
use App\Models\Bidang;
use App\Models\Rekrutmen;
use App\Models\PermohonanLayanan;
use App\Models\MagangApplication;
use App\Models\JenisLayanan;
use App\Models\StatusMaster;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DummyPendaftarSeeder extends Seeder
{
    public function run(): void
    {
        set_time_limit(0);

        $defaultPassword = Hash::make('password123');

        // Bersihkan data dummy pendaftar sebelumnya agar tidak terjadi duplikasi
        $existingDummyUserIds = User::where('email', 'like', '%@lentera.test')->pluck('id');
        if ($existingDummyUserIds->isNotEmpty()) {
            MagangApplication::whereIn('user_id', $existingDummyUserIds)->delete();
            PermohonanLayanan::whereIn('user_id', $existingDummyUserIds)->delete();
            User::whereIn('id', $existingDummyUserIds)->delete();
        }

        // Ambil semua jenis layanan
        $allJenisLayanan = JenisLayanan::all();
        if ($allJenisLayanan->isEmpty()) {
            $this->command->error("Jenis layanan tidak ditemukan.");
            return;
        }

        // Ambil semua Status Master
        $statusApproved = StatusMaster::whereIn('kode', ['disetujui', 'selesai'])->get();
        $statusPending = StatusMaster::whereIn('kode', ['menunggu_verifikasi', 'perlu_revisi'])->get();
        $defaultStatus = StatusMaster::first();

        // 1. Ambil 10 Dinas Teratas
        $diskominfo = Dinas::where('name', 'like', '%KOMUNIKASI%')->first() ?? Dinas::first();
        $bpkad = Dinas::where('name', 'like', '%KEUANGAN%')->first() ?? Dinas::skip(1)->first();
        $dinkes = Dinas::where('name', 'like', '%KESEHATAN%')->first() ?? Dinas::skip(2)->first();
        $disdik = Dinas::where('name', 'like', '%PENDIDIKAN%')->first() ?? Dinas::skip(3)->first();
        $bapperida = Dinas::where('name', 'like', '%INOVASI%')->orWhere('name', 'like', '%BAPEDA%')->first() ?? Dinas::skip(4)->first();
        $dinsos = Dinas::where('name', 'like', '%SOSIAL%')->first() ?? Dinas::skip(5)->first();
        $bkpsdm = Dinas::where('name', 'like', '%KEPEGAWAIAN%')->first() ?? Dinas::skip(6)->first();
        $disdukcapil = Dinas::where('name', 'like', '%PENDUDUKAN%')->first() ?? Dinas::skip(7)->first();
        $cibinong = Dinas::where('name', 'like', '%KECAMATAN CIBINONG%')->first() ?? Dinas::skip(8)->first();
        $bojonggede = Dinas::where('name', 'like', '%KECAMATAN BOJONGGEDE%')->first() ?? Dinas::skip(9)->first();

        // Konfigurasi Pendaftar:
        // Diskominfo = 55 (paling banyak dan beda sendiri)
        // Dinas lainnya maksimal 50
        $topDinasConfig = [
            ['dinas' => $diskominfo,  'count' => 55],
            ['dinas' => $bpkad,       'count' => 48],
            ['dinas' => $dinkes,      'count' => 42],
            ['dinas' => $disdik,      'count' => 36],
            ['dinas' => $bapperida,   'count' => 30],
            ['dinas' => $dinsos,      'count' => 25],
            ['dinas' => $bkpsdm,      'count' => 20],
            ['dinas' => $disdukcapil, 'count' => 16],
            ['dinas' => $cibinong,    'count' => 12],
            ['dinas' => $bojonggede,  'count' => 8],
        ];

        $topDinasIds = array_map(fn($item) => $item['dinas']->id, $topDinasConfig);

        // Ambil semua dinas dan kecamatan lainnya (bukan kesbangpol dan bukan top 10)
        $otherDinas = Dinas::whereNotIn('id', $topDinasIds)
            ->where('is_kesbangpol', false)
            ->orderBy('name')
            ->get();

        // Pastikan setiap dinas punya rekrutmen aktif
        $allTargetDinas = array_merge(
            array_map(fn($item) => $item['dinas'], $topDinasConfig),
            $otherDinas->all()
        );

        foreach ($allTargetDinas as $targetDinas) {
            $hasRek = Rekrutmen::where('dinas_id', $targetDinas->id)->where('is_active', true)->exists();
            if (!$hasRek) {
                $firstBidang = $targetDinas->bidang->first();
                Rekrutmen::create([
                    'dinas_id' => $targetDinas->id,
                    'bidang_id' => $firstBidang?->id,
                    'judul' => 'Penerimaan Magang / PKL ' . $targetDinas->name,
                    'deskripsi_persyaratan' => 'Terbuka untuk siswa dan mahasiswa berprestasi.',
                    'kuota' => 60,
                    'is_active' => true,
                ]);
            }
        }

        // Susun target pendaftaran:
        // 1. Top 10 sesuai kuota
        $targets = [];
        foreach ($topDinasConfig as $item) {
            for ($k = 0; $k < $item['count']; $k++) {
                $targets[] = $item['dinas'];
            }
        }

        // 2. Dinas lainnya diberi 1 s.d. 3 pendaftar per dinas (maksimal 50)
        foreach ($otherDinas as $d) {
            $pendaftarCount = rand(1, 3);
            for ($k = 0; $k < $pendaftarCount; $k++) {
                $targets[] = $d;
            }
        }

        $firstNames = [
            'Aditya', 'Ahmad', 'Aisyah', 'Alif', 'Amanda', 'Andika', 'Anisa', 'Arif', 'Arya', 'Bagas',
            'Bima', 'Budi', 'Cahya', 'Cantika', 'Citra', 'Danu', 'Dedi', 'Dewi', 'Dian', 'Diki',
            'Dimas', 'Dwi', 'Eka', 'Elsa', 'Fajar', 'Faisal', 'Farhan', 'Fatimah', 'Febrianto', 'Firman',
            'Galih', 'Gilang', 'Gita', 'Habibie', 'Hafiz', 'Hana', 'Hendra', 'Hesti', 'Ilham', 'Indah',
            'Intan', 'Irfan', 'Joko', 'Jonathan', 'Kartika', 'Kevin', 'Laila', 'Latifah', 'Lukman', 'M. Rizki',
            'Maya', 'Mega', 'Melati', 'Mochamad', 'Muhammad', 'Nabila', 'Nadya', 'Naufal', 'Nia', 'Nova',
            'Nurul', 'Oktavia', 'Panji', 'Praditya', 'Pratama', 'Putri', 'Qori', 'Raditya', 'Rafli', 'Rahmawati',
            'Raihan', 'Rendi', 'Reza', 'Rian', 'Rina', 'Rinto', 'Riska', 'Rizki', 'Sabrina', 'Sandiaga',
            'Sarah', 'Satria', 'Setya', 'Sinta', 'Siti', 'Sulaiman', 'Syahrul', 'Tania', 'Taufik', 'Tegar',
            'Tiara', 'Tri', 'Ujang', 'Vina', 'Wahyu', 'Wawan', 'Wisnu', 'Yanto', 'Yusuf', 'Zahra', 'Zulham'
        ];

        $lastNames = [
            'Pratama', 'Fauzi', 'Putri', 'Ramadan', 'Rahmawati', 'Saputra', 'Fitriani', 'Hidayat', 'Kurniawan', 'Prasetya',
            'Santoso', 'Wulandari', 'Dewi', 'Lestari', 'Wicaksono', 'Irawan', 'Sartika', 'Anggraini', 'Wahyudi', 'Anggara',
            'Cahyono', 'Safitri', 'Nugraha', 'Rahman', 'Maulana', 'Azzahra', 'Permana', 'Ramadhan', 'Gutawa', 'Al-Ghifari',
            'Pertiwi', 'Wijaya', 'Purwanti', 'Akbar', 'Permatasari', 'Nuraini', 'Hakim', 'Prasetyo', 'Sari', 'Sanjaya',
            'Majnun', 'Hanum', 'Fadillah', 'Indriyani', 'Silviana', 'Kusuma', 'Al-Fatih', 'Syakieb', 'Hutagalung', 'Azhar',
            'Daniati', 'Riyanti', 'Hidayati', 'Rahayu', 'Petualang', 'Handayani', 'Dika', 'Ahmad', 'Kusuma', 'Pangalila',
            'Rahadian', 'Firmansyah', 'Nose', 'Harahap', 'Amalia', 'Febian', 'Chairunnisa', 'Salahuddin', 'Sechan', 'Tama',
            'Novanto', 'Nuriyah', 'Badriah', 'Nurhaliza', 'Syah', 'Gunawan', 'Andini', 'Handoko', 'Koswara', 'Panduwinata',
            'Setiawan', 'Wardhana', 'Basna', 'Mansur'
        ];

        $campuses = [
            'Institut Pertanian Bogor (IPB)',
            'Universitas Pakuan (UNPAK)',
            'Universitas Djuanda (UNIDA)',
            'Universitas Ibn Khaldun (UIKA)',
            'Universitas Indonesia (UI)',
            'Politeknik Negeri Jakarta (PNJ)',
            'Universitas Bina Sarana Informatika',
            'STIKOM Binaniaga Bogor',
            'Politeknik AKA Bogor',
            'SMKN 1 Cibinong',
            'SMKN 1 Bojonggede',
            'SMK Wikrama Bogor',
        ];

        $majors = [
            'S1 Teknik Informatika',
            'S1 Sistem Informasi',
            'S1 Ilmu Komputer',
            'S1 Manajemen Komunikasi',
            'S1 Ilmu Pemerintahan',
            'S1 Administrasi Publik',
            'S1 Akuntansi',
            'S1 Hukum Tata Negara',
            'S1 Desain Komunikasi Visual',
            'D3 Rekayasa Perangkat Lunak',
            'D3 Teknik Komputer Jaringan',
        ];

        $totalPendaftar = count($targets);
        $this->command->info("Membuat {$totalPendaftar} data pendaftar (Diskominfo: 55, lainnya maksimal 48)...");

        DB::beginTransaction();
        try {
            for ($i = 1; $i <= $totalPendaftar; $i++) {
                $idx = $i - 1;
                $targetDinas = $targets[$idx];

                // Nama acak yang alami
                $fName = $firstNames[array_rand($firstNames)];
                $lName = $lastNames[array_rand($lastNames)];
                $nama = "{$fName} {$lName}";

                $emailNum = sprintf('%04d', $i);
                $username = 'pendaftar_' . $emailNum;
                $email = 'pendaftar_' . $emailNum . '@lentera.test';
                $campus = $campuses[array_rand($campuses)];
                $major = $majors[array_rand($majors)];

                // Tentukan status kelulusan (sekitar 70% diterima/disetujui, 30% proses/lainnya)
                $isAccepted = (rand(1, 100) <= 70);
                if ($isAccepted) {
                    $statusObj = $statusApproved->isNotEmpty() ? $statusApproved->random() : $defaultStatus;
                    $statusMagang = 'diterima';
                    $role = 'peserta';
                } else {
                    $statusObj = $statusPending->isNotEmpty() ? $statusPending->random() : $defaultStatus;
                    $statusMagang = 'menunggu';
                    $role = 'user';
                }

                // Pilih bidang jika diterima
                $bidangPenempatan = null;
                if ($isAccepted && $targetDinas->bidang->isNotEmpty()) {
                    $bidangPenempatan = $targetDinas->bidang->random();
                }

                // Ambil rekrutmen dinas
                $rekrutmen = Rekrutmen::where('dinas_id', $targetDinas->id)
                    ->where('is_active', true)
                    ->first();

                // Pilih jenis layanan secara bervariasi
                $jenisLayananItem = $allJenisLayanan->random();

                // Tentukan tanggal pendaftaran (created_at)
                // 90% pendaftaran dibuat pada tahun 2026 (Januari s.d. Oktober 2026)
                // 10% pendaftaran pada tahun 2025
                if ($targetDinas->id === $diskominfo->id || rand(1, 10) <= 9) {
                    $month = rand(1, 10);
                    $day = rand(1, 28);
                    $hour = rand(8, 16);
                    $minute = rand(0, 59);
                    $createdAt = Carbon::create(2026, $month, $day, $hour, $minute, 0);
                } else {
                    $month = rand(1, 12);
                    $day = rand(1, 28);
                    $hour = rand(8, 16);
                    $minute = rand(0, 59);
                    $createdAt = Carbon::create(2025, $month, $day, $hour, $minute, 0);
                }

                // 1. Buat User
                $user = User::create([
                    'username' => $username,
                    'email' => $email,
                    'name' => $nama,
                    'password' => $defaultPassword,
                    'role' => $role,
                    'dinas_id' => $targetDinas->id,
                    'bidang_id' => $bidangPenempatan?->id,
                    'status_akun' => 'aktif',
                    'email_verified_at' => $createdAt,
                    'asal_instansi' => $campus,
                    'program_studi' => $major,
                    'nim' => '2024' . sprintf('%06d', $i),
                    'nik' => '320101' . sprintf('%010d', 1000000000 + $i),
                    'no_hp' => '0812' . rand(1000, 9999) . sprintf('%04d', $i % 10000),
                    'tempat_lahir' => 'Bogor',
                    'tanggal_lahir' => '2003-05-15',
                    'alamat' => 'Jl. Tegar Beriman No. ' . $i . ', Cibinong, Kab. Bogor',
                    'tanggal_mulai_magang' => $createdAt->copy()->addDays(rand(1, 10))->toDateString(),
                    'tanggal_selesai_magang' => $createdAt->copy()->addMonths(3)->toDateString(),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 2. Buat PermohonanLayanan
                $permohonan = PermohonanLayanan::create([
                    'user_id' => $user->id,
                    'dinas_id' => $targetDinas->id,
                    'jenis_layanan_id' => $jenisLayananItem->id,
                    'status_master_id' => $statusObj->id,
                    'jenis_permohonan' => 'Individu',
                    'atas_nama' => $user->name,
                    'no_hp' => $user->no_hp,
                    'asal_instansi' => $user->asal_instansi,
                    'judul_kegiatan' => 'Permohonan Layanan ' . $jenisLayananItem->nama . ' pada ' . $targetDinas->name,
                    'tempat_kegiatan' => $targetDinas->name,
                    'tanggal_mulai' => $user->tanggal_mulai_magang,
                    'tanggal_selesai' => $user->tanggal_selesai_magang,
                    'keterangan' => 'Pengajuan berkas atas nama ' . $user->name,
                    'file_ktp' => 'permohonan/dummy_ktp.pdf',
                    'file_ktm' => 'permohonan/dummy_ktm.pdf',
                    'file_surat_pengantar' => 'permohonan/dummy_surat_pengantar.pdf',
                    'file_proposal' => 'permohonan/dummy_proposal.pdf',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                // 3. Buat MagangApplication
                MagangApplication::create([
                    'user_id' => $user->id,
                    'permohonan_layanan_id' => $permohonan->id,
                    'dinas_id' => $targetDinas->id,
                    'rekrutmen_id' => $rekrutmen?->id,
                    'bidang_id' => $bidangPenempatan?->id,
                    'status' => $statusMagang,
                    'pesan_lamaran' => $permohonan->judul_kegiatan,
                    'tanggal_mulai' => $permohonan->tanggal_mulai,
                    'tanggal_selesai' => $permohonan->tanggal_selesai,
                    'berlaku_sampai' => $createdAt->copy()->addDays(30)->toDateString(),
                    'expired_at' => $createdAt->copy()->addDays(30),
                    'jumlah_orang' => 1,
                    'catatan_admin' => $isAccepted
                        ? 'Diterima pada ' . ($bidangPenempatan?->name ?? 'Bidang Terkait') . '.'
                        : null,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            DB::commit();
            $this->command->info("Selesai! Berhasil membuat {$totalPendaftar} data pendaftar. Diskominfo: 55 data (terbanyak), dinas lain maksimal 48 data.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Gagal melakukan seeding: " . $e->getMessage());
        }
    }
}
