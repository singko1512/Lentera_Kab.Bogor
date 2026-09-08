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
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('foto_masuk')->nullable()->after('waktu_masuk');
            $table->string('lokasi_masuk')->nullable()->after('foto_masuk');
            $table->string('foto_pulang')->nullable()->after('waktu_pulang');
            $table->string('lokasi_pulang')->nullable()->after('foto_pulang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['foto_masuk', 'lokasi_masuk', 'foto_pulang', 'lokasi_pulang']);
        });
    }
};
