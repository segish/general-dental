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
        Schema::table('radiology_attributes', function (Blueprint $table) {
            $table->enum('result_type', ['paragraph', 'short'])->default('short')->after('attribute_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('radiology_attributes', function (Blueprint $table) {
            $table->dropColumn('result_type');
        });
    }
};
