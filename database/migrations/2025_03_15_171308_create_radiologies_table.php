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
        Schema::create('radiologies', function (Blueprint $table) {
            $table->id();
            $table->string('radiology_name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('additional_notes')->nullable();
            $table->decimal('cost', 10, 2);
            $table->unsignedInteger('time_taken_hour')->default(0);
            $table->unsignedInteger('time_taken_min');
            $table->enum('paper_size', ['A4', 'A5'])->default('A4');
            $table->boolean('is_inhouse')->default(true);
            $table->enum('paper_orientation', ['portrait', 'landscape'])->default('portrait');
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('radiologies');
    }
};
