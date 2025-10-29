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
        Schema::create('test_attribute_references', function (Blueprint $table) {
            $table->id();

            // Link to test attribute
            $table->foreignId('test_attribute_id')
                ->constrained('test_attributes')
                ->onDelete('cascade');

            // Conditions (optional)
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->integer('min_age')->nullable(); // Age in years
            $table->integer('max_age')->nullable();
            $table->boolean('is_pregnant')->nullable();

            // Quantitative range values (optional)
            $table->decimal('lower_limit', 10, 2)->nullable();
            $table->decimal('upper_limit', 10, 2)->nullable();
            $table->enum('lower_operator', ['>', '>=', '='])->nullable();
            $table->enum('upper_operator', ['<', '<=', '='])->nullable();

            // Qualitative or additional info
            $table->string('reference_text', 255)->nullable();

            // Is this the default/fallback reference?
            $table->boolean('is_default')->default(false);

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
        Schema::dropIfExists('test_attribute_references');
    }
};
