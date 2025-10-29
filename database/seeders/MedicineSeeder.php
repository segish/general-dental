<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $medicines = [
            // Analgesics (Pain relievers)
            ['id' => 1, 'name' => 'Paracetamol', 'description' => 'Pain reliever and fever reducer', 'category_id' => 1, 'status' => 'active'],
            ['id' => 2, 'name' => 'Ibuprofen', 'description' => 'Pain reliever and anti-inflammatory', 'category_id' => 1, 'status' => 'active'],
            ['id' => 3, 'name' => 'Aspirin', 'description' => 'Pain reliever and blood thinner', 'category_id' => 1, 'status' => 'active'],

            // Antipyretics (Fever reducers)
            ['id' => 4, 'name' => 'Acetaminophen', 'description' => 'Fever reducer and pain reliever',  'category_id' => 2, 'status' => 'active'],

            // Antibiotics
            ['id' => 5, 'name' => 'Amoxicillin', 'description' => 'Broad-spectrum antibiotic', 'category_id' => 3, 'status' => 'active'],
            ['id' => 6, 'name' => 'Ciprofloxacin', 'description' => 'Fluoroquinolone antibiotic', 'category_id' => 3, 'status' => 'active'],
            ['id' => 7, 'name' => 'Azithromycin', 'description' => 'Macrolide antibiotic', 'category_id' => 3, 'status' => 'active'],

            // Antiseptics
            ['id' => 8, 'name' => 'Chlorhexidine', 'description' => 'Antiseptic for skin disinfection', 'category_id' => 4, 'status' => 'active'],
            ['id' => 9, 'name' => 'Povidone-Iodine', 'description' => 'Antiseptic solution for wounds', 'category_id' => 4, 'status' => 'active'],

            // Antivirals
            ['id' => 10, 'name' => 'Acyclovir', 'description' => 'Treats herpes infections', 'category_id' => 5, 'status' => 'active'],
            ['id' => 11, 'name' => 'Oseltamivir', 'description' => 'Used for flu treatment', 'category_id' => 5, 'status' => 'active'],

            // Antifungals
            ['id' => 12, 'name' => 'Fluconazole', 'description' => 'Oral antifungal medication', 'category_id' => 6, 'status' => 'active'],
            ['id' => 13, 'name' => 'Clotrimazole', 'description' => 'Topical antifungal cream', 'category_id' => 6, 'status' => 'active'],

            // Antiparasitics
            ['id' => 14, 'name' => 'Albendazole', 'description' => 'Treats worm infections', 'category_id' => 7, 'status' => 'active'],
            ['id' => 15, 'name' => 'Ivermectin', 'description' => 'Used for parasitic infections', 'category_id' => 7, 'status' => 'active'],

            // Anti-inflammatory
            ['id' => 16, 'name' => 'Diclofenac', 'description' => 'NSAID for pain and inflammation', 'category_id' => 8, 'status' => 'active'],

            // Antihistamines
            ['id' => 17, 'name' => 'Cetirizine', 'description' => 'Used for allergies', 'category_id' => 9, 'status' => 'active'],

            // Corticosteroids
            ['id' => 18, 'name' => 'Prednisone', 'description' => 'Anti-inflammatory steroid', 'category_id' => 10, 'status' => 'active'],

            // Bronchodilators
            ['id' => 19, 'name' => 'Salbutamol', 'description' => 'Used for asthma and COPD', 'category_id' => 11, 'status' => 'active'],

            // Antihypertensives
            ['id' => 20, 'name' => 'Amlodipine', 'description' => 'Calcium channel blocker', 'category_id' => 12, 'status' => 'active'],
            ['id' => 21, 'name' => 'Lisinopril', 'description' => 'ACE inhibitor for high blood pressure', 'category_id' => 12, 'status' => 'active'],

            // Hypoglycemics
            ['id' => 22, 'name' => 'Metformin', 'description' => 'Controls blood sugar levels', 'category_id' => 13, 'status' => 'active'],

            // Diuretics
            ['id' => 23, 'name' => 'Furosemide', 'description' => 'Diuretic for fluid retention', 'category_id' => 14, 'status' => 'active'],

            // Anticoagulants
            ['id' => 24, 'name' => 'Warfarin', 'description' => 'Prevents blood clots', 'category_id' => 15, 'status' => 'active'],

            // Gastrointestinal Drugs
            ['id' => 25, 'name' => 'Omeprazole', 'description' => 'Treats acid reflux', 'category_id' => 17, 'status' => 'active'],

            // Ophthalmic Medications
            ['id' => 26, 'name' => 'Timolol', 'description' => 'Used for glaucoma treatment', 'category_id' => 19, 'status' => 'active'],

            // Dermatological Agents
            ['id' => 27, 'name' => 'Hydrocortisone Cream', 'description' => 'Anti-inflammatory cream',  'category_id' => 20, 'status' => 'active'],

            // Nutritional Supplements
            ['id' => 28, 'name' => 'Vitamin D', 'description' => 'Boosts bone health', 'category_id' => 21, 'status' => 'active'],
        ];

        DB::table('medicines')->insert($medicines);
    }
}
