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
        Schema::create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->enum('category_type', ['Vital Sign', 'Physical Tests']);
            $table->string('name');
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null'); // Link to units table
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
        Schema::dropIfExists('assessment_categories');
    }
};
