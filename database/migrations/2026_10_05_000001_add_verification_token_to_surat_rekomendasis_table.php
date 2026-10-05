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
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            if (!Schema::hasColumn('surat_rekomendasis', 'verification_token')) {
                $table->string('verification_token', 64)->nullable()->unique()->after('nomor_surat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_rekomendasis', function (Blueprint $table) {
            if (Schema::hasColumn('surat_rekomendasis', 'verification_token')) {
                $table->dropColumn('verification_token');
            }
        });
    }
};
