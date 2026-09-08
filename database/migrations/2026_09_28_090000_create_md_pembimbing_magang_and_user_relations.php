<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('md_pembimbing_magang')) {
            Schema::create('md_pembimbing_magang', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 100)->unique();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('users')) {
            $existingPembimbing = DB::table('users')
                ->whereNotNull('pembimbing_magang')
                ->where('pembimbing_magang', '<>', '')
                ->distinct()
                ->pluck('pembimbing_magang');

            foreach ($existingPembimbing as $nama) {
                DB::table('md_pembimbing_magang')->insertOrIgnore([
                    'nama' => $nama,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'bidang_id')) {
                    $table->foreignId('bidang_id')->nullable()->after('bidang_magang')->constrained('bidang')->nullOnDelete();
                }

                if (! Schema::hasColumn('users', 'pembimbing_magang_id')) {
                    $table->foreignId('pembimbing_magang_id')->nullable()->after('pembimbing_magang')->constrained('md_pembimbing_magang')->nullOnDelete();
                }
            });

            if (Schema::hasTable('bidang')) {
                DB::table('users')
                    ->whereNotNull('bidang_magang')
                    ->where('bidang_magang', '<>', '')
                    ->orderBy('id')
                    ->get()
                    ->each(function ($user): void {
                        $bidangId = DB::table('bidang')->where('nama', $user->bidang_magang)->value('id');

                        if ($bidangId) {
                            DB::table('users')->where('id', $user->id)->update(['bidang_id' => $bidangId]);
                        }
                    });
            }

            DB::table('users')
                ->whereNotNull('pembimbing_magang')
                ->where('pembimbing_magang', '<>', '')
                ->orderBy('id')
                ->get()
                ->each(function ($user): void {
                    $pembimbingId = DB::table('md_pembimbing_magang')->where('nama', $user->pembimbing_magang)->value('id');

                    if ($pembimbingId) {
                        DB::table('users')->where('id', $user->id)->update(['pembimbing_magang_id' => $pembimbingId]);
                    }
                });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'pembimbing_magang_id')) {
                    $table->dropConstrainedForeignId('pembimbing_magang_id');
                }

                if (Schema::hasColumn('users', 'bidang_id')) {
                    $table->dropConstrainedForeignId('bidang_id');
                }
            });
        }

        Schema::dropIfExists('md_pembimbing_magang');
    }
};
