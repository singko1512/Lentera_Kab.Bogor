<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('admins')) {
            Schema::create('admins', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('username', 100)->nullable()->unique();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('role', 20)->default('admin');
                $table->string('status_akun', 20)->default('aktif');
                $table->foreignId('dinas_id')->nullable()->constrained('dinas')->nullOnDelete();
                $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
                $table->rememberToken();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('admins', 'bidang_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->foreignId('bidang_id')->nullable()->after('dinas_id')->constrained('bidang')->nullOnDelete();
            });
        }

        // Pindahkan / Salin akun pengelola (Admin, Superadmin, Dinas, Kesbangpol, Bidang) ke tabel admins
        if (Schema::hasTable('users')) {
            $existingAdmins = DB::table('users')
                ->whereIn('role', ['admin', 'superadmin', 'dinas', 'kesbangpol', 'bidang'])
                ->get();

            foreach ($existingAdmins as $adm) {
                DB::table('admins')->updateOrInsert(
                    ['email' => $adm->email],
                    [
                        'name' => $adm->name ?: 'Administrator',
                        'username' => $adm->username ?: null,
                        'password' => $adm->password,
                        'role' => $adm->role ?: 'dinas',
                        'status_akun' => $adm->status_akun ?: 'aktif',
                        'dinas_id' => $adm->dinas_id ?: null,
                        'bidang_id' => $adm->bidang_id ?: null,
                        'created_at' => $adm->created_at ?: now(),
                        'updated_at' => $adm->updated_at ?: now(),
                    ]
                );
            }

            // Hapus akun pengelola dari tabel users agar tabel users murni untuk user biasa (peserta)
            DB::table('users')
                ->whereIn('role', ['admin', 'superadmin', 'dinas', 'kesbangpol', 'bidang'])
                ->delete();
        }

        // Pastikan akun default Super Admin dan Admin tersedia di tabel admins jika belum ada
        $now = now();
        $defaultPassword = Hash::make('AdminLenteraBogor#2026!');

        DB::table('admins')->updateOrInsert(
            ['username' => 'superadmin'],
            [
                'name' => 'Super Admin Lentera',
                'email' => 'superadmin@lentera.com',
                'password' => $defaultPassword,
                'role' => 'superadmin',
                'status_akun' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('admins')->updateOrInsert(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Lentera',
                'email' => 'admin@lentera.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'status_akun' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
