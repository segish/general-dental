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
        Schema::create('radiology_request_test', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_request_id')->constrained('radiology_requests')->onDelete('cascade');
            $table->foreignId('radiology_id')->constrained('radiologies')->onDelete('cascade');
            $table->enum('status', ['pending', 'in process', 'completed', 'rejected'])->default('pending');
            $table->string('additional_note')->nullable();
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
        Schema::dropIfExists('radiology_request_test');
    }
};
