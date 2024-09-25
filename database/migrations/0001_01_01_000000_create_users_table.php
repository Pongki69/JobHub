<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('job_experience')->nullable(); // Match with your database
            $table->string('job_description'); // Match with your database
            $table->string('company'); // Match with your database
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('contact_num')->nullable();
            $table->date('birthdate')->nullable(); // Change to date for better handling
            $table->string('role')->nullable();
            $table->string('profile_pic')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('google_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
