<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone',15)->nullable();
            $table->string('address')->nullable();
            $table->string('bio')->nullable();
            $table->unsignedBigInteger('upline_id')->nullable();
            $table->float('shopping_wallet')->default(0);
            $table->float('income_wallet')->default(0);
            $table->float('points')->default(0);
            $table->float('left_points')->default(0);
            $table->float('right_points')->default(0);
            $table->string('refer_code')->unique();
            $table->enum('position',['left','right'])->default('right');
            $table->unsignedBigInteger('left_user_id')->nullable();
            $table->unsignedBigInteger('right_user_id')->nullable();
            $table->integer('refer_by');
            $table->boolean('is_active')->default(false);
            $table->enum('role', ['admin', 'user','dealer'])->default('user');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        DB::table('users')->insert([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('112233'),
            'refer_code' => '123456',
            'is_active' => true,
            'role' => 'admin',
            'refer_by' => 1,
            'upline_id' => 1,
            'position' => 'left',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
