<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpecimenOriginsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert sample data for specimen origins (anatomical or physiological origin)
        DB::table('specimen_origins')->insert([
            [
                'name' => 'Intestinal',
                'description' => 'Blood drawn from a vein, typically for routine tests or blood donation.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Venous',
                'description' => 'Blood drawn from a vein, typically for routine tests or blood donation.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Arterial',
                'description' => 'Blood collected from an artery, often used for blood gas analysis or specific diagnostic tests.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Skin Biopsy',
                'description' => 'Tissue sample taken from the skin, often for pathology examination or to diagnose skin conditions.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Lung',
                'description' => 'Tissue or fluid collected from the lungs, often used to diagnose respiratory diseases.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Urinary Bladder',
                'description' => 'Urine collected directly from the urinary bladder, typically used for specific diagnostic purposes.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Cerebrospinal Fluid',
                'description' => 'Fluid collected from around the brain and spinal cord, often used for diagnostic testing of neurological conditions.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Bone Marrow',
                'description' => 'Sample taken from the bone marrow, often used for hematological testing or diagnosing blood cancers.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Saliva',
                'description' => 'Sample collected from the mouth, used for various diagnostic tests, including genetic or hormonal studies.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Capillary',
                'description' => 'Capillary',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
