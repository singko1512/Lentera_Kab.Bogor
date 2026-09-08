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
        Schema::table('magang_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('magang_applications', 'dinas_id')) {
                $table->foreignId('dinas_id')->nullable()->after('rekrutmen_id')->constrained('dinas')->nullOnDelete();
            }
            if (!Schema::hasColumn('magang_applications', 'bidang_id')) {
                $table->foreignId('bidang_id')->nullable()->after('dinas_id')->constrained('bidang')->nullOnDelete();
            }
            if (!Schema::hasColumn('magang_applications', 'file_surat_penerimaan')) {
                $table->string('file_surat_penerimaan')->nullable()->after('status');
            }
            if (!Schema::hasColumn('magang_applications', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('file_surat_penerimaan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('magang_applications', function (Blueprint $table) {
            $table->dropForeign(['dinas_id']);
            $table->dropForeign(['bidang_id']);
            $table->dropColumn(['dinas_id', 'bidang_id', 'file_surat_penerimaan', 'catatan_admin']);
        });
    }
};
