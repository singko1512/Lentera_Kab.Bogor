<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $seedPassword = env('SEED_ADMIN_PASSWORD');
        if (app()->environment('production') && empty($seedPassword)) {
            throw new \RuntimeException('SEED_ADMIN_PASSWORD environment variable must be configured in production!');
        }

        $passwordPlain = $seedPassword ?: 'AdminBanggaBogor#2026!';
        $defaultPassword = Hash::make($passwordPlain);

        // 1. Setup Super Admin di tabel admins
        $superadmin = Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@dinas.com',
                'name' => 'Super Administrator',
                'password' => Hash::make('SuperAdmin#Bangga2026!'),
                'role' => 'superadmin',
                'status_akun' => 'aktif',
            ]
        );
        $this->command->info("Super Admin ID: " . $superadmin->id . " | Username: 'superadmin'");

        // Setup Administrator di tabel admins
        $admin = Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@dinas.com',
                'name' => 'Administrator Bangga Online',
                'password' => Hash::make('AdminBangga#2026!'),
                'role' => 'admin',
                'status_akun' => 'aktif',
            ]
        );
        $this->command->info("Admin ID: " . $admin->id . " | Username: 'admin'");

        // 2. Setup Akun Kesbangpol di tabel admins
        $dinasKesbangpol = Dinas::firstOrCreate(
            ['name' => 'BADAN KESATUAN BANGSA DAN POLITIK'],
            ['is_kesbangpol' => true]
        );
        $dinasKesbangpol->update(['is_kesbangpol' => true]);

        // Akun 1: Bakesbangpol - Pengajuan Layanan (Pelayanan Publik Rekomendasi)
        // Email: bakesbangpol.layanan@dinas.com (Password Unik)
        $kesbangpolLayanan = Admin::updateOrCreate(
            ['username' => 'kesbangpol'],
            [
                'email' => 'bakesbangpol.layanan@dinas.com',
                'name' => 'Bakesbangpol',
                'password' => Hash::make('BakesbangpolLayanan#2026!'),
                'role' => 'kesbangpol',
                'dinas_id' => $dinasKesbangpol->id,
                'status_akun' => 'aktif',
            ]
        );
        $this->command->info("Kesbangpol Layanan ID: " . $kesbangpolLayanan->id . " | Email: 'bakesbangpol.layanan@dinas.com'");

        // Akun 2: Bakesbangpol - Rekrutmen Internal (Instansi/Dinas)
        // Email: bakesbangpol@dinas.com (Password Unik & Berbeda dari Layanan)
        $kesbangpolInternal = Admin::updateOrCreate(
            ['username' => 'BAKESBANGPOL'],
            [
                'email' => 'bakesbangpol@dinas.com',
                'name' => 'Admin BAKESBANGPOL (Rekrutmen Internal)',
                'password' => Hash::make('BakesbangpolInternal#2026!'),
                'role' => 'dinas',
                'dinas_id' => $dinasKesbangpol->id,
                'status_akun' => 'aktif',
            ]
        );
        $this->command->info("Kesbangpol Internal ID: " . $kesbangpolInternal->id . " | Email: 'bakesbangpol@dinas.com'");

        // 3. Setup Akun Dinas (Diskominfo) di tabel admins
        $dinasDiskominfo = Dinas::firstOrCreate(
            ['name' => 'DINAS KOMUNIKASI DAN INFORMATIKA']
        );

        $dinasDiskominfoUser = Admin::updateOrCreate(
            ['email' => 'diskominfo@dinas.com'],
            [
                'username' => 'diskominfo',
                'name' => 'Admin Diskominfo',
                'password' => Hash::make('DiskominfoBangga#2026!'),
                'role' => 'dinas',
                'dinas_id' => $dinasDiskominfo->id,
                'status_akun' => 'aktif',
            ]
        );

        // Akun generic username 'dinas'
        $dinasGeneric = Admin::updateOrCreate(
            ['username' => 'dinas'],
            [
                'email' => 'dinas@dinas.com',
                'name' => 'Admin Dinas (Diskominfo)',
                'password' => Hash::make('DinasBangga#2026!'),
                'role' => 'dinas',
                'dinas_id' => $dinasDiskominfo->id,
                'status_akun' => 'aktif',
            ]
        );

        $this->command->info("Dinas ID: " . $dinasGeneric->id . " | Username: 'dinas' atau 'diskominfo'");
    }
}

