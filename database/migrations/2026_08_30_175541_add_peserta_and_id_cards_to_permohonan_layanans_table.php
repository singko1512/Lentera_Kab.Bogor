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
            $table->string('file_daftar_peserta')->nullable();
            $table->string('file_id_card')->nullable();
            $table->string('file_kartu_pelajar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->dropColumn(['file_daftar_peserta', 'file_id_card', 'file_kartu_pelajar']);
        });
    }
};
