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
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('test_name');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('additional_notes')->nullable();
            $table->decimal('cost', 10, 2);
            $table->integer('time_taken_hour')->unsigned()->default(0);
            $table->integer('time_taken_min')->unsigned();
            $table->enum('result_type', ['multi-type', 'numeric', 'text', 'result-based', 'other'])->default('other');
            $table->foreignId('test_category_id')->constrained('test_categories')->onDelete('cascade');
            $table->foreignId('laboratory_machine_id')->nullable()->constrained('laboratory_machines')->onDelete('set null');
            $table->foreignId('testing_method_id')->nullable()->constrained('testing_methods')->onDelete('set null');
            $table->foreignId('specimen_type_id')->nullable()->constrained('specimen_types')->onDelete('set null');
            $table->enum('result_source', ['machine', 'manual'])->default('manual');

            $table->enum('paper_size', ['A4', 'A5'])->default('A4');
            $table->boolean('is_inhouse')->default(true);
            $table->enum('paper_orientation', ['portrait', 'landscape'])->default('portrait');
            $table->enum('page_display', ['single', 'group'])->default('group');
            $table->boolean('is_active')->default(false);
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
        Schema::dropIfExists('tests');
    }
};
