<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('jurnals')) {
            Schema::table('jurnals', function (Blueprint $table) {
                if (!Schema::hasColumn('jurnals', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('magang_application_id')->constrained('users')->nullOnDelete();
                }
                if (!Schema::hasColumn('jurnals', 'hasil_pekerjaan')) {
                    $table->text('hasil_pekerjaan')->nullable()->after('kegiatan');
                }
                if (!Schema::hasColumn('jurnals', 'kendala')) {
                    $table->text('kendala')->nullable()->after('hasil_pekerjaan');
                }
                if (!Schema::hasColumn('jurnals', 'file_pekerjaan')) {
                    $table->string('file_pekerjaan', 255)->nullable()->after('file_lampiran');
                }
            });
        }

        if (Schema::hasTable('md_absensi')) {
            Schema::table('md_absensi', function (Blueprint $table) {
                if (!Schema::hasColumn('md_absensi', 'surat_izin')) {
                    $table->string('surat_izin', 255)->nullable()->after('laporan');
                }
                if (!Schema::hasColumn('md_absensi', 'is_koreksi')) {
                    $table->boolean('is_koreksi')->default(false)->after('surat_izin');
                }
                if (!Schema::hasColumn('md_absensi', 'keterangan_koreksi')) {
                    $table->text('keterangan_koreksi')->nullable()->after('is_koreksi');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jurnals')) {
            Schema::table('jurnals', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('jurnals', 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
                if (Schema::hasColumn('jurnals', 'hasil_pekerjaan')) {
                    $columns[] = 'hasil_pekerjaan';
                }
                if (Schema::hasColumn('jurnals', 'kendala')) {
                    $columns[] = 'kendala';
                }
                if (Schema::hasColumn('jurnals', 'file_pekerjaan')) {
                    $columns[] = 'file_pekerjaan';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('md_absensi')) {
            Schema::table('md_absensi', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('md_absensi', 'surat_izin')) {
                    $columns[] = 'surat_izin';
                }
                if (Schema::hasColumn('md_absensi', 'is_koreksi')) {
                    $columns[] = 'is_koreksi';
                }
                if (Schema::hasColumn('md_absensi', 'keterangan_koreksi')) {
                    $columns[] = 'keterangan_koreksi';
                }
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
