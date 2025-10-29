<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class CurrenciesTableSeeder extends Seeder
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
                'country' => 'Ethiopian Birr',
                'currency_code' => 'ETB',
                'currency_symbol' => 'ETB',
                'exchange_rate' => '1.00',
                'created_at' => null,
                'updated_at' => null,
            ],

            [
                'country' => 'US Dollar',
                'currency_code' => 'USD',
                'currency_symbol' => '$',
                'exchange_rate' => '1.00',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'country' => 'Canadian Dollar',
                'currency_code' => 'CAD',
                'currency_symbol' => 'CA$',
                'exchange_rate' => '1.00',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'country' => 'Euro',
                'currency_code' => 'EUR',
                'currency_symbol' => '€',
                'exchange_rate' => '1.00',
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'country' => 'United Arab Emirates Dirham',
                'currency_code' => 'AED',
                'currency_symbol' => 'د.إ.‏',
                'exchange_rate' => '1.00',
                'created_at' => null,
                'updated_at' => null,
            ],

        ];

        \DB::table('currencies')->insert($data);
    }
}