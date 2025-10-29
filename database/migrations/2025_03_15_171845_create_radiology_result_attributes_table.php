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
        Schema::create('radiology_result_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_result_id')->constrained('radiology_results')->onDelete('cascade');
            $table->foreignId('radiology_attribute_id')->constrained('radiology_attributes')->onDelete('cascade');
            $table->text('result_value')->nullable();
            $table->text('comments')->nullable();
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
        Schema::dropIfExists('radiology_result_attributes');
    }
};
