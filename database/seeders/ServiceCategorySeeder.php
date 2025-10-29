<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Direct Diagnosis' => ['prescription', 'medical record', 'billing service', 'diagnosis', 'lab test', 'radiology', 'vital sign'],
            'Pregnancy Monitoring' => ['prescription', 'delivery summary', 'billing service', 'diagnosis', 'lab test', 'radiology', 'vital sign', 'pregnancy', 'newborn', 'pregnancy history', 'Labour Followup'],
            'Laboratory Service' => ['lab test', 'billing service'],
            'Consultation' => ['prescription', 'diagnosis', 'billing service'],
            'Emergency' => ['diagnosis', 'vital sign', 'billing service'],
            'Follow-up' => ['medical record', 'diagnosis', 'billing service'],
            'Surgery' => ['diagnosis', 'vital sign', 'billing service'],
            'Vaccination' => ['medical record', 'billing service'],
            'Injection' => ['medical record', 'billing service'],
            'Health Check-up' => ['prescription', 'diagnosis', 'lab test', 'vital sign', 'billing service'],
            'Diagnostic Imaging' => ['radiology', 'billing service'],
            'Physical Therapy' => ['medical record', 'billing service'],
            'Pediatrics' => ['prescription', 'medical record', 'vital sign', 'billing service'],
            'Geriatrics' => ['prescription', 'medical record', 'vital sign', 'billing service'],
            'Maternity' => ['medical record', 'pregnancy', 'billing service'],
            'Specialist Consultation' => ['diagnosis', 'medical record', 'billing service'],
            'Dental' => ['diagnosis', 'medical record', 'billing service'],
            'Mental Health' => ['diagnosis', 'medical record', 'billing service'],
            'Pre-operative' => ['diagnosis', 'vital sign', 'billing service'],
            'Post-operative' => ['diagnosis', 'medical record', 'billing service'],
        ];

        foreach ($categories as $name => $types) {
            ServiceCategory::create([
                'name' => $name,
                'description' => null,
                'service_type' => implode(',', $types), // SET expects comma-separated string
            ]);
        }
    }
}
