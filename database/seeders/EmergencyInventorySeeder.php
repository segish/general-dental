<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmergencyInventorySeeder extends Seeder
{
    public function run()
    {
        DB::table('emergency_inventory')->insert([
            // Inventory for Injection Items (category_id = 1)
            [
                'emergency_medicine_id' => 1, // Ceftriaxone Injection
                'batch_number' => 'CEF-2025-001',
                'quantity' => 5,
                'buying_price' => 5.50,
                'selling_price' => 8.00,
                'expiry_date' => '2025-12-31',
                'received_date' => Carbon::now()->subDays(5),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emergency_medicine_id' => 2, // Diclofenac Injection
                'batch_number' => 'DICLO-2025-003',
                'quantity' => 120,
                'buying_price' => 2.00,
                'selling_price' => 3.50,
                'expiry_date' => '2026-01-31',
                'received_date' => Carbon::now()->subDays(10),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emergency_medicine_id' => 3, // Dexamethasone Injection
                'batch_number' => 'DEXA-2024-008',
                'quantity' => 80,
                'buying_price' => 1.80,
                'selling_price' => 3.00,
                'expiry_date' => '2025-11-15',
                'received_date' => Carbon::now()->subDays(20),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emergency_medicine_id' => 4, // Hydrocortisone Injection
                'batch_number' => 'HYDRO-2024-010',
                'quantity' => 60,
                'buying_price' => 3.50,
                'selling_price' => 6.00,
                'expiry_date' => '2025-10-01',
                'received_date' => Carbon::now()->subDays(15),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Digital Thermometer (category_id = 2)
            [
                'emergency_medicine_id' => 5,
                'batch_number' => 'THERMO-2024-007',
                'quantity' => 20,
                'buying_price' => 12.00,
                'selling_price' => 18.50,
                'expiry_date' => null,
                'received_date' => Carbon::now()->subDays(10),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Gauze Roll (category_id = 3)
            [
                'emergency_medicine_id' => 6,
                'batch_number' => 'GAUZE-2026-003',
                'quantity' => 300,
                'buying_price' => 0.50,
                'selling_price' => 1.00,
                'expiry_date' => '2026-06-30',
                'received_date' => Carbon::now()->subMonth(),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'emergency_medicine_id' => 7,
                'batch_number' => 'GLOVES-2024-005',
                'quantity' => 500,
                'buying_price' => 0.25,
                'selling_price' => 0.75,
                'expiry_date' => '2025-09-30',
                'received_date' => Carbon::now()->subWeek(),
                'supplier_id' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

        ]);
    }
}
