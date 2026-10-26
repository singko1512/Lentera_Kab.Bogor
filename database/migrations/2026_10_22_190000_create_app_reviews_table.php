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
        Schema::create('app_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('permohonan_layanan_id')->nullable()->constrained('permohonan_layanans')->nullOnDelete();
            
            // 4 Pertanyaan Rating (Skala 1 - 10)
            $table->unsignedTinyInteger('rating_ui_ux')->comment('Desain & Antarmuka (UI/UX)');
            $table->unsignedTinyInteger('rating_kemudahan')->comment('Kemudahan Penggunaan (Usability)');
            $table->unsignedTinyInteger('rating_kecepatan')->comment('Kecepatan & Kinerja Sistem (Performance)');
            $table->unsignedTinyInteger('rating_kejelasan')->comment('Kejelasan Informasi & Alur Layanan');
            
            // Kolom Kritik & Saran (Opsional)
            $table->text('kritik_saran')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_reviews');
    }
};
