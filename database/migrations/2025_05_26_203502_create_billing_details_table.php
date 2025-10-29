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
        Schema::create('billing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('billing_id');
            $table->foreign('billing_id')->references('id')->on('billings')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('unit_cost', 10, 2)->nullable();
            $table->foreignId('test_id')->nullable()->constrained('tests')->cascadeOnDelete();
            $table->foreignId('radiology_id')->nullable()->constrained('radiologies')->cascadeOnDelete();
            $table->foreignId('billing_service_id')->nullable()->constrained('billing_services')->cascadeOnDelete();
            $table->foreignId('emergency_medicine_issuance_id')->nullable()->constrained('emergency_prescription_details')->cascadeOnDelete();
            $table->foreignId('patient_procedures_id')->nullable()->constrained('patient_procedures')->cascadeOnDelete();
            $table->foreignId('billing_from_discharge_id')->nullable()->constrained('discharges')->cascadeOnDelete();
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
        Schema::dropIfExists('billing_details');
    }
};
