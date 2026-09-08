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
        if (Schema::hasColumn('users', 'nip_atau_id') && ! Schema::hasColumn('users', 'email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('nip_atau_id', 'email');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'email') && ! Schema::hasColumn('users', 'nip_atau_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->renameColumn('email', 'nip_atau_id');
            });
        }
    }
};
