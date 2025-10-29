<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class AddLabourFollowupToAssessmentCategories extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE assessment_categories MODIFY category_type ENUM('Vital Sign', 'Physical Tests', 'Labour Followup')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE assessment_categories MODIFY category_type ENUM('Vital Sign', 'Physical Tests')");
    }
}
