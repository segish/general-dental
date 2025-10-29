<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmergencyMedicineSeeder extends Seeder
{
    public function run()
    {
        DB::table('emergency_medicines')->insert([
            // Category ID = 1 (Injection)
            [
                'name' => 'Ceftriaxone Injection',
                'description' => 'Antibiotic used to treat various infections.',
                'unit_id' => null,
                'payment_timing' => 'prepaid',
                'item_type' => 'medication',
                'category_id' => 1,
                'low_stock_threshold' => 10,
                'expiry_alert_days' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Diclofenac Injection',
                'description' => 'Used for pain relief and inflammation.',
                'unit_id' => null,
                'payment_timing' => 'prepaid',
                'item_type' => 'medication',
                'category_id' => 1,
                'low_stock_threshold' => 15,
                'expiry_alert_days' => 45,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Dexamethasone Injection',
                'description' => 'Steroid used to treat inflammation and allergic reactions.',
                'unit_id' => null,
                'payment_timing' => 'postpaid',
                'item_type' => 'medication',
                'category_id' => 1,
                'low_stock_threshold' => 12,
                'expiry_alert_days' => 40,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Hydrocortisone Injection',
                'description' => 'Used in emergency situations for adrenal insufficiency.',
                'unit_id' => null,
                'payment_timing' => 'prepaid',
                'item_type' => 'medication',
                'category_id' => 1,
                'low_stock_threshold' => 8,
                'expiry_alert_days' => 30,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Category ID = 2 (Medical Equipment)
            [
                'name' => 'Digital Thermometer',
                'description' => 'Used to measure body temperature.',
                'unit_id' => null,
                'payment_timing' => 'postpaid',
                'item_type' => 'equipment',
                'category_id' => 2,
                'low_stock_threshold' => 5,
                'expiry_alert_days' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],

            // Category ID = 3 (First Aid Supplies)
            [
                'name' => 'Gauze Roll',
                'description' => 'Sterile dressing for wounds.',
                'unit_id' => null,
                'payment_timing' => 'prepaid',
                'item_type' => 'consumable',
                'category_id' => 3,
                'low_stock_threshold' => 20,
                'expiry_alert_days' => 60,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Surgical Gloves',
                'description' => 'Sterile gloves used during procedures.',
                'unit_id' => null,
                'payment_timing' => 'prepaid',
                'item_type' => 'consumable',
                'category_id' => 3,
                'low_stock_threshold' => 50,
                'expiry_alert_days' => 90,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
