<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ExcelDinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mencegah error "Maximum execution time of 30 seconds exceeded"
        set_time_limit(0); 

        // Melakukan hashing password HANYA SEKALI untuk semua akun (sangat mempercepat proses seeder)
        $defaultPassword = Hash::make('password123');

        $jsonPath = database_path('data/dinas_bidang.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error("File dinas_bidang.json tidak ditemukan!");
            return;
        }

        $data = json_decode(file_get_contents($jsonPath), true);

        foreach ($data as $item) {
            $dinasName = $item['nama'];
            
            // Buat atau cari Dinas
            $dinas = Dinas::firstOrCreate(['name' => $dinasName]);
            
            // Buat Akun Dinas
            $dinasEmail = strtolower(Str::slug($dinasName, '_')) . '@dinas.com';
            User::firstOrCreate(
                ['email' => $dinasEmail],
                [
                    'name' => 'Admin ' . $dinasName,
                    'password' => $defaultPassword,
                    'role' => 'dinas',
                    'dinas_id' => $dinas->id,
                ]
            );

            // Loop untuk Bidang
            foreach ($item['bidangs'] as $bidangName) {
                if (empty($bidangName)) continue;
                
                // Buat atau cari Bidang
                $bidang = Bidang::firstOrCreate([
                    'dinas_id' => $dinas->id,
                    'name' => $bidangName
                ]);

                // Buat Akun Bidang (opsional, jika diperlukan akun per bidang)
                // Sebaiknya dibuat juga agar bidang bisa login untuk memverifikasi jurnal
                $cleanD = str_replace(['DINAS ', 'BADAN ', 'KECAMATAN ', 'RUMAH SAKIT UMUM DAERAH ', 'SEKRETARIAT '], '', strtoupper($dinasName));
                $dCode = implode('_', array_slice(array_filter(explode(' ', Str::slug($cleanD, ' '))), 0, 3));
                if (str_contains(strtoupper($dinasName), 'KECAMATAN')) $dCode = 'kec_' . $dCode;
                elseif (str_contains(strtoupper($dinasName), 'RUMAH SAKIT')) $dCode = 'rsud_' . $dCode;

                $cleanB = str_replace(['BIDANG ', 'BAGIAN '], '', strtoupper($bidangName));
                $bCode = implode('_', array_slice(array_filter(explode(' ', Str::slug($cleanB, ' '))), 0, 3));

                $username = strtolower($bCode . '_' . $dCode);
                $bidangEmail = strtolower($bCode . '.' . $dCode . '@bidang.com');

                User::firstOrCreate(
                    ['email' => $bidangEmail],
                    [
                        'name' => 'Admin ' . $bidangName . ' (' . $dinasName . ')',
                        'username' => $username,
                        'password' => $defaultPassword,
                        'role' => 'bidang',
                        'dinas_id' => $dinas->id,
                        'bidang_id' => $bidang->id,
                    ]
                );
            }
        }

        $this->command->info("Data Dinas, Bidang, dan Akun berhasil di-seed!");
    }
}
