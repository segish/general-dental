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
        Schema::create('prescription_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prescription_id')->constrained('prescriptions')->onDelete('cascade');
            $table->foreignId('medicine_id')->constrained('medicines')->onDelete('cascade');

            $table->string('dosage')->nullable();
            $table->integer('dose_duration')->nullable();
            $table->enum('dose_time', ['Before Meal', 'After Meal', 'With Meal', 'Anytime'])->nullable()->default('Anytime');
            $table->enum('dose_interval', ['Once Daily', 'Twice Daily', 'Three Times Daily', 'Every 6 Hours', 'Every 8 Hours'])->nullable()->default('Once Daily');
            $table->integer('quantity')->nullable();
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('prescription_details');
    }
};
