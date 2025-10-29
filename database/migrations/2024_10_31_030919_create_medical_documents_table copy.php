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
        Schema::create('medical_documents', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['abortion', 'consent', 'certification', 'examination','referal','laboratory request', 'circumcision' ]);
            // common
            $table->foreignId('visit_id')->constrained('visits')->onDelete('cascade');
            $table->date('date')->nullable();
            $table->string('notes')->nullable();
            $table->enum('language', allowed: ['amharic', 'english'])->default('amharic');
            $table->foreignId('filled_by')->constrained(table: 'admins')->onDelete('cascade');
            // consent
            $table->string('witness_1_name')->nullable();
            $table->string('witness_1_relationship')->nullable();
            $table->string('witness_2_name')->nullable();
            $table->string('witness_2_relationship')->nullable();
            // certefication (seek leave)
            $table->string('diagnosis')->nullable();
            $table->integer('date_of_rest')->nullable();
            // medical eximination
            $table->string('to')->nullable();
            $table->string('number')->nullable();
            // referal
            $table->string('from_hospital')->nullable();
            $table->string('to_hospital')->nullable();
            $table->string('from_department')->nullable();
            $table->string('to_department')->nullable();
            $table->string('clinical_findings')->nullable();
            $table->string('dignosis')->nullable();
            $table->string('rx_given')->nullable();
            $table->string('reason')->nullable();
            $table->unique(['visit_id', 'type', 'language']);

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
        Schema::dropIfExists('medical_documents');
    }};
