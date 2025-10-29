<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PatientsSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        $patients = [];

        for ($i = 0; $i < 10; $i++) { // Generate 10 patients
            $patients[] = [
                'registration_no' => strtoupper(Str::random(5)), // Generate a random 5-character code
                'registration_date' => Carbon::now(),
                'date_of_birth' => Carbon::parse('1995-10-18'),
                'full_name' => $this->generateName($i),
                'gender' => $i % 2 === 0 ? 'male' : 'female', // Alternate genders for simplicity
                'marital_status' => $i % 2 === 0 ? 'Married' : 'Single', // Alternate marital statuses for simplicity
                'blood_group' => $this->getRandomBloodGroup(),
                'phone' => $this->generateUniquePhoneNumber($patients), // Generate a unique phone number
                'address' => 'Addis Ababa',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('patients')->insert($patients);
    }

    private function generateName($index)
    {
        // Generate a simple name based on index
        $names = [
            'Abebe Teshome',
            'Marta Guta',
            'Dawit Bekele',
            'Selamawit Tesfaye',
            'Kebede Feleke',
            'Genet Mengistu',
            'Yonas Tesfamariam',
            'Hiwot Abebe',
            'Solomon Deressa',
            'Aster Zewdie',
        ];
        return $names[$index % count($names)];
    }

    private function getRandomBloodGroup()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'];
        return $bloodGroups[array_rand($bloodGroups)];
    }

    private function generateUniquePhoneNumber($patients)
    {
        do {
            $phone = '09' . rand(1000000, 9999999); // Generate a random Ethiopian phone number
            $isUnique = !in_array($phone, array_column($patients, 'phone'));
        } while (!$isUnique);

        return $phone;
    }
}
