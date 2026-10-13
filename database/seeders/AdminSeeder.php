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

        $passwordPlain = $seedPassword ?: 'AdminLenteraBogor#2026!';
        $defaultPassword = Hash::make($passwordPlain);

        // 1. Setup Super Admin di tabel admins
        $superadmin = Admin::updateOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@lentera.com',
                'name' => 'Super Admin Lentera',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'status_akun' => 'aktif',
            ]
        );
        $this->command->info("Super Admin ID: " . $superadmin->id . " | Username: 'superadmin'");

        // Setup Administrator di tabel admins
        $admin = Admin::updateOrCreate(
            ['username' => 'admin'],
            [
                'email' => 'admin@lentera.com',
                'name' => 'Administrator Lentera',
                'password' => Hash::make('admin123'),
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

        $kesbangpol = Admin::updateOrCreate(
            ['email' => 'kesbangpol@lentera.com'],
            [
                'username' => 'kesbangpol',
                'name' => 'Admin Kesbangpol',
                'password' => $defaultPassword,
                'role' => 'dinas',
                'dinas_id' => $dinasKesbangpol->id,
                'status_akun' => 'aktif',
            ]
        );
        // Sinkronkan akun kesbangpol@dinas.com jika sudah ada
        Admin::where('email', 'kesbangpol@dinas.com')->update([
            'password' => $defaultPassword,
            'dinas_id' => $dinasKesbangpol->id,
            'status_akun' => 'aktif',
        ]);
        $this->command->info("Kesbangpol ID: " . $kesbangpol->id . " | Username: 'kesbangpol'");

        // 3. Setup Akun Dinas (Diskominfo) di tabel admins
        $dinasDiskominfo = Dinas::firstOrCreate(
            ['name' => 'DINAS KOMUNIKASI DAN INFORMATIKA']
        );

        $dinasDiskominfoUser = Admin::updateOrCreate(
            ['email' => 'diskominfo@lentera.com'],
            [
                'username' => 'diskominfo',
                'name' => 'Admin Diskominfo',
                'password' => $defaultPassword,
                'role' => 'dinas',
                'dinas_id' => $dinasDiskominfo->id,
                'status_akun' => 'aktif',
            ]
        );

        // Akun generic username 'dinas'
        $dinasGeneric = Admin::updateOrCreate(
            ['username' => 'dinas'],
            [
                'email' => 'dinas@lentera.com',
                'name' => 'Admin Dinas (Diskominfo)',
                'password' => $defaultPassword,
                'role' => 'dinas',
                'dinas_id' => $dinasDiskominfo->id,
                'status_akun' => 'aktif',
            ]
        );

        // Sinkronkan akun diskominfo@dinas.com jika sudah ada
        Admin::where('email', 'diskominfo@dinas.com')->update([
            'password' => $defaultPassword,
            'dinas_id' => $dinasDiskominfo->id,
            'status_akun' => 'aktif',
        ]);
        $this->command->info("Dinas ID: " . $dinasGeneric->id . " | Username: 'dinas' atau 'diskominfo'");
    }
}

