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
            if (!Schema::hasColumn('dinas', 'status_magang')) {
                $table->string('status_magang')->default('otomatis')->after('is_kesbangpol');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dinas', function (Blueprint $table) {
            if (Schema::hasColumn('dinas', 'status_magang')) {
                $table->dropColumn('status_magang');
            }
        });
    }
};
