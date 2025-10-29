<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PHPUnit\Framework\Constraint\IsFalse;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('departments')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'description' => 'The initial point of the system and have full control of the system.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 2,
                'name' => 'Reception',
                'description' => 'The initial point of contact for patients and administrative tasks.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 3,
                'name' => 'Sample Collection Room',
                'description' => 'Room where patient samples (blood, urine, etc.) are collected for testing.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 4,
                'name' => 'Parasitology',
                'description' => 'Department focused on diagnosing parasitic infections through laboratory tests.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 5,
                'name' => 'Hematology',
                'description' => 'Department dedicated to analyzing blood and blood diseases.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 6,
                'name' => 'Clinical Chemistry',
                'description' => 'Department that conducts biochemical tests to assess the chemical components of body fluids.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 7,
                'name' => 'Serology and Immunology',
                'description' => 'Focuses on diagnosing immune system disorders and infections through blood tests.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 8,
                'name' => 'Microbiology',
                'description' => 'Department for identifying microorganisms causing infections through cultures and testing.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 9,
                'name' => 'Radiology',
                'description' => 'Department for performing imaging tests and procedures.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 10,
                'name' => 'Pharmacy',
                'description' => 'Department for dispensing medications and providing pharmaceutical services.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 11,
                'name' => 'Laboratory',
                'description' => 'Department for performing laboratory tests and procedures.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 12,
                'name' => 'Nursing',
                'description' => 'Department for providing nursing care and support to patients.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'id' => 13,
                'name' => 'Doctor',
                'description' => 'Department for providing medical care and support to patients.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
