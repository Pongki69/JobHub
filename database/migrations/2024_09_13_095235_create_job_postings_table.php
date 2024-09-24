<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_posts', function (Blueprint $table) {
    $table->unsignedBigInteger('user_id'); // Foreign key to users table
    $table->id();
    $table->string('job_title');
    $table->string('company');
    $table->text('job_description');
    $table->string('job_type');
    $table->string('job_location');
    $table->timestamp('job_deadline');
    $table->string('image_path')->nullable();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('uploader_name');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_postings');
    }
}
