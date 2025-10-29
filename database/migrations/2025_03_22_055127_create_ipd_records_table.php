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
        Schema::create('ipd_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained('visits')->cascadeOnDelete();
            $table->foreignId('ward_id')->constrained('wards');
            $table->foreignId('bed_id')->constrained('beds');
            $table->foreignId('admitting_doctor_id')->constrained('admins');
            $table->dateTime('admission_date');
            $table->dateTime('discharge_date')->nullable();
            $table->text('discharge_summary')->nullable();
            $table->enum('ipd_status', ['Admitted', 'Discharged', 'Transferred', 'Cancelled'])->default('Admitted');
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
        Schema::dropIfExists('ipd_records');
    }
};
