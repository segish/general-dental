<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_id');
            $table->string('attribute_name');
            $table->enum('attribute_type', ['Qualitative', 'Quantitative']);
            $table->enum('test_category', ['Macroscopic', 'Microscopic', 'Chemical', 'Text', 'Result'])->default('Text');
            $table->boolean('has_options')->default(false);
            $table->boolean('default_required')->default(false);
            $table->unsignedInteger('index')->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('set null');
            $table->timestamps();

            $table->foreign('test_id')->references('id')->on('tests')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('test_attributes');
    }
};
