<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\Admin;
use App\Models\Bidang;
use Illuminate\Support\Facades\Hash;

class SyncDinasGambarSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = Hash::make('Tegarberiman');

        $list = [
            ['name' => 'BAKESBANGPOL', 'username' => 'BAKESBANGPOL', 'old_match' => '%KESATUAN BANGSA%'],
            ['name' => 'BAPPENDA', 'username' => 'BAPPENDA', 'old_match' => '%PENDAPATAN DAERAH%'],
            ['name' => 'BAPPERIDA', 'username' => 'BAPPERIDA', 'old_match' => '%INOVASI DAERAH%'],
            ['name' => 'BKPSDM', 'username' => 'BKPSDM', 'old_match' => '%KEPEGAWAIAN%'],
            ['name' => 'BPBD', 'username' => 'BPBD', 'old_match' => '%BENCANA%'],
            ['name' => 'BPKAD', 'username' => 'BPKAD', 'old_match' => '%KEUANGAN DAN ASET%'],
            ['name' => 'DAMKAR', 'username' => 'DAMKAR', 'old_match' => '%PEMADAM%'],
            ['name' => 'DAPD', 'username' => 'DAPD', 'old_match' => '%ARSIP DAN PERPUSTAKAAN%'],
            ['name' => 'DINKES', 'username' => 'DINKES', 'old_match' => '%KESEHATAN%'],
            ['name' => 'DINSOS', 'username' => 'DINSOS', 'old_match' => '%SOSIAL%'],
            ['name' => 'DISBUD', 'username' => 'DISBUD', 'old_match' => null],
            ['name' => 'DISDAGIN', 'username' => 'DISDAGIN', 'old_match' => '%PERDAGANGAN%'],
            ['name' => 'DISDIK', 'username' => 'DISDIK', 'old_match' => '%PENDIDIKAN%'],
            ['name' => 'DISDUKCAPIL', 'username' => 'DISDUKCAPIL', 'old_match' => '%KEPENDUDUKAN%'],
            ['name' => 'DISHUB', 'username' => 'DISHUB', 'old_match' => '%PERHUBUNGAN%'],
            ['name' => 'DISKANAK', 'username' => 'DISKANAK', 'old_match' => '%PERIKANAN%'],
            ['name' => 'DISKOMINFO', 'username' => 'DISKOMINFO', 'old_match' => '%KOMUNIKASI%'],
            ['name' => 'DISKOPUKM', 'username' => 'DISKOPUKM', 'old_match' => '%KOPERASI%'],
            ['name' => 'DISNAKER', 'username' => 'DISNAKER', 'old_match' => '%TENAGA KERJA%'],
            ['name' => 'DISPAREKRAF', 'username' => 'DISPAREKRAF', 'old_match' => '%PARIWISATA%'],
            ['name' => 'DISPORA', 'username' => 'DISPORA', 'old_match' => '%PEMUDA%'],
            ['name' => 'DISTANHORBUN', 'username' => 'DISTANHORBUN', 'old_match' => '%TANAMAN PANGAN%'],
            ['name' => 'DKP', 'username' => 'DKP', 'old_match' => '%KETAHANAN PANGAN%'],
            ['name' => 'DLH', 'username' => 'DLH', 'old_match' => '%LINGKUNGAN HIDUP%'],
            ['name' => 'DP3AP2KB', 'username' => 'DP3AP2KB', 'old_match' => '%PEREMPUAN%'],
            ['name' => 'DPKP', 'username' => 'DPKP', 'old_match' => '%KAWASAN PERMUKIMAN%'],
            ['name' => 'DPMD', 'username' => 'DPMD', 'old_match' => '%MASYARAKAT DAN DESA%'],
            ['name' => 'DPMPTSP', 'username' => 'DPMPTSP', 'old_match' => '%PENANAMAN MODAL%'],
            ['name' => 'DPTR', 'username' => 'DPTR', 'old_match' => '%PERTANAHAN DAN TATA RUANG%'],
            ['name' => 'DPU', 'username' => 'DPU', 'old_match' => '%PEKERJAAN UMUM%'],
            ['name' => 'INSPEKTORAT', 'username' => 'INSPEKTORAT', 'old_match' => '%INSPEKTORAT%'],
            ['name' => 'RSUD CIAWI', 'username' => 'RSUDCIAWI', 'old_match' => '%BAKTI PAJAJARAN%'],
            ['name' => 'RSUD CIBINONG', 'username' => 'RSUDCIBINONG', 'old_match' => '%IDHAM CHALID%'],
            ['name' => 'RSUD CILEUNGSI', 'username' => 'RSUDCILEUNGSI', 'old_match' => '%MOH. NOH NUR%'],
            ['name' => 'RSUD LEUWILIANG', 'username' => 'RSUDLEUWILIANG', 'old_match' => '%RH. SATIBI%'],
            ['name' => 'SATPOL PP', 'username' => 'SATPOLPP', 'old_match' => '%POLISI PAMONG PRAJA%'],
            ['name' => 'SETDA', 'username' => 'SETDA', 'old_match' => '%SEKRETARIAT DAERAH%'],
            ['name' => 'SETWAN', 'username' => 'SETWAN', 'old_match' => '%SEKRETARIAT DPRD%'],
            ['name' => 'KECAMATAN BABAKAN MADANG', 'username' => 'KECAMATAN BABAKAN MADANG', 'old_match' => '%BABAKAN MADANG%'],
            ['name' => 'KECAMATAN BOJONG GEDE', 'username' => 'KECAMATAN BOJONG GEDE', 'old_match' => '%BOJONG%'],
            ['name' => 'KECAMATAN CARINGIN', 'username' => 'KECAMATAN CARINGIN', 'old_match' => null],
            ['name' => 'KECAMATAN CARIU', 'username' => 'KECAMATAN CARIU', 'old_match' => '%CARIU%'],
            ['name' => 'KECAMATAN CIAMPEA', 'username' => 'KECAMATAN CIAMPEA', 'old_match' => '%CIAMPEA%'],
            ['name' => 'KECAMATAN CIAWI', 'username' => 'KECAMATAN CIAWI', 'old_match' => 'KECAMATAN CIAWI'],
            ['name' => 'KECAMATAN CIBINONG', 'username' => 'KECAMATAN CIBINONG', 'old_match' => 'KECAMATAN CIBINONG'],
            ['name' => 'KECAMATAN CIBUNGBULANG', 'username' => 'KECAMATAN CIBUNGBULANG', 'old_match' => '%CIBUNGBULANG%'],
            ['name' => 'KECAMATAN CIGOMBONG', 'username' => 'KECAMATAN CIGOMBONG', 'old_match' => '%CIGOMBONG%'],
            ['name' => 'KECAMATAN CIGUDEG', 'username' => 'KECAMATAN CIGUDEG', 'old_match' => '%CIGUDEG%'],
            ['name' => 'KECAMATAN CIJERUK', 'username' => 'KECAMATAN CIJERUK', 'old_match' => '%CIJERUK%'],
            ['name' => 'KECAMATAN CILEUNGSI', 'username' => 'KECAMATAN CILEUNGSI', 'old_match' => 'KECAMATAN CILEUNGSI'],
            ['name' => 'KECAMATAN CIOMAS', 'username' => 'KECAMATAN CIOMAS', 'old_match' => '%CIOMAS%'],
            ['name' => 'KECAMATAN CISARUA', 'username' => 'KECAMATAN CISARUA', 'old_match' => '%CISARUA%'],
            ['name' => 'KECAMATAN CISEENG', 'username' => 'KECAMATAN CISEENG', 'old_match' => '%CISEENG%'],
            ['name' => 'KECAMATAN CITEUREUP', 'username' => 'KECAMATAN CITEUREUP', 'old_match' => '%CITEUREUP%'],
            ['name' => 'KECAMATAN DRAMAGA', 'username' => 'KECAMATAN DRAMAGA', 'old_match' => '%DRAMAGA%'],
            ['name' => 'KECAMATAN GUNUNG PUTRI', 'username' => 'KECAMATAN GUNUNG PUTRI', 'old_match' => '%GUNUNG PUTRI%'],
            ['name' => 'KECAMATAN GUNUNG SINDUR', 'username' => 'KECAMATAN GUNUNG SINDUR', 'old_match' => '%GUNUNG SINDUR%'],
            ['name' => 'KECAMATAN JASINGA', 'username' => 'KECAMATAN JASINGA', 'old_match' => '%JASINGA%'],
            ['name' => 'KECAMATAN JONGGOL', 'username' => 'KECAMATAN JONGGOL', 'old_match' => '%JONGGOL%'],
            ['name' => 'KECAMATAN KEMANG', 'username' => 'KECAMATAN KEMANG', 'old_match' => '%KEMANG%'],
            ['name' => 'KECAMATAN KLAPANUNGGAL', 'username' => 'KECAMATAN KLAPANUNGGAL', 'old_match' => '%KLAPANUNGGAL%'],
            ['name' => 'KECAMATAN LEUWILIANG', 'username' => 'KECAMATAN LEUWILIANG', 'old_match' => 'KECAMATAN LEUWILIANG'],
            ['name' => 'KECAMATAN LEUWISADENG', 'username' => 'KECAMATAN LEUWISADENG', 'old_match' => '%LEUWISADENG%'],
            ['name' => 'KECAMATAN MEGAMENDUNG', 'username' => 'KECAMATAN MEGAMENDUNG', 'old_match' => null],
            ['name' => 'KECAMATAN NANGGUNG', 'username' => 'KECAMATAN NANGGUNG', 'old_match' => '%NANGGUNG%'],
            ['name' => 'KECAMATAN PAMIJAHAN', 'username' => 'KECAMATAN PAMIJAHAN', 'old_match' => null],
            ['name' => 'KECAMATAN PARUNG', 'username' => 'KECAMATAN PARUNG', 'old_match' => 'KECAMATAN PARUNG'],
            ['name' => 'KECAMATAN PARUNG PANJANG', 'username' => 'KECAMATAN PARUNG PANJANG', 'old_match' => '%PARUNG PANJANG%'],
            ['name' => 'KECAMATAN RANCABUNGUR', 'username' => 'KECAMATAN RANCABUNGUR', 'old_match' => '%RANCABUNGUR%'],
            ['name' => 'KECAMATAN RUMPIN', 'username' => 'KECAMATAN RUMPIN', 'old_match' => '%RUMPIN%'],
            ['name' => 'KECAMATAN SUKAJAYA', 'username' => 'KECAMATAN SUKAJAYA', 'old_match' => '%SUKAJAYA%'],
            ['name' => 'KECAMATAN SUKAMAKMUR', 'username' => 'KECAMATAN SUKAMAKMUR', 'old_match' => '%SUKAMAKMUR%'],
            ['name' => 'KECAMATAN SUKARAJA', 'username' => 'KECAMATAN SUKARAJA', 'old_match' => '%SUKARAJA%'],
            ['name' => 'KECAMATAN TAJURHALANG', 'username' => 'KECAMATAN TAJURHALANG', 'old_match' => '%TAJURHALANG%'],
            ['name' => 'KECAMATAN TAMANSARI', 'username' => 'KECAMATAN TAMANSARI', 'old_match' => '%TAMANSARI%'],
            ['name' => 'KECAMATAN TANJUNGSARI', 'username' => 'KECAMATAN TANJUNGSARI', 'old_match' => '%TANJUNGSARI%'],
            ['name' => 'KECAMATAN TENJO', 'username' => 'KECAMATAN TENJO', 'old_match' => 'KECAMATAN TENJO'],
            ['name' => 'KECAMATAN TENJOLAYA', 'username' => 'KECAMATAN TENJOLAYA', 'old_match' => '%TENJOLAYA%'],
        ];

        foreach ($list as $item) {
            $dinas = null;
            if ($item['old_match']) {
                $dinas = Dinas::where('name', 'LIKE', $item['old_match'])->first();
            }

            if (!$dinas) {
                $dinas = Dinas::where('name', $item['name'])->first();
            }

            if (!$dinas) {
                $dinas = Dinas::create([
                    'name' => $item['name'],
                    'status_magang' => 'otomatis',
                ]);
            } else {
                $dinas->update([
                    'name' => $item['name'],
                ]);
            }

            if ($item['name'] === 'BAKESBANGPOL') {
                $dinas->update(['is_kesbangpol' => true]);
            }

            // Buat bidang default Sekretariat jika belum punya bidang
            if ($dinas->bidang()->count() === 0) {
                Bidang::create([
                    'dinas_id' => $dinas->id,
                    'name' => 'SEKRETARIAT',
                ]);
            }

            // Generate Email yang rapi
            $emailSlug = strtolower(str_replace(' ', '', $item['username']));
            if (str_starts_with($item['name'], 'KECAMATAN ')) {
                $kecName = strtolower(str_replace('KECAMATAN ', '', $item['name']));
                $emailSlug = 'kec_' . str_replace(' ', '', $kecName);
            }
            $email = $emailSlug . '@dinas.com';

            // Sinkronkan akun Admin Dinas
            $admin = Admin::where('dinas_id', $dinas->id)->first();
            if (!$admin) {
                $admin = Admin::where('email', $email)->orWhere('username', $item['username'])->first();
            }

            if ($admin) {
                $admin->update([
                    'name' => 'Admin ' . $item['name'],
                    'username' => $item['username'],
                    'email' => $email,
                    'role' => 'dinas',
                    'status_akun' => 'aktif',
                    'dinas_id' => $dinas->id,
                    'password' => $defaultPassword,
                ]);
            } else {
                Admin::create([
                    'name' => 'Admin ' . $item['name'],
                    'username' => $item['username'],
                    'email' => $email,
                    'role' => 'dinas',
                    'status_akun' => 'aktif',
                    'dinas_id' => $dinas->id,
                    'password' => $defaultPassword,
                ]);
            }
        }

        $this->command->info('Berhasil menyinkronkan seluruh 78 Akun Dinas sesuai gambar!');
    }
}
