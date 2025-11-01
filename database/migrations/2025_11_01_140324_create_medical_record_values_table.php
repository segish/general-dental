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
        Schema::create('medical_record_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained('medical_records')->onDelete('cascade');
            $table->foreignId('medical_record_field_id')->constrained('medical_record_fields')->onDelete('cascade');
            $table->text('value')->nullable(); // Store as text for single values, JSON string for arrays
            $table->timestamps();

            // Ensure one value per field per medical record (can be updated)
            $table->unique(['medical_record_id', 'medical_record_field_id'], 'mr_val_mr_id_field_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medical_record_values');
    }
};
