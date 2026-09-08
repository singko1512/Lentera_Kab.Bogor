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
            $table->string('nik', 50)->nullable()->after('email');
            $table->string('no_hp', 20)->nullable()->after('nik');
            $table->string('tempat_lahir', 100)->nullable()->after('no_hp');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('asal_instansi')->nullable()->after('tanggal_lahir');
            $table->string('program_studi')->nullable()->after('asal_instansi');
            $table->string('nim', 50)->nullable()->after('program_studi');
            $table->text('alamat')->nullable()->after('nim');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'no_hp',
                'tempat_lahir',
                'tanggal_lahir',
                'asal_instansi',
                'program_studi',
                'nim',
                'alamat'
            ]);
        });
    }
};
