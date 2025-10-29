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
        Schema::create('billings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade'); // Each record belongs to a visit
            $table->foreignId('laboratory_request_id')->nullable()->constrained('laboratory_requests')->cascadeOnDelete();
            $table->foreignId('radiology_request_id')->nullable()->constrained('radiology_requests')->cascadeOnDelete();
            $table->foreignId('billing_service_id')->nullable()->constrained('billing_services')->cascadeOnDelete();
            $table->foreignId('emergency_medicine_issuance_id')->nullable()->constrained('emergency_prescriptions')->cascadeOnDelete();
            $table->foreignId('patient_procedures_id')->nullable()->constrained('patient_procedures')->cascadeOnDelete();
            $table->foreignId('billing_from_discharge_id')->nullable()->constrained('discharges')->cascadeOnDelete();

            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            $table->date('bill_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount', 5, 2)->default(0);
            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->string('status');
            $table->text('note')->nullable();
            $table->boolean('is_canceled')->default(false);
            $table->text('cancel_reason')->nullable();
            $table->foreignId('canceled_by')->nullable()->constrained('admins')->onDelete('set null');
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
        Schema::dropIfExists('billings');
    }
};
