<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\User;
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

        // 1. Setup Super Admin
        $superadmin = User::updateOrCreate(
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

        // 2. Setup Akun Kesbangpol
        $dinasKesbangpol = Dinas::firstOrCreate(
            ['name' => 'BADAN KESATUAN BANGSA DAN POLITIK'],
            ['is_kesbangpol' => true]
        );
        $dinasKesbangpol->update(['is_kesbangpol' => true]);

        $kesbangpol = User::updateOrCreate(
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
        User::where('email', 'kesbangpol@dinas.com')->update([
            'password' => $defaultPassword,
            'dinas_id' => $dinasKesbangpol->id,
            'status_akun' => 'aktif',
        ]);
        $this->command->info("Kesbangpol ID: " . $kesbangpol->id . " | Username: 'kesbangpol'");

        // 3. Setup Akun Dinas (Diskominfo)
        $dinasDiskominfo = Dinas::firstOrCreate(
            ['name' => 'DINAS KOMUNIKASI DAN INFORMATIKA']
        );

        $dinasDiskominfoUser = User::updateOrCreate(
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
        $dinasGeneric = User::updateOrCreate(
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
        User::where('email', 'diskominfo@dinas.com')->update([
            'password' => $defaultPassword,
            'dinas_id' => $dinasDiskominfo->id,
            'status_akun' => 'aktif',
        ]);
        $this->command->info("Dinas ID: " . $dinasGeneric->id . " | Username: 'dinas' atau 'diskominfo'");
    }
}

