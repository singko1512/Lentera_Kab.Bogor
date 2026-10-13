<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('permohonan_layanans', function (Blueprint $table) {
                $table->dropForeign(['surat_final_diunggah_oleh']);
            });

            Schema::table('md_project_task_participants', function (Blueprint $table) {
                $table->dropForeign(['approved_by']);
            });

            Schema::table('md_work_submissions', function (Blueprint $table) {
                $table->dropForeign(['reviewed_by']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('permohonan_layanans', function (Blueprint $table) {
                $table->foreign('surat_final_diunggah_oleh')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });

            Schema::table('md_project_task_participants', function (Blueprint $table) {
                $table->foreign('approved_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });

            Schema::table('md_work_submissions', function (Blueprint $table) {
                $table->foreign('reviewed_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            });
        }
    }
};
