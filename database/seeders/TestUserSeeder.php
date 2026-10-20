<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure clean state: delete any previous test user with this email
        DB::table('users')->where('email', 'testuser@example.com')->delete();

        User::create([
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'username' => 'testuser',
            'password' => Hash::make('Password123'),
            'role' => 'user',
            'status_akun' => 'aktif',
            'email_verified_at' => Carbon::now(),
            'activation_token' => null,
            // optional fields to satisfy validation defaults
            'no_hp' => '08123456789',
            'tempat_lahir' => 'Bogor',
            'tanggal_lahir' => '2000-01-01',
            'asal_instansi' => 'Universitas Bogor',
            'program_studi' => 'Ilmu Komputer',
            'nim' => '12345678',
            'alamat' => 'Jalan Example No.1, Bogor',
        ]);
    }
}
