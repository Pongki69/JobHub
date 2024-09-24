<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // Create the 'users' table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            // Additional columns for users
            $table->string('contact_num', 255)->nullable();
            $table->string('birthdate', 255)->nullable();
            $table->string('role', 255)->nullable();
            $table->string('profile_pic', 255)->nullable();
            $table->string('cover_photo', 255)->nullable();
            $table->string('google_id')->nullable()->unique();
        });

        // Create the 'password_reset_tokens' table
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Create the 'sessions' table
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop the 'sessions' table
        Schema::dropIfExists('sessions');
        
        // Drop the 'password_reset_tokens' table
        Schema::dropIfExists('password_reset_tokens');
        
        // Drop the 'users' table
        Schema::dropIfExists('users');
    }
};
