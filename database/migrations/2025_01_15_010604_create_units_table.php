<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "μS/cm"
            $table->string('code'); // e.g., "μS/cm"
            $table->text('description'); // e.g., "Microsiemens per Centimeter"
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
}
