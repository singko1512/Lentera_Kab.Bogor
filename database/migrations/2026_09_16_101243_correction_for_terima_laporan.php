<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Drop user_id from jurnals
        if (Schema::hasTable('jurnals')) {
            Schema::table('jurnals', function (Blueprint $table) {
                if (Schema::hasColumn('jurnals', 'user_id')) {
                    $table->dropConstrainedForeignId('user_id');
                }
            });
        }

        // 2. Rename surat_izin to surat_keterangan in md_absensi
        if (Schema::hasTable('md_absensi')) {
            Schema::table('md_absensi', function (Blueprint $table) {
                if (Schema::hasColumn('md_absensi', 'surat_izin') && !Schema::hasColumn('md_absensi', 'surat_keterangan')) {
                    $table->renameColumn('surat_izin', 'surat_keterangan');
                }
            });
        }

        // 3. Unique index for jurnals (magang_application_id, tanggal)
        if (Schema::hasTable('jurnals')) {
            // Check for duplicates first
            $duplicates = DB::table('jurnals')
                ->select('magang_application_id', 'tanggal', DB::raw('COUNT(*) as count'))
                ->groupBy('magang_application_id', 'tanggal')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            if ($duplicates->isNotEmpty()) {
                $errorMessage = "Cannot apply unique constraint on 'jurnals' table because duplicate records exist for magang_application_id and tanggal. Duplicates found: \n";
                foreach ($duplicates as $duplicate) {
                    $errorMessage .= "magang_application_id: {$duplicate->magang_application_id}, tanggal: {$duplicate->tanggal} (Count: {$duplicate->count})\n";
                }
                throw new \Exception($errorMessage);
            }

            Schema::table('jurnals', function (Blueprint $table) {
                $indexes = Schema::getIndexes('jurnals');
                $indexExists = false;
                
                foreach ($indexes as $index) {
                    if ($index['name'] === 'jurnals_magang_application_id_tanggal_unique') {
                        $indexExists = true;
                        break;
                    }
                }

                if (!$indexExists) {
                    $table->unique(['magang_application_id', 'tanggal']);
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
                if (!Schema::hasColumn('jurnals', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                }

                $indexes = Schema::getIndexes('jurnals');
                $indexExists = false;
                
                foreach ($indexes as $index) {
                    if ($index['name'] === 'jurnals_magang_application_id_tanggal_unique') {
                        $indexExists = true;
                        break;
                    }
                }

                if ($indexExists) {
                    $table->dropUnique('jurnals_magang_application_id_tanggal_unique');
                }
            });
        }

        if (Schema::hasTable('md_absensi')) {
            Schema::table('md_absensi', function (Blueprint $table) {
                if (Schema::hasColumn('md_absensi', 'surat_keterangan') && !Schema::hasColumn('md_absensi', 'surat_izin')) {
                    $table->renameColumn('surat_keterangan', 'surat_izin');
                }
            });
        }
    }
};
