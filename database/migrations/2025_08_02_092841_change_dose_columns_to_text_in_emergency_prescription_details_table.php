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
        Schema::table('emergency_prescription_details', function (Blueprint $table) {
            $table->text('dose_interval')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emergency_prescription_details', function (Blueprint $table) {
            $table->enum('dose_interval', ['Once Daily', 'Twice Daily', 'Three Times Daily', 'Every 6 Hours', 'Every 8 Hours'])->nullable()->default('Once Daily')->change();
        });
    }
};
