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
        Schema::create('medical_conditions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('condition_categories')->onDelete('cascade');
            $table->string('name')->unique(); // Example: "Diabetes"
            $table->string('code')->nullable()->unique(); // Example: "diabetes"
            $table->text('description')->nullable();
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
        Schema::dropIfExists('medical_conditions');
    }
};
