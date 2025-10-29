<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RadiologySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('radiologies')->insert([
            [
                'id' => 1,
                'radiology_name'     => 'Ultrasound Obstetrics',
                'title'              => 'Ultrasound for Pregnancy Checkup',
                'description'        => 'Used to monitor the development of the fetus during pregnancy.',
                'additional_notes'   => 'Usually done in the second trimester.',
                'cost'               => 350.00,
                'time_taken_hour'    => 0,
                'time_taken_min'     => 30,
                'paper_size'         => 'A4',
                'is_inhouse'         => true,
                'paper_orientation'  => 'portrait',
                'is_active'          => true,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
            [
                'id' => 2,
                'radiology_name'     => 'Ultrasound Abdomen & Pelvis',
                'title'              => 'Ultrasound Scan of Abdomen and Pelvis',
                'description'        => 'Examines organs such as liver, kidneys, bladder, and reproductive organs.',
                'additional_notes'   => 'Patient may be asked to drink water before the scan.',
                'cost'               => 400.00,
                'time_taken_hour'    => 0,
                'time_taken_min'     => 25,
                'paper_size'         => 'A4',
                'is_inhouse'         => true,
                'paper_orientation'  => 'portrait',
                'is_active'          => true,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
                        [
                'id' => 3,
                'radiology_name'     => 'Ultrasound Obstetrics 2',
                'title'              => 'Ultrasound Obstetrics',
                'description'        => 'Used to monitor the development of the fetus during pregnancy.',
                'additional_notes'   => 'Usually done in the second trimester.',
                'cost'               => 350.00,
                'time_taken_hour'    => 0,
                'time_taken_min'     => 30,
                'paper_size'         => 'A4',
                'is_inhouse'         => true,
                'paper_orientation'  => 'portrait',
                'is_active'          => true,
                'created_at'         => now(),
                'updated_at'         => now(),
            ],
        ]);
    }
}
