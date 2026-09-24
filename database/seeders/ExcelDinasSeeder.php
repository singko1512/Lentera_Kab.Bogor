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
    public function run(): void
    {
        set_time_limit(0);

        // Melakukan hashing password HANYA SEKALI untuk semua akun (sangat mempercepat proses seeder)
        $defaultPassword = Hash::make('Tegarberiman');

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
            if ($dinasName === 'BADAN KESATUAN BANGSA DAN POLITIK') {
                $dinas->update(['is_kesbangpol' => true]);
            }

            // Buat Akun Dinas
            $shortName = $this->getDinasAcronym($dinasName);
            $dinasEmail = $shortName . '@dinas.com';

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
                if (empty($bidangName))
                    continue;

                // Buat atau cari Bidang
                Bidang::firstOrCreate([
                    'dinas_id' => $dinas->id,
                    'name' => $bidangName
                ]);
            }
        }

        $this->command->info("Data Dinas, Bidang, dan Akun berhasil di-seed dengan email singkatan (misal: diskominfo@dinas.com)!");
    }

    private function getDinasAcronym($name)
    {
        $nameUpper = strtoupper(trim($name));
        $custom = [
            'DINAS KOMUNIKASI DAN INFORMATIKA' => 'diskominfo',
            'BADAN KESATUAN BANGSA DAN POLITIK' => 'kesbangpol',
            'BADAN PERENCANAAN PEMBANGUNAN DAERAH, PENELITIAN DAN PENGEMBANGAN' => 'bappedalitbang',
            'BADAN PENGELOLAAN PENDAPATAN DAERAH' => 'bappenda',
            'BADAN KEPEGAWAIAN DAN PENGEMBANGAN SUMBER DAYA MANUSIA' => 'bkpsdm',
            'BADAN PENGELOLAAN KEUANGAN DAN ASET DAERAH' => 'bpkad',
            'BADAN PENANGGULANGAN BENCANA DAERAH' => 'bpbd',
            'DINAS PENDIDIKAN' => 'disdik',
            'DINAS KESEHATAN' => 'dinkes',
            'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG' => 'dpupr',
            'DINAS PERUMAHAN, KAWASAN PERMUKIMAN DAN PERTANAHAN' => 'dpkpp',
            'DINAS SOSIAL' => 'dinsos',
            'DINAS TENAGA KERJA' => 'disnaker',
            'DINAS PEMBERDAYAAN PEREMPUAN DAN PERLINDUNGAN ANAK, PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA' => 'dp3ap2kb',
            'DINAS KETAHANAN PANGAN' => 'dkp',
            'DINAS LINGKUNGAN HIDUP' => 'dlh',
            'DINAS KEPENDUDUKAN DAN PENCATATAN SIPIL' => 'disdukcapil',
            'DINAS PEMBERDAYAAN MASYARAKAT DAN DESA' => 'dpmd',
            'DINAS PERHUBUNGAN' => 'dishub',
            'DINAS KOPERASI, USAHA KECIL DAN MENENGAH' => 'diskopukm',
            'DINAS PENANAMAN MODAL DAN PELAYANAN TERPADU SATU PINTU' => 'dpmptsp',
            'DINAS KEPEMUDAAN DAN OLAHRAGA' => 'dispora',
            'DINAS KEBUDAYAAN DAN PARIWISATA' => 'disbudpar',
            'DINAS PERPUSTAKAAN DAN KEARSIPAN' => 'dispusip',
            'DINAS PERIKANAN DAN PETERNAKAN' => 'diskannak',
            'DINAS TANAMAN PANGAN, HORTIKULTURA DAN PERKEBUNAN' => 'distanhorbun',
            'DINAS PERDAGANGAN DAN PERINDUSTRIAN' => 'disdagin',
            'DINAS PEMADAM KEBAKARAN' => 'damkar',
            'SATUAN POLISI PAMONG PRAJA' => 'satpolpp',
            'INSPEKTORAT DAERAH' => 'inspektorat',
            'SEKRETARIAT DAERAH' => 'setda',
            'SEKRETARIAT DPRD' => 'setwan',
        ];

        if (isset($custom[$nameUpper])) {
            return $custom[$nameUpper];
        }

        $clean = str_replace(['DINAS ', 'BADAN ', 'KECAMATAN ', 'RUMAH SAKIT UMUM DAERAH ', 'SEKRETARIAT '], '', $nameUpper);
        $words = array_filter(explode(' ', Str::slug($clean, ' ')));

        if (str_starts_with($nameUpper, 'KECAMATAN'))
            return 'kec_' . implode('', array_slice($words, 0, 2));
        if (str_starts_with($nameUpper, 'RUMAH SAKIT'))
            return 'rsud_' . implode('', array_slice($words, 0, 2));

        $acronym = '';
        foreach (array_slice($words, 0, 4) as $w) {
            $acronym .= substr($w, 0, 1);
        }
        if (str_starts_with($nameUpper, 'DINAS'))
            $acronym = 'dis' . $acronym;
        elseif (str_starts_with($nameUpper, 'BADAN'))
            $acronym = 'b' . $acronym;
        else
            $acronym = implode('', array_slice($words, 0, 3));

        return strtolower($acronym);
    }
}
