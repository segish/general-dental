<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DefaultRolesSeeder::class,
            DepartmentSeeder::class,
            AdminTableSeeder::class,
            CurrenciesTableSeeder::class,
            BusinessSettingsTableSeeder::class,
            PharmacyCompanySettingTableSeeder::class,
            TestCategoriesSeeder::class,
            LaboratoryMachineSeeder::class,
            TestingMethodSeeder::class,
            TestsSeeder::class,
            SpecimenTypesSeeder::class,
            SpecimenOriginsSeeder::class,
            PatientsSeeder::class,
            // LaboratoryRequestSeeder::class,
            // LaboratoryRequestTestSeeder::class,
            UnitSeeder::class,
            TestAttributeSeeder::class,
            WardAndBedSeeder::class,
            MedicalConditionSeeder::class,
            MedicalRecordFieldSeeder::class,
            ServiceCategorySeeder::class,
            BillingServiceSeeder::class,
            MedicineCategorySeeder::class,
            AssessmentCategorySeeder::class,
            MedicineSeeder::class,
            ProductSeeder::class,
            PharmacyInventorySeeder::class,
            CustomerSeeder::class,
            RadiologySeeder::class,
            RadiologyAttributeSeeder::class,
            EmergencyMedicineCategorySeeder::class,
            EmergencyMedicineSeeder::class,
            EmergencyInventorySeeder::class,
            DoseIntervalSeeder::class,
        ]);
    }
}
