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
        Schema::create('referral_slip_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            $table->string('to_department');
            $table->string('from_department');
            $table->date('date');
            $table->text('clinical_finding');
            $table->string('diagnosis');
            $table->text('investigation_result')->nullable();
            $table->text('rx_given')->nullable();
            $table->text('reasons_for_referral');
            $table->string('referred_by');
            $table->text('finding')->nullable();
            $table->text('treatment_given')->nullable();
            $table->foreignId('filled_by')->constrained(table: 'admins')->onDelete('cascade');
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
        Schema::dropIfExists('referral_slip_forms');
    }
};
