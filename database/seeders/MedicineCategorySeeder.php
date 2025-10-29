<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['id' => 1, 'name' => 'Analgesics', 'description' => 'Pain relievers like Paracetamol, Ibuprofen'],
            ['id' => 2, 'name' => 'Antipyretics', 'description' => 'Fever reducers like Acetaminophen'],
            ['id' => 3, 'name' => 'Antibiotics', 'description' => 'Infection treatments like Amoxicillin, Ciprofloxacin'],
            ['id' => 4, 'name' => 'Antiseptics', 'description' => 'Disinfectants like Chlorhexidine, Povidone-Iodine'],
            ['id' => 5, 'name' => 'Antivirals', 'description' => 'For viral infections like Acyclovir, Oseltamivir'],
            ['id' => 6, 'name' => 'Antifungals', 'description' => 'For fungal infections like Fluconazole, Clotrimazole'],
            ['id' => 7, 'name' => 'Antiparasitics', 'description' => 'For parasitic infections like Albendazole, Ivermectin'],
            ['id' => 8, 'name' => 'Anti-inflammatory', 'description' => 'Reduces inflammation like Diclofenac, Naproxen'],
            ['id' => 9, 'name' => 'Antihistamines', 'description' => 'For allergies like Cetirizine, Loratadine'],
            ['id' => 10, 'name' => 'Corticosteroids', 'description' => 'For severe inflammation like Prednisone, Hydrocortisone'],
            ['id' => 11, 'name' => 'Bronchodilators', 'description' => 'For asthma like Salbutamol, Theophylline'],
            ['id' => 12, 'name' => 'Antihypertensives', 'description' => 'For blood pressure like Amlodipine, Lisinopril'],
            ['id' => 13, 'name' => 'Hypoglycemics', 'description' => 'For diabetes like Metformin, Insulin'],
            ['id' => 14, 'name' => 'Diuretics', 'description' => 'For fluid retention like Furosemide, Hydrochlorothiazide'],
            ['id' => 15, 'name' => 'Anticoagulants', 'description' => 'Blood thinners like Warfarin, Heparin'],
            ['id' => 16, 'name' => 'Psychotropics', 'description' => 'Mental health medications'],
            ['id' => 17, 'name' => 'Gastrointestinal Drugs', 'description' => 'For stomach issues like Omeprazole, Ranitidine'],
            ['id' => 18, 'name' => 'Hormonal Drugs', 'description' => 'Hormone therapies like Levothyroxine, Contraceptives'],
            ['id' => 19, 'name' => 'Ophthalmic Medications', 'description' => 'Eye drops like Timolol, Artificial Tears'],
            ['id' => 20, 'name' => 'Dermatological Agents', 'description' => 'Skin medications like Clotrimazole Cream, Hydrocortisone Cream'],
            ['id' => 21, 'name' => 'Nutritional Supplements', 'description' => 'Vitamins and minerals like Vitamin D, Iron, Folic Acid'],
        ];

        DB::table('medicine_categories')->insert($categories);
    }
}
