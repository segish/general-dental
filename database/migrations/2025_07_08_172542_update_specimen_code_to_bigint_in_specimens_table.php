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
        Schema::table('specimens', function (Blueprint $table) {
            $table->unsignedBigInteger('specimen_code')->change();
        });
    }

    public function down()
    {
        Schema::table('specimens', function (Blueprint $table) {
            $table->unsignedInteger('specimen_code')->change(); // revert back if needed
        });
    }
};
