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
        Schema::table('md_absensi', function (Blueprint $table) {
            $table->string('surat_izin')->nullable()->after('lokasi_masuk_diambil_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('md_absensi', function (Blueprint $table) {
            $table->dropColumn('surat_izin');
        });
    }
};
