<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaboratoryRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('laboratory_requests')->insert([
            [
                'patient_id' => 1,
                'referring_dr' => 'Dr. Abebe Kebede',
                'referring_institution' => 'Tikur Anbessa Hospital',
                'card_no' => 'CARD001',
                'hospital_ward' => 'Ward 3A',
                'requested_by' => 'physician',
                'relevant_clinical_data' => 'Patient exhibits symptoms of severe anemia.',
                'current_medication' => 'Iron supplements and multivitamins.',
                'order_status' => 'urgent',
                'fasting' => 'yes',
                'collected_by' => 1,
                'additional_note' => 'Ensure priority processing for this test.',
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'referring_dr' => 'Dr. Almaz Tadesse',
                'referring_institution' => 'Menelik II Hospital',
                'card_no' => 'CARD002',
                'hospital_ward' => 'Ward 2B',
                'requested_by' => 'self',
                'relevant_clinical_data' => 'Routine checkup for diabetes.',
                'current_medication' => 'Metformin 500mg daily.',
                'order_status' => 'routine',
                'fasting' => 'no',
                'collected_by' => 1,
                'additional_note' => 'Check glucose levels.',
                'status' => 'in process',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'referring_dr' => 'Dr. Getachew Alemayehu',
                'referring_institution' => 'St. Paul’s Hospital',
                'card_no' => 'CARD003',
                'hospital_ward' => 'Ward 1C',
                'requested_by' => 'physician',
                'relevant_clinical_data' => 'Suspected bacterial infection.',
                'current_medication' => 'Ciprofloxacin 500mg.',
                'order_status' => 'routine',
                'fasting' => 'no',
                'collected_by' => 1,
                'additional_note' => 'Request for blood culture test.',
                'status' => 'completed',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'referring_dr' => 'Dr. Hiwot Mesfin',
                'referring_institution' => 'Zewditu Memorial Hospital',
                'card_no' => 'CARD004',
                'hospital_ward' => 'Ward 4D',
                'requested_by' => 'self',
                'relevant_clinical_data' => 'Regular monitoring for thyroid disorder.',
                'current_medication' => 'Levothyroxine 75mcg.',
                'order_status' => 'urgent',
                'fasting' => 'yes',
                'collected_by' => 1,
                'additional_note' => 'Focus on TSH levels.',
                'status' => 'rejected',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'referring_dr' => 'Dr. Tsegaye Dagne',
                'referring_institution' => 'Yekatit 12 Hospital',
                'card_no' => 'CARD005',
                'hospital_ward' => 'Ward 5E',
                'requested_by' => 'physician',
                'relevant_clinical_data' => 'Patient has elevated liver enzymes.',
                'current_medication' => 'Silymarin 140mg.',
                'order_status' => 'routine',
                'fasting' => 'no',
                'collected_by' => 1,
                'additional_note' => 'Liver function test required.',
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'patient_id' => 1,
                'referring_dr' => null,
                'referring_institution' => null,
                'card_no' => null,
                'hospital_ward' => null,
                'requested_by' => 'self',
                'relevant_clinical_data' => 'Routine blood test requested by patient.',
                'current_medication' => null,
                'order_status' => 'routine',
                'fasting' => 'yes',
                'collected_by' => 1,
                'additional_note' => 'Fasting condition met for lipid profile test.',
                'status' => 'pending',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
