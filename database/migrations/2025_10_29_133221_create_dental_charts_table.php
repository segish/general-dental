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
        Schema::create('dental_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->enum('chart_type', [
                'odontogram',           // Tooth chart
                'periodontal',           // Gum charting
                'treatment_plan',        // Treatment visualization
                'clinical_drawing',      // Freehand drawings
                'image_annotation'       // X-ray/photo annotations
            ]);
            $table->string('title')->nullable();
            $table->text('chart_data')->nullable();  // JSON data for drawing (Fabric.js JSON)
            $table->text('tooth_data')->nullable();  // JSON for tooth-specific data
            $table->string('image_path')->nullable(); // For image annotations background
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('admins')->onDelete('cascade');
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
        Schema::dropIfExists('dental_charts');
    }
};
