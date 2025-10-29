<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specimens', function (Blueprint $table) {
            $table->id();
            $table->integer('specimen_code')->unique()->unsigned();
            $table->unsignedBigInteger('checker_id')->nullable();
            $table->unsignedBigInteger('specimen_origin_id')->nullable();
            $table->unsignedBigInteger('laboratory_request_id'); // Column for test request ID
            $table->enum('status', ['pending', 'in process', 'accepted', 'rejected'])->default('pending');
            $table->text('notes')->nullable();

            // New columns for tracking both checking and approval time
            $table->dateTime('checking_start_time')->nullable();
            $table->dateTime('checking_end_time')->nullable();

            // Time when the specimen was taken
            $table->dateTime('specimen_taken_at')->nullable();  // Adds specimen taken time column

            $table->timestamps();

            $table->foreign('checker_id')->references('id')->on('admins');
            $table->foreign('specimen_origin_id')->references('id')->on('specimen_origins');
            $table->foreign('laboratory_request_id')->references('id')->on('laboratory_requests'); // Foreign key for test_requests table
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specimens');
    }
};
