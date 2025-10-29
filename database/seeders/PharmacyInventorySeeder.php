<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PharmacyInventorySeeder extends Seeder
{
    public function run()
    {
        $inventory = [];

        for ($i = 1; $i <= 28; $i++) {
            $inventory[] = [
                'product_id' => $i,
                'batch_number' => 'BATCH' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'barcode' => 'BAR' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'quantity' => rand(50, 200),
                'buying_price' => rand(10, 50) + rand(0, 99) / 100,
                'selling_price' => rand(60, 100) + rand(0, 99) / 100,
                'expiry_date' => Carbon::now()->addMonths(rand(6, 24))->toDateString(),
                'received_date' => Carbon::now()->subDays(rand(0, 30))->toDateString(),
                'supplier_id' => null,
                'manufacturer' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pharmacy_inventory')->insert($inventory);
    }
}
