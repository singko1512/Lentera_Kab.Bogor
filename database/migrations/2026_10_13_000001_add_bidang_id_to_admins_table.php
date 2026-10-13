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
        if (Schema::hasTable('admins') && ! Schema::hasColumn('admins', 'bidang_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->foreignId('bidang_id')->nullable()->after('dinas_id')->constrained('bidang')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('admins') && Schema::hasColumn('admins', 'bidang_id')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropForeign(['bidang_id']);
                $table->dropColumn('bidang_id');
            });
        }
    }
};
