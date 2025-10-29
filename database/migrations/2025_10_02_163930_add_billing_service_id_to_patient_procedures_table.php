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
        Schema::table('patient_procedures', function (Blueprint $table) {
            $table->unsignedBigInteger('billing_service_id')->nullable()->after('visit_id');
            $table->foreign('billing_service_id')->references('id')->on('billing_services')->onDelete('cascade');
            $table->dropColumn('procedure_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('patient_procedures', function (Blueprint $table) {
            $table->dropForeign(['billing_service_id']);
            $table->dropColumn('billing_service_id');
        });
    }
};
