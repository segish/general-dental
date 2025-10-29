<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DoseIntervalSeeder extends Seeder
{
    public function run()
    {
        $doseIntervals = [
            ['id' => 1, 'name' => 'Once Daily', 'description' => 'Take medication once per day'],
            ['id' => 2, 'name' => 'Twice Daily', 'description' => 'Take medication twice per day'],
            ['id' => 3, 'name' => 'Three Times Daily', 'description' => 'Take medication three times per day'],
            ['id' => 4, 'name' => 'Every 6 Hours', 'description' => 'Take medication every 6 hours'],
            ['id' => 5, 'name' => 'Every 8 Hours', 'description' => 'Take medication every 8 hours'],
            ['id' => 6, 'name' => 'PRN', 'description' => 'Take medication as needed'],
            ['id' => 7, 'name' => 'Stat(Only ones)', 'description' => 'Take medication immediately, one time only'],
            ['id' => 8, 'name' => 'Every 4hrly', 'description' => 'Take medication every 4 hours'],
            ['id' => 9, 'name' => 'One times per week', 'description' => 'Take medication once per week'],
            ['id' => 10, 'name' => 'Two times per week', 'description' => 'Take medication twice per week'],
        ];

        DB::table('dose_intervals')->insert($doseIntervals);
    }
}
