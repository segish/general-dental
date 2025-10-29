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
            $table->integer('issued_quantity')->default(0)->after('quantity');
            $table->integer('cancelled_quantity')->default(0)->after('issued_quantity');
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
            $table->dropColumn(['issued_quantity', 'cancelled_quantity']);
        });
    }
};
