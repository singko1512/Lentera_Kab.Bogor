<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Kesbangpol Admin
        $kes = Dinas::where('name', 'BADAN KESATUAN BANGSA DAN POLITIK')->first();
        if ($kes) {
            $kes->update(['is_kesbangpol' => true]);
            
            $kUser = User::firstOrCreate(
                ['email' => 'admin@kesbangpol.com'],
                [
                    'name' => 'Admin Kesbangpol',
                    'password' => bcrypt('password123'),
                    'role' => 'dinas',
                    'dinas_id' => $kes->id
                ]
            );
            $this->command->info("Kesbangpol ID: " . $kUser->id);
        }

        // 2. Setup Super Admin
        $admin = User::firstOrCreate(
            ['email' => 'superadmin@lentera.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password123'),
                'role' => 'admin'
            ]
        );
        $this->command->info("Super Admin ID: " . $admin->id);
    }
}
