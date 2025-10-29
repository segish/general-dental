<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EmergencyMedicineCategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('emergency_medicine_categories')->insert([
            [
                'name' => 'Injection',
                'description' => 'Includes antibiotics, vaccines, and other injectable medications.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Medical Equipment',
                'description' => 'Items like stethoscopes, thermometers, or BP monitors used during diagnosis or treatment.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'First Aid Supplies',
                'description' => 'Bandages, antiseptics, gloves, etc. used for immediate care.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Surgical Supplies',
                'description' => 'Minor surgical tools like scalpels, sutures, and forceps used inside the clinic.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Nebulizer Solutions',
                'description' => 'Solutions used with nebulizers for respiratory treatments.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
