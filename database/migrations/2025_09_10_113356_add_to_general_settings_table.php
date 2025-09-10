<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->decimal('club_required_pv', 12, 2)->default(1000)->after('withdraw_charge');
            $table->string('club_image')->nullable()->after('club_required_pv');
            $table->decimal('pv_value', 12, 2)->default(2)->after('club_image');
        });


        if (DB::table('general_settings')->count() == 0) {
            DB::table('general_settings')->insert([
                'club_required_pv' => 1000,
                'club_image' => null,
                'pv_value' => 2,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->dropColumn(['club_required_pv', 'club_image']);
        });
    }
};
