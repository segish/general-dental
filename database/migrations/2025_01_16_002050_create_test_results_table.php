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
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_request_test_id'); // Links to the test in the request
            $table->enum('result_status', ['Normal', 'Abnormal', 'Critical', 'Pending', 'Inconclusive', 'Positive', 'Negative', 'Reactive', 'Non-Reactive', 'Indeterminate'])->nullable();
            $table->unsignedBigInteger('processed_by')->nullable(); // Who processed the result
            $table->enum('process_status', ['pending', 'in process', 'completed', 'rejected'])->default('pending');
            $table->unsignedBigInteger('verified_by')->nullable(); // Who verified the result
            $table->enum('verify_status', ['pending', 'checking', 'approved', 'rejected'])->default('pending');

            // Time tracking for the result process
            $table->dateTime('process_end_time')->nullable();
            $table->dateTime('verify_start_time')->nullable();
            $table->dateTime('verify_end_time')->nullable();

            $table->string('additional_note')->nullable();
            $table->text('comments')->nullable();
            $table->json('image')->nullable(); // Images or additional media
            $table->timestamps();

            // Foreign key linking to the laboratory_request_test table
            $table->foreign('laboratory_request_test_id')->references('id')->on('laboratory_request_test')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('admins');
            $table->foreign('verified_by')->references('id')->on('admins');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_results');
    }
};
