<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateDeliverySummariesApgarAndDeliveryMode extends Migration
{
    public function up()
    {
        Schema::table('delivery_summaries', function (Blueprint $table) {
            // Modify delivery_mode enum using raw SQL (because enums cannot be updated directly with Blueprint)
            DB::statement("ALTER TABLE delivery_summaries MODIFY delivery_mode ENUM('SVD', 'SVD Vacuum', 'SVD Forceps', 'C-Section') NULL");

            // Rename newborn_count to apgar_score and change type to string
            $table->string('apgar_score')->nullable()->after('newborn_type');
            $table->dropColumn('newborn_count');
        });
    }

    public function down()
    {
        Schema::table('delivery_summaries', function (Blueprint $table) {
            DB::statement("ALTER TABLE delivery_summaries MODIFY delivery_mode ENUM('SVD', 'C-Section', 'Vacuum', 'Forceps') NULL");

            $table->integer('newborn_count')->nullable()->after('newborn_type');
            $table->dropColumn('apgar_score');
        });
    }
}
