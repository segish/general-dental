<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaboratoryRequestTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('laboratory_request_test')->insert([
            [
                'laboratory_request_id' => 1,
                'test_id' => 144,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 1,
                'test_id' => 19,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 1,
                'test_id' => 24,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 2,
                'test_id' => 113,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 2,
                'test_id' => 19,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 2,
                'test_id' => 144,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 2,
                'test_id' => 1,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 3,
                'test_id' => 3,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 4,
                'test_id' => 119,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 5,
                'test_id' => 70,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 6,
                'test_id' => 5,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'laboratory_request_id' => 6,
                'test_id' => 19,
                'additional_note' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
