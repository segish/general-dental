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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            $table->text('chief_complaint'); // Example: "Pneumonia"
            $table->text('symptoms')->nullable(); // Example: "Antibiotics for 7 days"
            $table->text('medical_history')->nullable(); // Example: "Antibiotics for 7 days"
            $table->text('additional_notes')->nullable();
            $table->foreignId('doctor_id')->constrained('admins')->onDelete('cascade')->nullable();
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
        Schema::dropIfExists('medical_records');
    }
};
