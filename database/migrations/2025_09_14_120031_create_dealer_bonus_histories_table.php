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
        Schema::create('dealer_bonus_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('users')->onDelete('cascade'); // dealer ইউজারের ID
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade'); // কোন অর্ডারের জন্য
            $table->decimal('amount', 10, 2);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_bonus_histories');
    }
};
