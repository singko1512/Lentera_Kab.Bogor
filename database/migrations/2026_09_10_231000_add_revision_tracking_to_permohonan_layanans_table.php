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
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            if (!Schema::hasColumn('permohonan_layanans', 'status_revisi')) {
                $table->string('status_revisi', 50)->nullable()->after('keterangan');
            }
            if (!Schema::hasColumn('permohonan_layanans', 'catatan_pemohon')) {
                $table->text('catatan_pemohon')->nullable()->after('status_revisi');
            }
            if (!Schema::hasColumn('permohonan_layanans', 'dokumen_direvisi')) {
                $table->text('dokumen_direvisi')->nullable()->after('catatan_pemohon');
            }
            if (!Schema::hasColumn('permohonan_layanans', 'tanggal_revisi')) {
                $table->timestamp('tanggal_revisi')->nullable()->after('dokumen_direvisi');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->dropColumn(['status_revisi', 'catatan_pemohon', 'dokumen_direvisi', 'tanggal_revisi']);
        });
    }
};
