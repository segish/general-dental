<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AssessmentCategory;
use App\Models\LabourFollowup;

class AssessmentCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['category_type' => 'Vital Sign', 'name' => 'Blood Pressure', 'unit_id' => 33],
            ['category_type' => 'Vital Sign', 'name' => 'Heart Rate', 'unit_id' => 71],
            ['category_type' => 'Vital Sign', 'name' => 'Temperature', 'unit_id' => 72],
            ['category_type' => 'Vital Sign', 'name' => 'Oxygen Saturation', 'unit_id' => 58],
            ['category_type' => 'Vital Sign', 'name' => 'Respiratory Rate', 'unit_id' => 73],
        ];

        foreach ($categories as $category) {
            AssessmentCategory::create($category);
        }

        $followupCategories = [
            ['category_type' => 'Labour Followup', 'name' => 'Contraction Length', 'unit_id' => 33],
            ['category_type' => 'Labour Followup', 'name' => 'Contraction Freq', 'unit_id' => 33],
            ['category_type' => 'Labour Followup', 'name' => 'Contraction Type', 'unit_id' => 33],
            ['category_type' => 'Labour Followup', 'name' => 'Cervix', 'unit_id' => 8],
            ['category_type' => 'Labour Followup', 'name' => 'fh', 'unit_id' => 71],
        ];

        foreach ($followupCategories as $category) {
            AssessmentCategory::create($category);
        }
    }
}
