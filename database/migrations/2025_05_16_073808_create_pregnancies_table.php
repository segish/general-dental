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
        Schema::create('pregnancies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            // Core Info
            $table->date('lmp')->nullable();
            $table->date('edd')->nullable();
            $table->string('anc_reg_no')->nullable();
            $table->integer('gravida')->nullable();
            $table->integer('para')->nullable();
            $table->integer('children_alive')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'Prefer not to say'])->nullable();

            $table->enum('status', ['ongoing', 'completed', 'aborted'])->default('ongoing');
            $table->boolean('is_high_risk')->default(false);

            // Obstetric History & Risk Factors
            $table->boolean('previous_stillbirth_or_neonatal_loss')->default(false);
            $table->integer('spontaneous_abortions_count')->nullable();
            $table->decimal('last_birth_weight_kg', 5, 2)->nullable();
            $table->boolean('hypertension_in_last_pregnancy')->default(false);
            $table->boolean('reproductive_tract_surgery')->default(false);

            // Current Pregnancy Risk Factors
            $table->boolean('multiple_pregnancy')->default(false);
            $table->integer('mother_age')->nullable();
            $table->boolean('rh_issue')->default(false);
            $table->boolean('vaginal_bleeding')->default(false);
            $table->boolean('pelvic_mass')->default(false);
            $table->integer('booking_bp_diastolic')->nullable();
            $table->boolean('diabetes')->default(false);
            $table->boolean('renal_disease')->default(false);
            $table->boolean('cardiac_disease')->default(false);

            // General Medical Conditions
            $table->boolean('chronic_hypertension')->default(false);
            $table->boolean('substance_abuse')->default(false);
            $table->text('serious_medical_disease')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('pregnancies');
    }
};
