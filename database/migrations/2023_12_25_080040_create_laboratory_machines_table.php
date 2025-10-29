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
        Schema::create('laboratory_machines', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Machine name
            $table->string('model')->nullable(); // Machine model
            $table->string('serial_number')->nullable(); // Serial number
            $table->string('manufacturer')->nullable(); // Manufacturer name
            $table->text('description')->nullable(); // Machine description
            $table->string('code')->unique(); // Machine code (e.g., ACC)
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
        Schema::dropIfExists('laboratory_machines');
    }
};
