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
        Schema::create('medical_record_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Field label/name (e.g., "Chief Complaint")
            $table->string('short_code')->unique(); // Short code identifier (e.g., "chief_complaint")
            $table->enum('field_type', ['text', 'textarea', 'select', 'multiselect', 'checkbox']); // Field type
            $table->boolean('is_multiple')->default(false); // For select/multiselect - single or multiple selection
            $table->boolean('is_required')->default(false); // Whether field is required
            $table->integer('order')->default(0); // Display order
            $table->boolean('status')->default(true); // Active/Inactive
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
        Schema::dropIfExists('medical_record_fields');
    }
};
