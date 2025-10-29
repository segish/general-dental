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
        Schema::table('medical_documents', function (Blueprint $table) {
            // Add police certificate to enum
            $table->enum('type', ['abortion', 'consent', 'certification', 'examination', 'referal', 'laboratory request', 'circumcision', 'police_certificate'])->change();

            // Examination form additional fields
            $table->text('past_diseases')->nullable();
            $table->text('hospitalization_history')->nullable();
            $table->string('self_declaration_verified')->nullable();
            $table->string('patient_signature')->nullable();
            $table->date('patient_signature_date')->nullable();
            $table->text('general_appearance')->nullable();
            $table->string('visual_acuity_od')->nullable();
            $table->string('visual_acuity_os')->nullable();
            $table->string('hearing_test')->nullable();
            $table->text('lung_examination')->nullable();
            $table->string('lung_xray')->nullable();
            $table->text('heart_condition')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('pulse')->nullable();
            $table->text('abdomen_examination')->nullable();
            $table->text('gut_examination')->nullable();
            $table->text('musculoskeletal_examination')->nullable();
            $table->text('mental_status')->nullable();
            $table->text('nervous_system_symptoms')->nullable();

            // Laboratory results
            $table->string('hiv_result')->nullable();
            $table->string('syphilis_result')->nullable();
            $table->string('hbsag_result')->nullable();
            $table->string('wbc_result')->nullable();
            $table->string('hcv_result')->nullable();
            $table->string('esr_result')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('pregnancy_test')->nullable();
            $table->string('final_medical_status')->nullable();

            // Police certificate fields
            $table->string('letter_number')->nullable();
            $table->text('issued_idea')->nullable();
            $table->date('examination_date')->nullable();
            $table->text('victim_history')->nullable();
            $table->text('injury_finding')->nullable();
            $table->text('doctor_recommendation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('medical_documents', function (Blueprint $table) {
            // Remove police certificate from enum
            $table->enum('type', ['abortion', 'consent', 'certification', 'examination', 'referal', 'laboratory request', 'circumcision'])->change();

            // Drop examination form additional fields
            $table->dropColumn([
                'past_diseases',
                'hospitalization_history',
                'self_declaration_verified',
                'patient_signature',
                'patient_signature_date',
                'general_appearance',
                'visual_acuity_od',
                'visual_acuity_os',
                'hearing_test',
                'lung_examination',
                'lung_xray',
                'heart_condition',
                'blood_pressure',
                'pulse',
                'abdomen_examination',
                'gut_examination',
                'musculoskeletal_examination',
                'mental_status',
                'nervous_system_symptoms',
                'hiv_result',
                'syphilis_result',
                'hbsag_result',
                'wbc_result',
                'hcv_result',
                'esr_result',
                'blood_group',
                'pregnancy_test',
                'final_medical_status',
                'letter_number',
                'issued_idea',
                'examination_date',
                'victim_history',
                'injury_finding',
                'doctor_recommendation'
            ]);
        });
    }
};
