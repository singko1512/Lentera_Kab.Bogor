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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'email')) {
                $table->string('email', 100)->nullable()->unique()->after('name');
            }

            if (! Schema::hasColumn('users', 'tanggal_mulai_magang')) {
                $table->date('tanggal_mulai_magang')->nullable()->after('email');
            }

            if (! Schema::hasColumn('users', 'tanggal_selesai_magang')) {
                $table->date('tanggal_selesai_magang')->nullable()->after('tanggal_mulai_magang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'tanggal_selesai_magang')) {
                $table->dropColumn('tanggal_selesai_magang');
            }

            if (Schema::hasColumn('users', 'tanggal_mulai_magang')) {
                $table->dropColumn('tanggal_mulai_magang');
            }

            if (Schema::hasColumn('users', 'email')) {
                $table->dropUnique(['email']);
                $table->dropColumn('email');
            }
        });
    }
};
