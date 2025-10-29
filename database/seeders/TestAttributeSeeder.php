<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestAttribute;
use App\Models\TestAttributeReference;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestAttributeSeeder extends Seeder
{
    public function run()
    {
        $items1 = [
            // Stool Test (ID: 17)
            ['id' => 1, 'test_id' => 17, 'attribute_name' => 'Odour', 'category' => 'Macroscopic'],
            ['id' => 2, 'test_id' => 17, 'attribute_name' => 'Appreance/Color', 'category' => 'Macroscopic'],
            ['id' => 3, 'test_id' => 17, 'attribute_name' => 'Consistence', 'category' => 'Macroscopic'],
            ['id' => 4, 'test_id' => 17, 'attribute_name' => 'Parasite', 'category' => 'Microscopic'],
            ['id' => 5, 'test_id' => 17, 'attribute_name' => 'Bacteria', 'category' => 'Microscopic'],
            ['id' => 6, 'test_id' => 17, 'attribute_name' => 'Wbc', 'category' => 'Microscopic'],
            ['id' => 7, 'test_id' => 17, 'attribute_name' => 'Rbc', 'category' => 'Microscopic'],
            ['id' => 8, 'test_id' => 17, 'attribute_name' => 'Pus', 'category' => 'Microscopic'],
            ['id' => 9, 'test_id' => 17, 'attribute_name' => 'Yeast', 'category' => 'Microscopic'],

            // Urine Test (ID: 22)
            ['id' => 10, 'test_id' => 22, 'attribute_name' => 'Odour', 'category' => 'Macroscopic'],
            ['id' => 11, 'test_id' => 22, 'attribute_name' => 'Appreance/Color', 'category' => 'Macroscopic'],
            ['id' => 12, 'test_id' => 22, 'attribute_name' => 'Consistence', 'category' => 'Macroscopic'],
            ['id' => 13, 'test_id' => 22, 'attribute_name' => 'Leukocyte', 'category' => 'Chemical'],
            ['id' => 14, 'test_id' => 22, 'attribute_name' => 'Nitrate', 'category' => 'Chemical'],
            ['id' => 15, 'test_id' => 22, 'attribute_name' => 'Urobilinogen', 'category' => 'Chemical'],
            ['id' => 16, 'test_id' => 22, 'attribute_name' => 'Protein', 'category' => 'Chemical'],
            ['id' => 17, 'test_id' => 22, 'attribute_name' => 'PH', 'category' => 'Chemical'],
            ['id' => 18, 'test_id' => 22, 'attribute_name' => 'S.Graphity', 'category' => 'Chemical'],
            ['id' => 19, 'test_id' => 22, 'attribute_name' => 'Ketone', 'category' => 'Chemical'],
            ['id' => 20, 'test_id' => 22, 'attribute_name' => 'Bilirubin', 'category' => 'Chemical'],
            ['id' => 21, 'test_id' => 22, 'attribute_name' => 'Glucose', 'category' => 'Chemical'],
            ['id' => 22, 'test_id' => 22, 'attribute_name' => 'Parasite', 'category' => 'Microscopic'],
            ['id' => 23, 'test_id' => 22, 'attribute_name' => 'Wbc', 'category' => 'Microscopic'],
            ['id' => 24, 'test_id' => 22, 'attribute_name' => 'Rbc', 'category' => 'Microscopic'],
            ['id' => 25, 'test_id' => 22, 'attribute_name' => 'Pus cell', 'category' => 'Microscopic'],
            ['id' => 26, 'test_id' => 22, 'attribute_name' => 'Yeast cell', 'category' => 'Microscopic'],
            ['id' => 27, 'test_id' => 22, 'attribute_name' => 'Epithelial cell', 'category' => 'Microscopic'],
            ['id' => 28, 'test_id' => 22, 'attribute_name' => 'Cast', 'category' => 'Microscopic'],
            ['id' => 29, 'test_id' => 22, 'attribute_name' => 'Crystal', 'category' => 'Microscopic'],
        ];

        $items2 = [
            ['id' => 30, 'test_id' => 77, 'attribute_name' => 'Glucose', 'unit_id' => 1],
            ['id' => 31, 'test_id' => 80, 'attribute_name' => 'Uric Acid', 'unit_id' => 1],
            ['id' => 32, 'test_id' => 79, 'attribute_name' => 'Creatinine', 'unit_id' => 1],
            ['id' => 33, 'test_id' => 78, 'attribute_name' => 'BUN/Urea', 'unit_id' => 1],
            ['id' => 34, 'test_id' => 75, 'attribute_name' => 'Bilirubin, Total', 'unit_id' => 1],
            ['id' => 35, 'test_id' => 76, 'attribute_name' => 'Bilirubin, Direct', 'unit_id' => 1],
            ['id' => 36, 'test_id' => 81, 'attribute_name' => 'Total Protein', 'unit_id' => 6],
            ['id' => 37, 'test_id' => 73, 'attribute_name' => 'ALT/SGPT', 'unit_id' => 4],
            ['id' => 38, 'test_id' => 72, 'attribute_name' => 'AST/SGOT', 'unit_id' => 4],
            ['id' => 39, 'test_id' => 74, 'attribute_name' => 'ALP', 'unit_id' => 4],
            ['id' => 40, 'test_id' => 82, 'attribute_name' => 'Albumin', 'unit_id' => 6],
            ['id' => 41, 'test_id' => 87, 'attribute_name' => 'LDH', 'unit_id' => 4],
            ['id' => 42, 'test_id' => 89, 'attribute_name' => 'GGT', 'unit_id' => 4],
            ['id' => 43, 'test_id' => 84, 'attribute_name' => 'Cholesterol', 'unit_id' => 1],
            ['id' => 44, 'test_id' => 86, 'attribute_name' => 'LDL', 'unit_id' => 1],
            ['id' => 45, 'test_id' => 85, 'attribute_name' => 'HDL', 'unit_id' => 1],
            ['id' => 46, 'test_id' => 83, 'attribute_name' => 'Triglyceride', 'unit_id' => 1],
            ['id' => 47, 'test_id' => 88, 'attribute_name' => 'Amylase', 'unit_id' => null],
            ['id' => 48, 'test_id' => 113, 'attribute_name' => 'Total T3', 'unit_id' => 74],
            ['id' => 49, 'test_id' => 114, 'attribute_name' => 'Total T4', 'unit_id' => 74],
            ['id' => 50, 'test_id' => 115, 'attribute_name' => 'Free T3', 'unit_id' => 27],
            ['id' => 51, 'test_id' => 116, 'attribute_name' => 'Free T4', 'unit_id' => 54],
            ['id' => 52, 'test_id' => 117, 'attribute_name' => 'TSH', 'unit_id' => 75],
            ['id' => 53, 'test_id' => 132, 'attribute_name' => 'AFP', 'unit_id' => 26],
            ['id' => 54, 'test_id' => 131, 'attribute_name' => 'Total PSA', 'unit_id' => 26],
            ['id' => 55, 'test_id' => 165, 'attribute_name' => 'Free PSA', 'unit_id' => 26],
            ['id' => 56, 'test_id' => 133, 'attribute_name' => 'CEA', 'unit_id' => 26],
            ['id' => 57, 'test_id' => 62, 'attribute_name' => 'Beta HCG (Quantitative)', 'unit_id' => 75],
            ['id' => 58, 'test_id' => 118, 'attribute_name' => 'FSH', 'unit_id' => 75],
            ['id' => 59, 'test_id' => 123, 'attribute_name' => 'Estradiol', 'unit_id' => 27],
            ['id' => 60, 'test_id' => 120, 'attribute_name' => 'Prolactin', 'unit_id' => 26],
            ['id' => 61, 'test_id' => 122, 'attribute_name' => 'Progesterone', 'unit_id' => 26],
            ['id' => 62, 'test_id' => 121, 'attribute_name' => 'Testosterone', 'unit_id' => 26],
            ['id' => 63, 'test_id' => 119, 'attribute_name' => 'LH', 'unit_id' => 75],
            ['id' => 64, 'test_id' => 163, 'attribute_name' => 'CKMB', 'unit_id' => 26],
            ['id' => 65, 'test_id' => 164, 'attribute_name' => 'Troponin cTnI', 'unit_id' => 26],
            ['id' => 66, 'test_id' => 166, 'attribute_name' => 'Myo', 'unit_id' => 26],
            ['id' => 67, 'test_id' => 130, 'attribute_name' => 'Vitamin D', 'unit_id' => 26],
            ['id' => 68, 'test_id' => 167, 'attribute_name' => 'PCT', 'unit_id' => 25],
            ['id' => 69, 'test_id' => 58, 'attribute_name' => 'CRP (Quantitative)', 'unit_id' => 76],

            ['id' => 70, 'test_id' => 1, 'attribute_name' => 'WBC', 'unit_id' => 79],
            ['id' => 71, 'test_id' => 1, 'attribute_name' => 'LYM', 'unit_id' => 80],
            ['id' => 72, 'test_id' => 1, 'attribute_name' => 'MON', 'unit_id' => 6],
            ['id' => 73, 'test_id' => 1, 'attribute_name' => 'GRA', 'unit_id' => 58],
            ['id' => 74, 'test_id' => 1, 'attribute_name' => 'LYM%', 'unit_id' => 81],
            ['id' => 75, 'test_id' => 1, 'attribute_name' => 'MON%', 'unit_id' => 82],
            ['id' => 76, 'test_id' => 1, 'attribute_name' => 'GRA%', 'unit_id' => 6],
            ['id' => 77, 'test_id' => 1, 'attribute_name' => 'RBC', 'unit_id' => 79],
            ['id' => 78, 'test_id' => 1, 'attribute_name' => 'HGB', 'unit_id' => 58],
            ['id' => 79, 'test_id' => 1, 'attribute_name' => 'HCT', 'unit_id' => 58],
            ['id' => 80, 'test_id' => 1, 'attribute_name' => 'MCV', 'unit_id' => 58],
            ['id' => 81, 'test_id' => 1, 'attribute_name' => 'MCH', 'unit_id' => 58],
            ['id' => 82, 'test_id' => 1, 'attribute_name' => 'MCHC', 'unit_id' => 58],
            ['id' => 83, 'test_id' => 1, 'attribute_name' => 'RDWc', 'unit_id' => 58],
            ['id' => 84, 'test_id' => 1, 'attribute_name' => 'PLT', 'unit_id' => 58],
            ['id' => 85, 'test_id' => 1, 'attribute_name' => 'PCT', 'unit_id' => 58],
            ['id' => 86, 'test_id' => 1, 'attribute_name' => 'MPV', 'unit_id' => 58],
            ['id' => 87, 'test_id' => 1, 'attribute_name' => 'PDWC', 'unit_id' => 58],

        ];

        foreach ($items1 as $item) {
            TestAttribute::create([
                'id' => $item['id'],
                'test_id' => $item['test_id'],
                'attribute_name' => $item['attribute_name'],
                'attribute_type' => 'Qualitative',
                'test_category' => $item['category'],
                'has_options' => true,
                'unit_id' => null,
                'default_required' => true,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        foreach ($items2 as $item) {
            TestAttribute::create([
                'id' => $item['id'],
                'test_id' => $item['test_id'],
                'attribute_name' => $item['attribute_name'],
                'attribute_type' => 'Quantitative',
                'test_category' => 'Result',
                'has_options' => false,
                'default_required' => true,
                'unit_id' => $item['unit_id'] ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }


        $references = [
            ['test_attribute_id' => 30, 'min_age' => 0, 'max_age' => 1, 'lower_limit' => 50, 'upper_limit' => 80, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 1, 'max_age' => 12, 'lower_limit' => 60, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 13, 'max_age' => 60, 'lower_limit' => 74, 'upper_limit' => 106, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 61, 'max_age' => 120, 'lower_limit' => 82, 'upper_limit' => 115, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 1, 'max_age' => 12, 'lower_limit' => 60, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 13, 'max_age' => 60, 'lower_limit' => 74, 'upper_limit' => 106, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 30, 'min_age' => 61, 'max_age' => 120, 'lower_limit' => 82, 'upper_limit' => 115, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 31, 'gender' => 'male', 'min_age' => 0, 'max_age' => 120, 'lower_limit' => 3.5, 'upper_limit' => 7.2, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 31, 'gender' => 'female', 'min_age' => 0, 'max_age' => 120, 'lower_limit' => 2.6, 'upper_limit' => 6.0, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 32, 'gender' => 'male', 'min_age' => 12, 'max_age' => 120, 'lower_limit' => 0.9, 'upper_limit' => 1.3, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 32, 'gender' => 'female', 'min_age' => 12, 'max_age' => 120, 'lower_limit' => 0.6, 'upper_limit' => 1.1, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 32, 'gender' => 'male', 'min_age' => 0, 'max_age' => 11, 'lower_limit' => 0.3, 'upper_limit' => 0.7, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 32, 'gender' => 'female', 'min_age' => 0, 'max_age' => 11, 'lower_limit' => 0.3, 'upper_limit' => 0.7, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 33, 'min_age' => 0, 'max_age' => 1, 'lower_limit' => 5, 'upper_limit' => 18, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 33, 'min_age' => 1, 'max_age' => 12, 'lower_limit' => 6, 'upper_limit' => 20, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 33, 'min_age' => 13, 'max_age' => 120, 'lower_limit' => 6, 'upper_limit' => 20, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 34, 'min_age' => 0, 'max_age' => 120, 'lower_limit' => 0, 'upper_limit' => 1.0, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 35, 'min_age' => 0, 'max_age' => 120, 'lower_limit' => 0, 'upper_limit' => 0.2, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 36, 'min_age' => 0, 'max_age' => 120, 'lower_limit' => 6.4, 'upper_limit' => 8.3, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 37, 'gender' => 'male', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 45, 'upper_operator' => '<='],
            ['test_attribute_id' => 37, 'gender' => 'female', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 34, 'upper_operator' => '<='],
            ['test_attribute_id' => 38, 'gender' => 'male', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 35, 'upper_operator' => '<='],
            ['test_attribute_id' => 38, 'gender' => 'female', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 31, 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'male', 'min_age' => 13, 'max_age' => 19, 'upper_limit' => 935, 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'male', 'min_age' => 20, 'max_age' => 39, 'lower_limit' => 90, 'upper_limit' => 320, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'male', 'min_age' => 40, 'max_age' => 59, 'lower_limit' => 100, 'upper_limit' => 390, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'male', 'min_age' => 61, 'max_age' => 120, 'lower_limit' => 120, 'upper_limit' => 460, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'female', 'min_age' => 13, 'max_age' => 19, 'upper_limit' => 448, 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'female', 'min_age' => 20, 'max_age' => 39, 'lower_limit' => 70, 'upper_limit' => 260, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'female', 'min_age' => 40, 'max_age' => 59, 'lower_limit' => 80, 'upper_limit' => 360, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 39, 'gender' => 'female', 'min_age' => 61, 'max_age' => 120, 'lower_limit' => 90, 'upper_limit' => 430, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 40, 'min_age' => 0, 'max_age' => 4, 'lower_limit' => 2.8, 'upper_limit' => 4.4, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 40, 'min_age' => 4, 'max_age' => 14, 'lower_limit' => 3.8, 'upper_limit' => 5.4, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 40, 'min_age' => 15, 'max_age' => 18, 'lower_limit' => 3.2, 'upper_limit' => 4.5, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 40, 'min_age' => 20, 'max_age' => 60, 'lower_limit' => 3.5, 'upper_limit' => 5.2, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 40, 'min_age' => 61, 'max_age' => 90, 'lower_limit' => 3.2, 'upper_limit' => 4.6, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 41, 'min_age' => 15, 'max_age' => 90, 'lower_limit' => 208, 'upper_limit' => 318, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 43, 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 200, 'upper_operator' => '<='],
            ['test_attribute_id' => 44, 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 100, 'upper_operator' => '<='],
            ['test_attribute_id' => 45, 'gender' => 'male', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 60, 'upper_operator' => '<='],
            ['test_attribute_id' => 45, 'gender' => 'female', 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 60, 'upper_operator' => '<='],
            ['test_attribute_id' => 46, 'min_age' => 0, 'max_age' => 120, 'upper_limit' => 200, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 48, 'lower_limit' => 0.61, 'upper_limit' => 9.22, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 49, 'lower_limit' => 12.87, 'upper_limit' => 300, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 50, 'lower_limit' => 0.26, 'upper_limit' => 32.55, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 51, 'lower_limit' => 0.078, 'upper_limit' => 7.77, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 52, 'lower_limit' => 0.1, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 53, 'lower_limit' => 15, 'upper_limit' => 400, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 54, 'lower_limit' => 2, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 55, 'lower_limit' => 0.2, 'upper_limit' => 30, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 56, 'lower_limit' => 1, 'upper_limit' => 500, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 57, 'lower_limit' => 2, 'upper_limit' => 200000, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 58, 'lower_limit' => 1, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 59, 'lower_limit' => 9, 'upper_limit' => 3000, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 60, 'lower_limit' => 1, 'upper_limit' => 200, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 61, 'lower_limit' => 1.5, 'upper_limit' => 60, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 62, 'lower_limit' => 0.2, 'upper_limit' => 15, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 63, 'lower_limit' => 1, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 64, 'lower_limit' => 0.3, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 65, 'lower_limit' => 0.1, 'upper_limit' => 50, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 66, 'lower_limit' => 2.0, 'upper_limit' => 400, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 67, 'lower_limit' => 5, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 68, 'lower_limit' => 0.1, 'upper_limit' => 100, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 69, 'lower_limit' => 0.5, 'upper_limit' => 200, 'lower_operator' => '>=', 'upper_operator' => '<='],

            ['test_attribute_id' => 70, 'lower_limit' => 5, 'upper_limit' => 10, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 71, 'lower_limit' => 1.3, 'upper_limit' => 4.0, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 72, 'lower_limit' => 0.30, 'upper_limit' => 0.70, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 73, 'lower_limit' => 2.5, 'upper_limit' => 7.5, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 74, 'lower_limit' => 25, 'upper_limit' => 40, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 75, 'lower_limit' => 3, 'upper_limit' => 7, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 76, 'lower_limit' => 50, 'upper_limit' => 75, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 77, 'lower_limit' => 4.5, 'upper_limit' => 7.5, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 78, 'lower_limit' => 14, 'upper_limit' => 17.4, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 79, 'lower_limit' => 45, 'upper_limit' => 52, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 80, 'lower_limit' => 84, 'upper_limit' => 96, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 81, 'lower_limit' => 27, 'upper_limit' => 32, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 82, 'lower_limit' => 30, 'upper_limit' => 35, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 85, 'lower_limit' => 150, 'upper_limit' => 400, 'lower_operator' => '>=', 'upper_operator' => '<='],
            ['test_attribute_id' => 86, 'lower_limit' => 8, 'upper_limit' => 15, 'lower_operator' => '>=', 'upper_operator' => '<='],
        ];

        foreach ($references as $reference) {
            TestAttributeReference::create([
                'test_attribute_id' => $reference['test_attribute_id'],
                'gender' => $reference['gender'] ?? null,
                'min_age' => $reference['min_age'] ?? null,
                'max_age' => $reference['max_age'] ?? null,
                'lower_limit' => $reference['lower_limit'] ?? null,
                'upper_limit' => $reference['upper_limit'] ?? null,
                'lower_operator' => $reference['lower_op+erator'] ?? null,
                'upper_operator' => $reference['upper_operator'] ?? null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $optionsData = [
            1  => ['Normal', 'Foul Odour', 'Sickly Odour'],
            2  => ['Brown', 'Yellow', 'Dark brown', 'Gray', 'Black', 'Green', 'Bloodly', 'Bright Red', 'Clay', 'White cream', 'White Blue', 'Blood Streak'],
            3  => ['Semi-formed', 'Formed', 'Loose', 'Watery Diarrhea', 'Hard', 'Soft', 'Mucoid', 'Mucous', 'Diarrhea with Blood', 'Mucous with Blood', 'Pus', 'Pus with Blood'],
            4  => ['No Ova of Parasite Seen', 'Trophozoite of E.histolytica seen', 'Cyst Spp. Of Entamoeba seen', 'Trophozoite of G.lambia seen', 'Cyst of G.labia seen', 'Trophozoite of T.vaginal seen', 'Larva of S.sercoralis seen', 'Ova of A.lumbricoides seen', 'Ova of Tinea Spps. Seen', 'Ova of S.mansoni seen', 'Ova of S.haematobium seen', 'Ova of H.nana seen'],
            5  => ['few', 'Moderate', 'Many', 'Full field', 'no (none)'],
            6  => ['few', 'Moderate', 'Many', 'Full field', 'no (none)'],
            7  => ['few', 'Moderate', 'Many', 'Full field', 'no (none)'],
            8  => ['few', 'Moderate', 'Many', 'Full field', 'no (none)'],
            9  => ['few', 'Moderate', 'Many', 'Full field', 'no (none)'],
            10 => ['Fruity', 'Mousy', 'Fish', 'Ammonical', 'Bumtsugar'],
            11 => ['Yellow', 'Light Yellow', 'Pale Yellow', 'Orange', 'Dark amber', 'Black', 'Red', 'Green', 'White'],
            12 => ['Clear', 'Cloud', 'Turbidity'],
            13 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            14 => ['Negative', 'Positive'],
            15 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            16 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            17 => ['5.0', '6.0', '6.5', '7.0', '7.5', '8.0', '8.5', '9.0'],
            18 => ['1.000', '1.005', '1.010', '1.015', '1.020', '1.025', '1.030'],
            19 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            20 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            21 => ['Trace', '+', '++', '+++', '++++', 'Negative'],
            22 => ['No Urine Sedimentation Seen', 'Ova of S.haematobium seen', 'Trophozoite of T.vaginal seen/LPF'],
            23 => ['few', 'Moderate', 'Many', 'full field/HPF', 'no (none)'],
            24 => ['Few', 'Moderate', 'Many', 'Full field/HPF', 'no (none)'],
            25 => ['Few', 'Moderate', 'Many', 'Full field/HPF', 'no (none)'],
            26 => ['few', 'Moderate', 'Many', 'full field/LPF', 'no (none)'],
            27 => ['few', 'Moderate', 'Many', 'Full field/LPF', 'no (none)'],
            28 => ['few', 'Moderate', 'Many', 'Full field/LPF', 'no (none)'],
            29 => ['Few', 'Moderate', 'Many', 'Full field/LPF', 'no (none)'],
        ];

        $options = [];
        foreach ($optionsData as $attributeId => $values) {
            foreach ($values as $value) {
                $options[] = [
                    'attribute_id' => $attributeId,
                    'option_value' => $value,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('attribute_options')->insert($options);
    }
}
