<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RadiologyAttributeSeeder extends Seeder
{
    public function run(): void
    {
        // Attributes for radiology_id = 1 (Ultrasound Obstetrics)
        $attributesForFirst = [
            'Fetal number',
            'FHB',
            'AFI',
            'Placenta Location',
            'placenta grade',
            'presentation',
            'CRL',
            'BPD',
            'FL',
            'AC',
            'HC',
            'EFW',
            'average GA',
            'EDD',
            'Conclusion'
        ];

        // Attributes for radiology_id = 2 (Ultrasound Abdomen & Pelvis)
        $attributesForSecond = [
            'findings',
            'conclusion'
        ];

        foreach ($attributesForFirst as $attribute) {
            DB::table('radiology_attributes')->insert([
                'radiology_id'    => 1,
                'attribute_name'  => $attribute,
                'default_required' => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        foreach ($attributesForSecond as $attribute) {
            DB::table('radiology_attributes')->insert([
                'radiology_id'    => 2,
                'attribute_name'  => $attribute,
                'default_required' => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }

        $radiologyAttributes3 = [
            'technique',
            'quality',
            'fetal_lie',
            'presentation',
            'fetal_heart_rate',
            'bpd',
            'bpd_ga',
            'edd_bpd',
            'fl',
            'fl_ga',
            'avg_ga',
            'edd_avg',
            'afi_values',
            'afi_total',
            'head_neck_comment',
            'spine_comment',
            'chest_comment',
            'abdomen_comment',
            'kidney_comment',
            'bladder_comment',
            'extremities_comment',
            'placenta_location',
            'umbilical_artery_doppler',
            'ductus_venosus_flow',
            'cervical_status',
            'cervical_length',
            'adnexal_masses_status',
            'conclusion'
        ];

        foreach ($radiologyAttributes3 as $attribute) {
            DB::table('radiology_attributes')->insert([
                'radiology_id'    => 3,
                'attribute_name'  => $attribute,
                'default_required' => true,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
