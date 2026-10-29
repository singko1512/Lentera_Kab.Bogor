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
        Schema::table('template_surats', function (Blueprint $table) {
            $table->longText('konten_html')->nullable()->after('keterangan');
            $table->json('dasar_hukum')->nullable()->after('konten_html');
            $table->json('ketentuan')->nullable()->after('dasar_hukum');
            $table->json('tembusan')->nullable()->after('ketentuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_surats', function (Blueprint $table) {
            $table->dropColumn(['konten_html', 'dasar_hukum', 'ketentuan', 'tembusan']);
        });
    }
};
