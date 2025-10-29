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
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->string('bed_number');
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->string('type')->nullable();
            $table->unsignedBigInteger('ward_id')->nullable();
            $table->string('room_number')->nullable();
            $table->enum('occupancy_status', ['cleaning', 'maintenance', 'normal'])->nullable();
            $table->decimal('price', 10, 2);
            $table->text('additional_notes')->nullable();
            $table->timestamps();
            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('set null');      
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */ 
    public function down()
    {
        Schema::dropIfExists('beds');
    }
};
