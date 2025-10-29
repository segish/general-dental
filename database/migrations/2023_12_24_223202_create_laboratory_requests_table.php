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
        Schema::create('laboratory_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_id')->unique()->constrained('visits')->onDelete('cascade');
            $table->string('referring_dr')->nullable();
            $table->string('referring_institution')->nullable();
            $table->string('card_no')->nullable();
            $table->string('hospital_ward')->nullable();
            $table->enum('requested_by', ['physician', 'self', 'other healthcare']);
            $table->text('relevant_clinical_data')->nullable();
            $table->text('current_medication')->nullable();
            $table->enum('specimen_taken_from', ['hospital', 'laboratory'])->default('hospital');
            $table->enum('order_status', ['urgent', 'routine'])->default('routine');
            $table->enum('fasting', ['yes', 'no']);
            $table->foreignId('collected_by')->constrained(table: 'admins')->onDelete('cascade');
            $table->string('additional_note')->nullable();
            $table->enum('status', ['pending', 'in process', 'completed', 'rejected'])->default('pending');
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
        Schema::dropIfExists('laboratory_requests');
    }
};
