<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestingMethodSeeder extends Seeder
{
    public function run()
    {
        DB::table('testing_methods')->insert([
            [
                'id' => 1,
                'code' => 'SPM',
                'description' => 'Spectrophotometry',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'code' => 'ISE',
                'description' => 'Ion Selective Electrode',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'code' => 'WMM',
                'description' => 'Wet Mount Microscopy',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'code' => 'CLIA',
                'description' => 'Chemiluminescent immunoassay',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'code' => 'COA',
                'description' => 'Coagulation time assessment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'code' => 'GS',
                'description' => 'Gram Staining',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
