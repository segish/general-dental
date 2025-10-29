<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run()
    {
        DB::table('customers')->insert([
            [
                'fullname' => 'Mekdes Tesfaye',
                'email' => 'mekdes.tesfaye@example.com',
                'phone_number' => '0912345678',
                'address' => 'Addis Ababa, Ethiopia',
                'status' => 'active',
            ],
            [
                'fullname' => 'Tadesse Bekele',
                'email' => 'tadesse.bekele@example.com',
                'phone_number' => '0923456789',
                'address' => 'Bahir Dar, Ethiopia',
                'status' => 'active',
            ],
            [
                'fullname' => 'Fatima Mohamed',
                'email' => 'fatima.mohamed@example.com',
                'phone_number' => '0934567890',
                'address' => 'Mekelle, Ethiopia',
                'status' => 'active',
            ],
            [
                'fullname' => 'Solomon Gebremedhin',
                'email' => 'solomon.gebremedhin@example.com',
                'phone_number' => '0945678901',
                'address' => 'Gondar, Ethiopia',
                'status' => 'active',
            ],
            [
                'fullname' => 'Tena Yilma',
                'email' => 'tena.yilma@example.com',
                'phone_number' => '0956789012',
                'address' => 'Hawassa, Ethiopia',
                'status' => 'active',
            ],
        ]);
    }
}
