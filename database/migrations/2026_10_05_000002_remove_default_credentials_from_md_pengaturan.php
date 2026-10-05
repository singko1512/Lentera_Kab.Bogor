<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('md_pengaturan')) {
            DB::table('md_pengaturan')
                ->whereIn('kunci', [
                    'pin_admin',
                    'pin_superadmin',
                    'admin_login_username',
                    'admin_login_password',
                    'superadmin_login_username',
                    'superadmin_login_password',
                ])
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op to avoid reintroducing sensitive default credentials
    }
};
