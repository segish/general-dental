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
        Schema::create('newborns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_summary_id')->constrained()->onDelete('cascade');

            $table->string('name')->nullable();
            $table->date('bcg_date')->nullable();
            $table->boolean('polio_0')->default(false);
            $table->boolean('vit_k')->default(false);
            $table->boolean('ttc')->default(false);
            $table->boolean('baby_mother_bonding')->default(false);
            $table->integer('para')->nullable();
            $table->boolean('prom')->default(false);
            $table->integer('prom_hours')->nullable();
            $table->decimal('birth_weight', 5, 2)->nullable();
            $table->decimal('temp', 5, 2)->nullable();
            $table->integer('pr')->nullable();
            $table->integer('rr')->nullable();
            $table->boolean('hiv_counts_and_testing_offered')->default(false);
            $table->boolean('hiv_testing_accepted')->default(false);
            $table->string('hiv_test_result')->nullable();
            $table->string('arv_px_mother')->nullable();
            $table->string('arv_px_newborn')->nullable();
            $table->integer('apgar_score')->nullable();
            $table->enum('sex', ['male', 'female'])->nullable();
            $table->decimal('length_cm', 5, 2)->nullable();
            $table->decimal('head_circumference_cm', 5, 2)->nullable();
            $table->enum('term_status', ['Term', 'Preterm', 'Postterm'])->nullable();
            $table->boolean('resuscitated')->default(false);
            $table->boolean('dysmorphic_faces')->default(false);
            $table->text('neonatal_evaluation')->nullable();
            $table->text('plan')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('newborns');
    }
};
