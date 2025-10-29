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
        Schema::create('radiology_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_id')->constrained('radiologies')->onDelete('cascade');
            $table->string('attribute_name');
            $table->boolean('default_required')->default(false);
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
        Schema::dropIfExists('radiology_attributes');
    }
};
