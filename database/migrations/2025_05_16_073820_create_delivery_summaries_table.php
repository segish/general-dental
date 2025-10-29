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
        Schema::create('delivery_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregnancy_id')->constrained('pregnancies')->onDelete('cascade');
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            $table->foreignId('delivered_by')->nullable()->constrained('admins')->onDelete('set null');

            $table->date('date')->nullable();
            $table->time('time')->nullable();

            $table->enum('delivery_mode', ['SVD', 'C-Section', 'Vacuum', 'Forceps'])->nullable();
            $table->enum('placenta', ['Completed', 'Incomplete'])->nullable();
            $table->string('cct')->nullable();
            $table->boolean('mrp')->default(false);

            $table->boolean('laceration_repair')->default(false);
            $table->enum('laceration_degree', ['1st Degree', '2nd Degree', '3rd Degree'])->nullable();

            $table->enum('amstl', ['Ergometrine', 'Oxytocine'])->nullable();
            $table->boolean('misoprostol')->default(false);
            $table->boolean('episiotomy')->default(false);

            $table->enum('newborn_type', ['Single', 'Multiple'])->nullable();
            $table->integer('newborn_count')->nullable();

            $table->enum('delivery_outcome', ['Alive', 'Stillbirth'])->nullable();
            $table->enum('stillbirth_type', ['Macerated', 'Fresh'])->nullable();

            $table->enum('obstetric_complication', ['Eclampsia', 'PPH', 'APH', 'PROM/Sepsis', 'Obstructed/Prolonged Labor', 'Ruptured Uterus'])->nullable();
            $table->enum('obstetric_management_status', ['Managed', 'Referred'])->nullable();
            $table->boolean('ruptured_uterus_repaired')->default(false);
            $table->boolean('hysterectomy')->default(false);

            $table->enum('feeding_option', ['EBF', 'RF'])->nullable();
            $table->boolean('referred_for_support')->default(false);

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
        Schema::dropIfExists('delivery_summaries');
    }
};
