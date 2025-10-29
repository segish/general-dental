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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            $table->date('registration_date');
            $table->date('date_of_birth');
            $table->string('full_name');
            $table->enum('gender', ['male', 'female']);
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'Prefer not to say'])->nullable();
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable();
            $table->string('email')->nullable();
            $table->string('vat_reg_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('date_of_registration')->nullable();
            $table->string('address')->nullable();
            $table->string('woreda')->nullable();
            $table->string('kebele')->nullable();
            $table->string('house_no')->nullable();
            $table->string('phone');
            $table->boolean('is_free_patient')->default(false);
            $table->string('guardian_name')->nullable();
            $table->boolean('is_flexible_payment')->default(true);
            $table->foreignId('mother_id')->nullable()->constrained('patients')->onDelete('set null');
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
        Schema::dropIfExists('patients');
    }
};
