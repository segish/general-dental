<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WardAndBedSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to avoid constraint issues
        Schema::disableForeignKeyConstraints();
        DB::table('beds')->truncate();
        DB::table('wards')->truncate();
        Schema::enableForeignKeyConstraints();
        
        // Seeding wards
        $wards = [];
        for ($i = 1; $i <= 5; $i++) {
            $wards[] = [
                'ward_name' => "Ward $i",
                'description' => "This is Ward $i",
                'max_beds_capacity' => rand(5, 20),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('wards')->insert($wards);
        
        // Retrieve all wards to associate beds
        $wards = DB::table('wards')->get();

        // Seeding beds
        $beds = [];
        foreach ($wards as $ward) {
            for ($j = 1; $j <= $ward->max_beds_capacity; $j++) {
                $bedType = rand(0, 1) ? 'Standard' : 'ICU';
                $bedPrice = $bedType === 'ICU' ? 500 : 200; // Example: ICU = $500, Standard = $200

                $beds[] = [
                    'bed_number' => "B-{$ward->id}-$j",
                    'status' => 'available',
                    'type' => $bedType,
                    'price' => $bedPrice, // Add price here
                    'ward_id' => $ward->id,
                    'room_number' => "R-{$ward->id}-" . ceil($j / 2),
                    'occupancy_status' => 'normal',
                    'additional_notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        DB::table('beds')->insert($beds);
    }
}
