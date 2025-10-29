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
        Schema::create('nurse_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nurse_id')->constrained('admins')->onDelete('cascade');
            $table->foreignId('visit_id')->nullable()->constrained('visits')->onDelete('set null');
            $table->foreignId('category_id')->constrained('assessment_categories')->onDelete('cascade');
            $table->string('test_name'); // Store the test name at the time of assessment
            $table->string('test_value')->nullable();
            $table->string('unit_name')->nullable(); // Store the unit used during the assessment
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at')->useCurrent();
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
        Schema::dropIfExists('nurse_assessments');
    }
};
