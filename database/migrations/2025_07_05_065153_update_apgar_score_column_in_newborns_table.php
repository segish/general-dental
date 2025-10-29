<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateApgarScoreColumnInNewbornsTable extends Migration
{
    public function up()
    {
        Schema::table('newborns', function (Blueprint $table) {
            $table->string('apgar_score')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('newborns', function (Blueprint $table) {
            $table->integer('apgar_score')->nullable()->change();
        });
    }
}
