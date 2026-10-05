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
            $table->string('file_surat_final')->nullable()->after('file_surat_keluaran');
            $table->timestamp('surat_final_diunggah_pada')->nullable()->after('file_surat_final');
            $table->foreignId('surat_final_diunggah_oleh')->nullable()->after('surat_final_diunggah_pada')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->dropForeign(['surat_final_diunggah_oleh']);
            $table->dropColumn(['file_surat_final', 'surat_final_diunggah_pada', 'surat_final_diunggah_oleh']);
        });
    }
};
