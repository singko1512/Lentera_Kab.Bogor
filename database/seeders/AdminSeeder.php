<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Kesbangpol account is handled entirely by ExcelDinasSeeder

        // 2. Setup Super Admin
        $admin = User::firstOrCreate(
            ['username' => 'superadmin'],
            [
                'email' => 'superadmin@lentera.com',
                'name' => 'Super Admin',
                'password' => bcrypt('Tegarberiman'),
                'role' => 'superadmin'
            ]
        );
        $this->command->info("Super Admin ID: " . $admin->id);
    }
}
