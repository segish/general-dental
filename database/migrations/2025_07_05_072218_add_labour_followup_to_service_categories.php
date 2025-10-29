<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLabourFollowupToServiceCategories extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE service_categories 
            MODIFY service_type SET(
                'prescription',
                'medical record',
                'billing service',
                'diagnosis',
                'lab test',
                'radiology',
                'vital sign',
                'pregnancy',
                'delivery summary',
                'newborn',
                'discharge',
                'pregnancy history',
                'Labour Followup'
            ) NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE service_categories 
            MODIFY service_type SET(
                'prescription',
                'medical record',
                'billing service',
                'diagnosis',
                'lab test',
                'radiology',
                'vital sign',
                'pregnancy',
                'delivery summary',
                'newborn',
                'discharge',
                'pregnancy history'
            ) NULL
        ");
    }
}
