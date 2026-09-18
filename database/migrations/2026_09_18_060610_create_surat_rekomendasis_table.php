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
        Schema::create('surat_rekomendasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_layanan_id')->constrained('permohonan_layanans')->onDelete('cascade');
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->string('sifat_surat')->default('Biasa');
            $table->string('lampiran_surat')->default('-');
            $table->string('pejabat_nama')->nullable();
            $table->string('pejabat_nip')->nullable();
            $table->string('pejabat_pangkat')->nullable();
            $table->string('pejabat_jabatan')->nullable();
            $table->json('tembusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_rekomendasis');
    }
};
