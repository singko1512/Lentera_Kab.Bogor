<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StatusMaster;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kolom berlaku_sampai pada surat_rekomendasis
        if (Schema::hasTable('surat_rekomendasis')) {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                if (!Schema::hasColumn('surat_rekomendasis', 'berlaku_sampai')) {
                    $table->date('berlaku_sampai')->nullable()->after('tanggal_surat');
                }
            });
        }

        // 2. Kolom berlaku_sampai & expired_at pada magang_applications
        if (Schema::hasTable('magang_applications')) {
            Schema::table('magang_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('magang_applications', 'berlaku_sampai')) {
                    $table->date('berlaku_sampai')->nullable()->after('tanggal_selesai');
                }
                if (!Schema::hasColumn('magang_applications', 'expired_at')) {
                    $table->timestamp('expired_at')->nullable()->after('berlaku_sampai');
                }
            });
        }

        // 3. Kolom masa_berlaku_hari pada dinas (override per instansi)
        if (Schema::hasTable('dinas')) {
            Schema::table('dinas', function (Blueprint $table) {
                if (!Schema::hasColumn('dinas', 'masa_berlaku_hari')) {
                    $table->unsignedInteger('masa_berlaku_hari')->nullable()->after('status_magang');
                }
            });
        }

        // 4. Seed status_masters bila belum ada
        if (Schema::hasTable('status_masters')) {
            StatusMaster::firstOrCreate(
                ['kode' => 'expired'],
                ['kode' => 'expired', 'nama' => 'Kedaluwarsa', 'warna' => '#6c757d']
            );
            StatusMaster::firstOrCreate(
                ['kode' => 'dibatalkan'],
                ['kode' => 'dibatalkan', 'nama' => 'Dibatalkan', 'warna' => '#6c757d']
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('surat_rekomendasis') && Schema::hasColumn('surat_rekomendasis', 'berlaku_sampai')) {
            Schema::table('surat_rekomendasis', function (Blueprint $table) {
                $table->dropColumn('berlaku_sampai');
            });
        }

        if (Schema::hasTable('magang_applications')) {
            Schema::table('magang_applications', function (Blueprint $table) {
                if (Schema::hasColumn('magang_applications', 'expired_at')) {
                    $table->dropColumn('expired_at');
                }
                if (Schema::hasColumn('magang_applications', 'berlaku_sampai')) {
                    $table->dropColumn('berlaku_sampai');
                }
            });
        }

        if (Schema::hasTable('dinas') && Schema::hasColumn('dinas', 'masa_berlaku_hari')) {
            Schema::table('dinas', function (Blueprint $table) {
                $table->dropColumn('masa_berlaku_hari');
            });
        }
    }
};
