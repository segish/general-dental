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
        Schema::create('laboratory_request_test', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('laboratory_request_id');
            $table->unsignedBigInteger('test_id');
            $table->enum('status', ['pending', 'in process', 'completed', 'rejected'])->default('pending');
            $table->string('additional_note')->nullable();
            $table->timestamps();

            $table->foreign('laboratory_request_id')->references('id')->on('laboratory_requests')->onDelete('cascade');
            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('laboratory_request_test');
    }
};
