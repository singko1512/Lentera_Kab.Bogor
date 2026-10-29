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
        Schema::create('template_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_layanan_id')->nullable()->constrained('jenis_layanans')->nullOnDelete();
            $table->string('nama');
            $table->string('slug')->index();
            $table->string('file_nama');
            $table->string('file_path');
            $table->string('nomor_prefix')->nullable()->default('000.9.2 / ');
            $table->string('sifat_surat')->default('Biasa');
            $table->string('lampiran_surat')->default('-');
            $table->string('perihal')->nullable();
            $table->string('pejabat_nama')->nullable()->default('Drs. BAMBANG WIDJANARKO, M.Si');
            $table->string('pejabat_nip')->nullable()->default('19680512 199303 1 008');
            $table->string('pejabat_pangkat')->nullable()->default('Pembina Utama Muda (IV/c)');
            $table->string('pejabat_jabatan')->nullable()->default('Kepala Badan Kesatuan Bangsa dan Politik Kabupaten Bogor');
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_surats');
    }
};
