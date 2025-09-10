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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('amount', 12, 2)->default(0);
            $table->boolean('status')->default(true)->comment('1 = Active, 0 = Inactive');
            $table->timestamps();
        });

        DB::table('funds')->insert([
            ['name' => 'Club Fund', 'amount' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Insurance Fund', 'amount' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Poor Fund', 'amount' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rank Fund', 'amount' => 0, 'status' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
