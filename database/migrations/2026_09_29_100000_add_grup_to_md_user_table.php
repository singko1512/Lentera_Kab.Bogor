<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'grup')) {
                $table->string('grup', 10)->default('A')->after('role');
            }
        });

        // Set group based on existing schedule if available
        $users = DB::table('users')->where('role', 'user')->get();
        foreach ($users as $user) {
            $schedule = DB::table('md_jadwal_mingguan')->where('user_id', $user->id)->first();
            if ($schedule) {
                $grup = (strtolower($schedule->senin) === 'wfo') ? 'A' : 'B';
            } else {
                $grup = 'A';
            }
            DB::table('users')->where('id', $user->id)->update(['grup' => $grup]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'grup')) {
                $table->dropColumn('grup');
            }
        });
    }
};
