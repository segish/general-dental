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
        Schema::create('testing_methods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Testing Method code (e.g., CLIA)
            $table->string('description'); // Testing Method description (e.g., Chemiluminescent assay)
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
        Schema::dropIfExists('testing_methods');
    }
};
