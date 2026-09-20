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
        Schema::table('dinas', function (Blueprint $table) {
            $table->string('nama_kepala')->nullable()->after('status_magang');
            $table->string('nip_kepala')->nullable()->after('nama_kepala');
            $table->string('kop_surat')->nullable()->after('nip_kepala');
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
