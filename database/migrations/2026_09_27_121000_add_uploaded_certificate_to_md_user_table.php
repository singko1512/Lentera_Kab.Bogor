<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'sertifikat_file_path')) {
                $table->string('sertifikat_file_path', 255)->nullable()->after('role');
            }

            if (! Schema::hasColumn('users', 'sertifikat_file_name')) {
                $table->string('sertifikat_file_name', 255)->nullable()->after('sertifikat_file_path');
            }

            if (! Schema::hasColumn('users', 'sertifikat_file_mime')) {
                $table->string('sertifikat_file_mime', 120)->nullable()->after('sertifikat_file_name');
            }

            if (! Schema::hasColumn('users', 'sertifikat_diunggah_pada')) {
                $table->timestamp('sertifikat_diunggah_pada')->nullable()->after('sertifikat_file_mime');
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('users')) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            foreach (['sertifikat_diunggah_pada', 'sertifikat_file_mime', 'sertifikat_file_name', 'sertifikat_file_path'] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
