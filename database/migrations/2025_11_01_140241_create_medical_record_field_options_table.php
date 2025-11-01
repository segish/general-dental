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
        Schema::create('medical_record_field_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_field_id')->constrained('medical_record_fields')->onDelete('cascade');
            $table->string('option_value'); // The actual value to be stored
            $table->string('option_label'); // The display label
            $table->integer('order')->default(0); // Display order
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
        Schema::dropIfExists('medical_record_field_options');
    }
};
