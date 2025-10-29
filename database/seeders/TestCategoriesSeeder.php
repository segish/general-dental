<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\IsFalse;

class TestCategoriesSeeder extends Seeder
{
    public function run()
    {
        DB::table('test_categories')->insert([
            ['id' => '1', 'name' => 'Hematology', 'description' => 'Blood cell analysis and related tests.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '2', 'name' => 'Coagulation', 'description' => 'Tests for blood clotting functions.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '3', 'name' => 'Parasitology & Urinalysis', 'description' => 'Testing for parasitic infections and analysis of urine for various conditions.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '4', 'name' => 'Serology', 'description' => 'Detection of antibodies and antigens.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '5', 'name' => 'Serology Count', 'description' => 'Advanced antibody/antigen quantification.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '6', 'name' => 'Chemistry', 'description' => 'Biochemical analysis of bodily fluids.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '7', 'name' => 'Chemistry Count', 'description' => 'Biochemical analysis of bodily fluids.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '8', 'name' => 'Endocrinology', 'description' => 'Diagnosis and monitoring of hormonal imbalances and endocrine-related conditions.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '9', 'name' => 'Tumor Marker', 'description' => 'Cancer detection and monitoring.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '10', 'name' => 'Microbiology', 'description' => 'Identification of microorganisms.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '11', 'name' => 'Pathology', 'description' => 'Examination of tissues and cells for disease diagnosis and monitoring.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '12', 'name' => 'Referral Tests', 'description' => 'Specialized tests referred for advanced analysis, often for genetic or chronic conditions.', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '13', 'name' => 'Cardiac Marker', 'description' => 'Tests used to measure cardiac markers for detecting heart damage or other cardiac conditions.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
