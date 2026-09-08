<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StatusMaster;
use App\Models\JenisLayanan;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Status Master
        $statuses = [
            ['kode' => 'menunggu_verifikasi', 'nama' => 'Menunggu Verifikasi', 'warna' => '#ffc107'], // yellow
            ['kode' => 'perlu_revisi', 'nama' => 'Perlu Revisi', 'warna' => '#fd7e14'], // orange
            ['kode' => 'ditolak', 'nama' => 'Ditolak', 'warna' => '#dc3545'], // red
            ['kode' => 'disetujui', 'nama' => 'Disetujui', 'warna' => '#20c997'], // teal
            ['kode' => 'selesai', 'nama' => 'Selesai', 'warna' => '#198754'], // green
        ];

        foreach ($statuses as $status) {
            StatusMaster::firstOrCreate(['kode' => $status['kode']], $status);
        }

        // 2. Jenis Layanan
        // slug matches the blade file name in resources/views/pelayanan/landing/forms
        $layanans = [
            ['nama' => 'Rekomendasi Surat Izin Penelitian | Pengambilan Data | Wawancara | Survei', 'slug' => 'penelitian_pt', 'is_magang' => false],
            ['nama' => 'Rekomendasi Surat Izin Penelitian | Pengambilan Data | Wawancara | Survei | Pelaksanaan Kegiatan (Instansi / Lembaga)', 'slug' => 'penelitian_instansi', 'is_magang' => false],
            ['nama' => 'Rekomendasi Surat Izin KKL / PKL', 'slug' => 'kkl_mahasiswa', 'is_magang' => true],
            ['nama' => 'Rekomendasi Surat Izin KKN', 'slug' => 'kkn_mahasiswa', 'is_magang' => false],
            ['nama' => 'Rekomendasi Surat Izin PKL (Siswa Sekolah)', 'slug' => 'kkl_siswa', 'is_magang' => true],
            ['nama' => 'Rekomendasi Pelaksanaan Kegiatan', 'slug' => 'pelaksanaan_kegiatan', 'is_magang' => false],
            ['nama' => 'KHUSUS PERPANJANGAN SURAT REKOMENDASI', 'slug' => 'perpanjangan', 'is_magang' => false],
        ];

        foreach ($layanans as $layanan) {
            JenisLayanan::firstOrCreate(['slug' => $layanan['slug']], $layanan);
        }
    }
}
