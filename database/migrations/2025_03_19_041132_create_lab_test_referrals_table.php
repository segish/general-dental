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
        Schema::create('lab_test_referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_test_id')->constrained('tests')->onDelete('cascade');
            $table->string('referred_clinic_name')->nullable(); 
            $table->string('referred_clinic_address')->nullable(); 
            $table->string('referred_clinic_contact')->nullable(); 
            $table->dateTime('referral_date')->default(now()); 
            $table->enum('status', ['Pending', 'Completed', 'Canceled'])->default('Pending'); 
            $table->text('notes')->nullable(); 
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
        Schema::dropIfExists('lab_test_referrals');
    }
};
