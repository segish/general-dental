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
    public function up()
    {
        Schema::create('radiology_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_request_test_id')->constrained('radiology_request_test')->onDelete('cascade');
            $table->enum('result_status', ['Normal', 'Abnormal', 'Critical', 'Pending', 'Inconclusive', 'Positive', 'Negative', 'Reactive', 'Non-Reactive', 'Indeterminate'])->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('admins');
            $table->enum('process_status', ['pending', 'in process', 'completed', 'rejected'])->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('admins');
            $table->enum('verify_status', ['pending', 'checking', 'approved', 'rejected'])->default('pending');
            $table->dateTime('process_end_time')->nullable();
            $table->dateTime('verify_start_time')->nullable();
            $table->dateTime('verify_end_time')->nullable();
            $table->string('additional_note')->nullable();
            $table->text('comments')->nullable();
            $table->json('image')->nullable();
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
        Schema::dropIfExists('radiology_results');
    }
};
