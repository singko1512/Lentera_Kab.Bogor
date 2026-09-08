<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\Bidang;
use Illuminate\Support\Facades\File;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get(database_path('seeders/dinas_bidang.json'));
        $data = json_decode($json, true);

        // Track created dinas to avoid unnecessary queries
        $dinasCache = [];

        foreach ($data as $row) {
            $instansiName = $row['instansi'];
            $bidangName = $row['unit_kerja'];

            // Jika belum ada di cache, cari atau buat
            if (!isset($dinasCache[$instansiName])) {
                $isKesbangpol = stripos($instansiName, 'KESATUAN BANGSA DAN POLITIK') !== false;

                $dinas = Dinas::firstOrCreate(
                    ['name' => $instansiName],
                    ['is_kesbangpol' => $isKesbangpol]
                );
                
                $dinasCache[$instansiName] = $dinas->id;
            }

            // Create bidang for this dinas
            Bidang::firstOrCreate([
                'dinas_id' => $dinasCache[$instansiName],
                'name' => $bidangName
            ]);
        }
    }
}
