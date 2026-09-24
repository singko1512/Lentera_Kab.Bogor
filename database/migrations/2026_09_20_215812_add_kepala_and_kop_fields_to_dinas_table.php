<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('nama_kepala')->nullable();
            $table->string('nip_kepala')->nullable();
            $table->string('kop_surat')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            $table->dropColumn(['nama_kepala', 'nip_kepala', 'kop_surat']);
        });
    }
};
