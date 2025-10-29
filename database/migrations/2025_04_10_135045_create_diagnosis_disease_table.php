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
        Schema::create('diagnosis_disease', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_treatment_id')->constrained('diagnosis_treatments')->onDelete('cascade');
            $table->foreignId('medical_condition_id')->constrained('medical_conditions')->onDelete('cascade');
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
        Schema::dropIfExists('diagnosis_disease');
    }
};
