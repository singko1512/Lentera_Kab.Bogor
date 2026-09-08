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
            $table->string('file_surat_keluaran')->nullable()->after('file_pendukung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->dropColumn('file_surat_keluaran');
        });
    }
};
