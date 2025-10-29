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
        Schema::create('prenatal_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->onDelete('cascade');
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            $table->integer('gestational_age')->nullable();
            $table->string('bp')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->string('pallor')->nullable();
            $table->string('uterine_height')->nullable();
            $table->string('fetal_heart_beat')->nullable();
            $table->string('presentation')->nullable();
            $table->string('urine_infection')->nullable();
            $table->string('urine_protein')->nullable();
            $table->string('rapid_syphilis_test')->nullable();
            $table->string('hemoglobin')->nullable();
            $table->string('blood_group_rh')->nullable();
            $table->string('tt_dose')->nullable();
            $table->boolean('iron_folic_acid')->default(false);
            $table->boolean('mebendazole')->default(false);
            $table->boolean('tin_use')->default(false);
            $table->string('arv_px_type')->nullable();
            $table->text('remarks')->nullable();
            $table->text('danger_signs')->nullable();
            $table->text('action_advice_counseling')->nullable();
            $table->date('next_follow_up')->nullable();
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
        Schema::dropIfExists('prenatal_visits');
    }
};
