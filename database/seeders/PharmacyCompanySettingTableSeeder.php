<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PharmacyCompanySettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'key' => 'company_name',
                'value' => 'Test Pharmacy',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 3,
                'key' => 'logo',
                'value' => '2021-06-12-60c493426bd7a.png',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 5,
                'key' => 'phone',
                'value' => '+251900000000',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 2,
                'key' => 'vat_reg_no',
                'value' => '123',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 6,
                'key' => 'tin_no',
                'value' => '123',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'id' => 7,
                'key' => 'address',
                'value' => 'Addis Ababa, Ethiopia',
                'created_at' => null,
                'updated_at' => null,
            ],
        ];

        DB::table('pharmacy_company_settings')->insert($data);
    }
}
