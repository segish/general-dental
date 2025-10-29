<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSpecimenCodeToString extends Migration
{
    public function up()
    {
        Schema::table('specimens', function (Blueprint $table) {
            $table->string('specimen_code', 20)->change(); // adjust length if needed
        });
    }

    public function down()
    {
        Schema::table('specimens', function (Blueprint $table) {
            $table->unsignedBigInteger('specimen_code')->change(); // revert
        });
    }
}
