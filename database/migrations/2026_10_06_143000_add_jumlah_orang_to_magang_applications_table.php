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
        if (Schema::hasTable('magang_applications')) {
            Schema::table('magang_applications', function (Blueprint $table) {
                if (!Schema::hasColumn('magang_applications', 'jumlah_orang')) {
                    $table->unsignedInteger('jumlah_orang')->default(1)->after('status');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('magang_applications')) {
            Schema::table('magang_applications', function (Blueprint $table) {
                if (Schema::hasColumn('magang_applications', 'jumlah_orang')) {
                    $table->dropColumn('jumlah_orang');
                }
            });
        }
    }
};
