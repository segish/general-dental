<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [];

        for ($i = 1; $i <= 28; $i++) {
            $products[] = [
                'medicine_id' => $i,
                'name' => DB::table('medicines')->where('id', $i)->value('name'),
                'image' => null,
                'unit_id' => rand(1, 73),
                'tax' => rand(0, 15),
                'discount' => rand(0, 20),
                'discount_type' => ['fixed', 'percentage'][rand(0, 1)],
                'low_stock_threshold' => 10,
                'expiry_alert_days' => 30,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
