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
        Schema::create('permohonan_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_layanan_id')->constrained('jenis_layanans')->cascadeOnDelete();
            $table->foreignId('status_master_id')->constrained('status_masters')->cascadeOnDelete();
            
            // Teks
            $table->string('jenis_permohonan')->nullable();
            $table->string('atas_nama');
            $table->string('no_hp');
            $table->string('asal_instansi')->nullable();
            $table->string('judul_kegiatan')->nullable();
            $table->string('tempat_kegiatan');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('keterangan')->nullable();
            
            // File Dokumen
            $table->string('file_ktp')->nullable();
            $table->string('file_ktm')->nullable();
            $table->string('file_surat_permohonan')->nullable();
            $table->string('file_surat_pengantar')->nullable();
            $table->string('file_surat_lokasi')->nullable();
            $table->string('file_proposal')->nullable();
            $table->string('file_surat_kesbangpol_jabar')->nullable();
            $table->string('file_surat_kemendagri')->nullable();
            $table->string('file_surat_rekomendasi_lama')->nullable();
            $table->string('file_pendukung')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_layanans');
    }
};
