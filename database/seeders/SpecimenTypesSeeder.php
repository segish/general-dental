<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SpecimenTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
    *
     * @return void
     */
    public function run()
    {
        DB::table('specimen_types')->insert([
            [
                'name' => 'Serum',
                'description' => 'Clear part of the blood after clotting.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Feces',
                'description' => 'Stool sample for parasitology or microbiology.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Body Fluid',
                'description' => 'Includes cerebrospinal, pleural, or peritoneal fluid.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Sputum',
                'description' => 'Mucus from the respiratory tract.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Vaginal Discharge',
                'description' => 'Sample for gynecological testing.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Whole Blood',
                'description' => 'Unseparated blood sample.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Urine',
                'description' => 'Used for urinalysis and culture.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Capillary',
                'description' => 'Blood collected from a finger or heel prick.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Ear/Eye Swab',
                'description' => 'Swab sample for infections.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Urethral Discharge',
                'description' => 'Sample for sexually transmitted infections.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
