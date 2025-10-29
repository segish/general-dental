<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboratoryMachineSeeder extends Seeder
{
    public function run()
    {
        DB::table('laboratory_machines')->insert([
            [
                'id' => 1,
                'code' => 'MED',
                'description' => 'MEDICA easyra',
                'name' => 'MEDICA easyra',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'code' => 'ACC',
                'description' => 'Access 2',
                'name' => 'Access 2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'code' => 'CA52',
                'description' => 'Coagulation Analyzer',
                'name' => 'Coagulation Analyzer',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
