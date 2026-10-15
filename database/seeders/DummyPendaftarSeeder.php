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
use Illuminate\Support\Facades\File;

class DummyPendaftarSeeder extends Seeder
{
    public function run(): void
    {
        set_time_limit(0);

        $defaultPassword = Hash::make('password123');

        // Ambil Jenis Layanan PKL / KKL Mahasiswa
        $jenisLayanan = JenisLayanan::where('slug', 'kkl_mahasiswa')->first()
            ?? JenisLayanan::first();

        // 1. Ambil 10 Dinas Teratas
        $diskominfo = Dinas::where('name', 'like', '%KOMUNIKASI%')->firstOrFail();
        $bpkad = Dinas::where('name', 'like', '%KEUANGAN%')->firstOrFail();
        $dinkes = Dinas::where('name', 'like', '%KESEHATAN%')->firstOrFail();
        $disdik = Dinas::where('name', 'like', '%PENDIDIKAN%')->firstOrFail();
        $bapperida = Dinas::where('name', 'like', '%INOVASI%')->firstOrFail();
        $dinsos = Dinas::where('name', 'like', '%SOSIAL%')->firstOrFail();
        $bkpsdm = Dinas::where('name', 'like', '%KEPEGAWAIAN%')->firstOrFail();
        $disdukcapil = Dinas::where('name', 'like', '%PENDUDUKAN%')->firstOrFail();
        $cibinong = Dinas::where('name', 'like', '%KECAMATAN CIBINONG%')->firstOrFail();
        $bojonggede = Dinas::where('name', 'like', '%KECAMATAN BOJONGGEDE%')->firstOrFail();

        // Pastikan Diskominfo memiliki lowongan / rekrutmen aktif dengan kuota melimpah
        $diskominfoBidangs = $diskominfo->bidang;
        if ($diskominfoBidangs->isNotEmpty()) {
            foreach ($diskominfoBidangs as $b) {
                Rekrutmen::firstOrCreate(
                    [
                        'dinas_id' => $diskominfo->id,
                        'bidang_id' => $b->id,
                    ],
                    [
                        'judul' => 'Magang ' . $b->name,
                        'deskripsi_persyaratan' => 'Terbuka untuk mahasiswa/siswa jurusan IT, Komunikasi, dan Administrasi.',
                        'kuota' => 25,
                        'is_active' => true,
                    ]
                );
            }
        }

        // Susun 10 Dinas Terbesar (Total: 55 pendaftar)
        $topDinasConfig = [
            ['dinas' => $diskominfo, 'count' => 30],
            ['dinas' => $bpkad, 'count' => 4],
            ['dinas' => $dinkes, 'count' => 4],
            ['dinas' => $disdik, 'count' => 3],
            ['dinas' => $bapperida, 'count' => 3],
            ['dinas' => $dinsos, 'count' => 3],
            ['dinas' => $bkpsdm, 'count' => 2],
            ['dinas' => $disdukcapil, 'count' => 2],
            ['dinas' => $cibinong, 'count' => 2],
            ['dinas' => $bojonggede, 'count' => 2],
        ];

        $topDinasIds = array_map(fn($item) => $item['dinas']->id, $topDinasConfig);

        // Ambil 45 Dinas & Kecamatan lainnya yang bukan Top 10 dan bukan Kesbangpol
        $otherDinas = Dinas::whereNotIn('id', $topDinasIds)
            ->where('is_kesbangpol', false)
            ->orderBy('name')
            ->take(45)
            ->get();

        // Siapkan rekrutmen default jika belum ada
        foreach (array_merge($topDinasConfig, $otherDinas->map(fn($d) => ['dinas' => $d, 'count' => 1])->all()) as $item) {
            $targetDinas = $item['dinas'];
            $hasRek = Rekrutmen::where('dinas_id', $targetDinas->id)->where('is_active', true)->exists();
            if (!$hasRek) {
                $firstBidang = $targetDinas->bidang->first();
                Rekrutmen::create([
                    'dinas_id' => $targetDinas->id,
                    'bidang_id' => $firstBidang?->id,
                    'judul' => 'Penerimaan Magang / PKL ' . $targetDinas->name,
                    'deskripsi_persyaratan' => 'Terbuka untuk siswa dan mahasiswa berprestasi.',
                    'kuota' => 15,
                    'is_active' => true,
                ]);
            }
        }

        // Susun daftar instansi target untuk 100 pendaftar
        $targets = [];
        foreach ($topDinasConfig as $item) {
            for ($k = 0; $k < $item['count']; $k++) {
                $targets[] = $item['dinas'];
            }
        }
        foreach ($otherDinas as $d) {
            $targets[] = $d;
        }

        // Daftar 100 Nama Lengkap
        $names = [
            'Aditya Pratama', 'Ahmad Fauzi', 'Aisyah Putri', 'Alif Ramadan', 'Amanda Rahmawati',
            'Andika Saputra', 'Anisa Fitriani', 'Arif Hidayat', 'Arya Pratama', 'Bagas Kurniawan',
            'Bima Prasetya', 'Budi Santoso', 'Cahya Wulandari', 'Cantika Dewi', 'Citra Lestari',
            'Danu Wicaksono', 'Dedi Irawan', 'Dewi Sartika', 'Dian Anggraini', 'Diki Wahyudi',
            'Dimas Anggara', 'Dwi Cahyono', 'Eka Kurniawan', 'Elsa Safitri', 'Fajar Nugraha',
            'Faisal Rahman', 'Farhan Maulana', 'Fatimah Azzahra', 'Febrianto', 'Firman Utina',
            'Galih Permana', 'Gilang Ramadhan', 'Gita Gutawa', 'Habibie Ramadhan', 'Hafiz Al-Ghifari',
            'Hana Pertiwi', 'Hendra Wijaya', 'Hesti Purwanti', 'Ilham Akbar', 'Indah Permatasari',
            'Intan Nuraini', 'Irfan Hakim', 'Joko Prasetyo', 'Jonathan Christie', 'Kartika Sari',
            'Kevin Sanjaya', 'Laila Majnun', 'Latifah Hanum', 'Lukman Hakim', 'M. Rizki Fadillah',
            'Maya Indriyani', 'Mega Silviana', 'Melati Kusuma', 'Mochamad Ilham', 'Muhammad Al-Fatih',
            'Nabila Syakieb', 'Nadya Hutagalung', 'Naufal Azhar', 'Nia Daniati', 'Nova Riyanti',
            'Nurul Hidayati', 'Oktavia Rahayu', 'Panji Petualang', 'Praditya Putra', 'Pratama Arhan',
            'Putri Handayani', 'Qori Hidayat', 'Raditya Dika', 'Rafli Ahmad', 'Rahmawati Kusuma',
            'Raihan Saputra', 'Rendi Pangalila', 'Reza Rahadian', 'Rian Firmansyah', 'Rina Nose',
            'Rinto Harahap', 'Riska Amalia', 'Rizki Febian', 'Sabrina Chairunnisa', 'Sandiaga Salahuddin',
            'Sarah Sechan', 'Satria Tama', 'Setya Novanto', 'Sinta Nuriyah', 'Siti Badriah',
            'Siti Nurhaliza', 'Sulaiman Syah', 'Syahrul Gunawan', 'Tania Putri', 'Taufik Hidayat',
            'Tegar Septian', 'Tiara Andini', 'Tri Handoko', 'Ujang Koswara', 'Vina Panduwinata',
            'Wahyu Hidayat', 'Wawan Setiawan', 'Wisnu Wardhana', 'Yanto Basna', 'Yusuf Mansur',
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

        $recordsSummary = [];

        for ($i = 1; $i <= 100; $i++) {
            $idx = $i - 1;
            $nama = $names[$idx] ?? ('Peserta Lentera ' . $i);
            $targetDinas = $targets[$idx];

            $emailNum = sprintf('%03d', $i);
            $username = 'peserta' . $emailNum;
            $email = 'peserta' . $emailNum . '@lentera.test';
            $campus = $campuses[array_rand($campuses)];
            $major = $majors[array_rand($majors)];

            // Status pembagian realistis:
            if ($i <= 30) {
                if ($i <= 18) {
                    $statusKbKode = 'menunggu_verifikasi';
                    $statusMagang = 'menunggu';
                    $statusTeks = 'Menunggu Verifikasi';
                } elseif ($i <= 26) {
                    $statusKbKode = 'disetujui';
                    $statusMagang = 'diterima';
                    $statusTeks = 'Diterima / Aktif';
                } elseif ($i <= 28) {
                    $statusKbKode = 'perlu_revisi';
                    $statusMagang = 'menunggu';
                    $statusTeks = 'Perlu Revisi Berkas';
                } else {
                    $statusKbKode = 'selesai';
                    $statusMagang = 'diterima';
                    $statusTeks = 'Selesai Magang';
                }
            } else {
                $randVal = $i % 10;
                if ($randVal < 7) {
                    $statusKbKode = 'menunggu_verifikasi';
                    $statusMagang = 'menunggu';
                    $statusTeks = 'Menunggu Verifikasi';
                } elseif ($randVal < 9) {
                    $statusKbKode = 'disetujui';
                    $statusMagang = 'diterima';
                    $statusTeks = 'Diterima / Aktif';
                } else {
                    $statusKbKode = 'perlu_revisi';
                    $statusMagang = 'menunggu';
                    $statusTeks = 'Perlu Revisi Berkas';
                }
            }

            $statusMaster = StatusMaster::where('kode', $statusKbKode)->first()
                ?? StatusMaster::find(3);

            // Tentukan bidang penempatan jika statusnya diterima
            $bidangPenempatan = null;
            if ($statusMagang === 'diterima') {
                $targetBidangs = $targetDinas->bidang;
                if ($targetBidangs->isNotEmpty()) {
                    $bidangPenempatan = $targetBidangs->random();
                }
            }

            // Ambil Rekrutmen yang cocok
            $rekrutmen = null;
            if ($bidangPenempatan) {
                $rekrutmen = Rekrutmen::where('dinas_id', $targetDinas->id)
                    ->where('bidang_id', $bidangPenempatan->id)
                    ->where('is_active', true)
                    ->first();
            }
            if (!$rekrutmen) {
                $rekrutmen = Rekrutmen::where('dinas_id', $targetDinas->id)
                    ->where('is_active', true)
                    ->first();
            }

            // 1. Buat / Update User
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'username' => $username,
                    'name' => $nama,
                    'password' => $defaultPassword,
                    'role' => ($statusMagang === 'diterima') ? 'peserta' : 'user',
                    'dinas_id' => $targetDinas->id,
                    'bidang_id' => $bidangPenempatan?->id,
                    'status_akun' => 'aktif',
                    'email_verified_at' => now(),
                    'asal_instansi' => $campus,
                    'program_studi' => $major,
                    'nim' => '2024' . sprintf('%06d', $i),
                    'nik' => '320101' . sprintf('%010d', 1000000000 + $i),
                    'no_hp' => '0812' . rand(1000, 9999) . sprintf('%04d', $i),
                    'tempat_lahir' => 'Bogor',
                    'tanggal_lahir' => '2003-05-15',
                    'alamat' => 'Jl. Tegar Beriman No. ' . $i . ', Cibinong, Kab. Bogor',
                    'tanggal_mulai_magang' => now()->subDays(rand(1, 10))->toDateString(),
                    'tanggal_selesai_magang' => now()->addMonths(3)->toDateString(),
                ]
            );

            // 2. Buat Permohonan Layanan
            $permohonan = PermohonanLayanan::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'dinas_id' => $targetDinas->id,
                ],
                [
                    'jenis_layanan_id' => $jenisLayanan->id,
                    'status_master_id' => $statusMaster->id,
                    'jenis_permohonan' => 'Individu',
                    'atas_nama' => $user->name,
                    'no_hp' => $user->no_hp,
                    'asal_instansi' => $user->asal_instansi,
                    'judul_kegiatan' => 'Praktik Kerja Lapangan / Magang Mandiri Mahasiswa pada ' . $targetDinas->name,
                    'tempat_kegiatan' => $targetDinas->name,
                    'tanggal_mulai' => now()->addDays(rand(1, 14))->toDateString(),
                    'tanggal_selesai' => now()->addMonths(3)->toDateString(),
                    'keterangan' => ($statusKbKode === 'perlu_revisi')
                        ? 'Mohon unggah kembali Surat Pengantar dari Kampus yang dibubuhi stempel basah/TTE.'
                        : 'Permohonan Magang atas nama ' . $user->name,
                    'file_ktp' => 'permohonan/dummy_ktp.pdf',
                    'file_ktm' => 'permohonan/dummy_ktm.pdf',
                    'file_surat_pengantar' => 'permohonan/dummy_surat_pengantar.pdf',
                    'file_proposal' => 'permohonan/dummy_proposal.pdf',
                ]
            );

            // 3. Buat MagangApplication
            MagangApplication::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'permohonan_layanan_id' => $permohonan->id,
                ],
                [
                    'dinas_id' => $targetDinas->id,
                    'rekrutmen_id' => $rekrutmen?->id,
                    'bidang_id' => $bidangPenempatan?->id,
                    'status' => $statusMagang,
                    'pesan_lamaran' => $permohonan->judul_kegiatan,
                    'tanggal_mulai' => $permohonan->tanggal_mulai,
                    'tanggal_selesai' => $permohonan->tanggal_selesai,
                    'berlaku_sampai' => now()->addDays(30)->toDateString(),
                    'expired_at' => now()->addDays(30),
                    'jumlah_orang' => 1,
                    'catatan_admin' => ($statusMagang === 'diterima')
                        ? 'Selamat, Anda ditempatkan pada ' . ($bidangPenempatan?->name ?? 'Bidang Terkait') . '.'
                        : (($statusKbKode === 'perlu_revisi') ? 'Harap revisi surat pengantar.' : null),
                ]
            );

            $recordsSummary[] = [
                'no' => $i,
                'nama' => $user->name,
                'email' => $user->email,
                'username' => $user->username,
                'password' => 'password123',
                'kampus' => $user->asal_instansi,
                'instansi_tujuan' => $targetDinas->name,
                'bidang' => $bidangPenempatan?->name ?? '-',
                'status' => $statusTeks,
            ];
        }

        // Tulis rangkuman 100 akun ke file Markdown
        $mdContent = "# DAFTAR 100 AKUN PENDAFTAR LOKAL LENTERA KAB. BOGOR\n\n";
        $mdContent .= "**Password semua akun:** `password123`\n\n";
        $mdContent .= "### Ringkasan Pendaftaran:\n";
        $mdContent .= "- **Total Akun:** 100 Akun\n";
        $mdContent .= "- **Peringkat 1 (Terbanyak):** Diskominfo (30 Pendaftar)\n";
        $mdContent .= "- **Peringkat 2:** BPKAD (4 Pendaftar)\n";
        $mdContent .= "- **Peringkat 3:** Dinkes (4 Pendaftar)\n";
        $mdContent .= "- **Peringkat 4-6:** Disdik, Bapperida, Dinsos (Masing-masing 3 pendaftar)\n";
        $mdContent .= "- **Peringkat 7-10:** BKPSDM, Disdukcapil, Kec. Cibinong, Kec. Bojonggede (Masing-masing 2 pendaftar)\n";
        $mdContent .= "- **Dinas & Kecamatan Lainnya:** 45 Akun (Masing-masing 1 pendaftar)\n\n";
        $mdContent .= "| No | Nama Lengkap | Email (Login) | Username | Password | Instansi Tujuan | Bidang Penempatan | Status Pengajuan |\n";
        $mdContent .= "|---|---|---|---|---|---|---|---|\n";

        foreach ($recordsSummary as $r) {
            $mdContent .= "| {$r['no']} | {$r['nama']} | `{$r['email']}` | `{$r['username']}` | `password123` | {$r['instansi_tujuan']} | {$r['bidang']} | **{$r['status']}** |\n";
        }

        File::put(base_path('DAFTAR_100_AKUN.md'), $mdContent);
        $this->command->info("Berhasil membuat 100 akun pemohon dan menyimpan daftar ke DAFTAR_100_AKUN.md");
    }
}
