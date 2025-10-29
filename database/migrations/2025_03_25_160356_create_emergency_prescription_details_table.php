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
        Schema::create('emergency_prescription_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('emergency_prescription_id')->constrained('emergency_prescriptions')->onDelete('cascade');
            $table->foreignId('emergency_inventory_id')->constrained('emergency_inventory')->onDelete('cascade');
            $table->foreignId('issued_by')->nullable()->constrained('admins')->onDelete('cascade');

            $table->string('dosage')->nullable();
            $table->integer('dose_duration')->nullable();
            $table->enum('dose_time', ['Before Meal', 'After Meal', 'With Meal', 'Anytime'])->nullable();
            $table->enum('dose_interval', ['Once Daily', 'Twice Daily', 'Three Times Daily', 'Every 6 Hours', 'Every 8 Hours'])->nullable();
            $table->integer('quantity');
            $table->enum('status', ['pending', 'issued', 'cancelled'])->nullable()->default('pending');
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
        Schema::dropIfExists('emergency_prescription_details');
    }
};
