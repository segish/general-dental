<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicalRecordField;
use Carbon\Carbon;

class MedicalRecordFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fields = [
            [
                'name' => 'Chief Complaint',
                'short_code' => 'chief_complaint',
                'field_type' => 'textarea',
                'is_multiple' => false,
                'is_required' => true,
                'order' => 1,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Symptoms',
                'short_code' => 'symptoms',
                'field_type' => 'textarea',
                'is_multiple' => false,
                'is_required' => false,
                'order' => 2,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Medical History',
                'short_code' => 'medical_history',
                'field_type' => 'textarea',
                'is_multiple' => false,
                'is_required' => false,
                'order' => 3,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Additional Notes',
                'short_code' => 'additional_notes',
                'field_type' => 'textarea',
                'is_multiple' => false,
                'is_required' => false,
                'order' => 4,
                'status' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($fields as $field) {
            MedicalRecordField::updateOrCreate(
                ['short_code' => $field['short_code']],
                $field
            );
        }
    }
}
