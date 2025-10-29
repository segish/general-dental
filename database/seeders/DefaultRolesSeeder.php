<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DefaultRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sample_collector
            = [
                'dashboard',
                'dashboard.view_samples_received_today',
                'dashboard.view_pending_sample_collections',
                'dashboard.view_rejected_samples',
                'settings',
                'settings-password',
                'patient.edit',
                'patient.update',
                'patient.list',
                'patient.delete',
                'patient.search',
                'patient.view',
                'specimen.add-new',
                'specimen.store',
                'specimen.edit',
                'specimen.update',
                'specimen.list',
                'specimen.delete',
                'specimen.search',
                'specimen.view',
            ];

        $sampleCollectorRole = Role::where('name', 'Sample Collector')->first();

        if (!$sampleCollectorRole) {
            $sampleCollectorRole = Role::create(['name' => 'Sample Collector', 'guard_name' => 'admin']);
        }

        foreach ($sample_collector as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $sampleCollectorRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $sampleCollectorRole->givePermissionTo($permission);
            }
        }

        echo 'Sample Collector role and permissions assigned successfully.';


        $nurse_permissions = [
            'dashboard',
            'dashboard.view_samples_received_today',
            'dashboard.view_pending_sample_collections',
            'dashboard.view_rejected_samples',
            'dashboard.view_todays_visit_list',
            'settings',
            'settings-password',
            'patient.edit',
            'patient.update',
            'patient.list',
            'patient.delete',
            'patient.search',
            'patient.view',
            'visit.list',
            'visit.view',
            'nurse_assessment.add-new',
            'nurse_assessment.store',
            'nurse_assessment.list',
            'nurse_assessment.update',
            'emergency_prescriptions.list',
            'emergency_prescriptions.view',
            'emergency_prescriptions.issued-status.update',

        ];

        $nurseRole = Role::where('name', 'Nurse')->first();

        if (!$nurseRole) {
            $nurseRole = Role::create(['name' => 'Nurse', 'guard_name' => 'admin']);
        }

        foreach ($nurse_permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $nurseRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $nurseRole->givePermissionTo($permission);
            }
        }

        echo 'Nurse role and permissions assigned successfully.';

        $pharmacist_permissions = [
            'dashboard',
            'dashboard.view_samples_received_today',
            'dashboard.view_pending_sample_collections',
            'dashboard.view_rejected_samples',
            'settings',
            'settings-password',
            'patient.edit',
            'patient.update',
            'patient.list',
            'patient.delete',
            'patient.search',
            'patient.view',
            'medicines.add-new',
            'medicines.store',
            'medicines.list',
            'pharmacy_inventory.add-new',
            'pharmacy_inventory.store',
            'pharmacy_inventory.edit',
            'pharmacy_inventory.update',
            'pharmacy_inventory.list',
            'pharmacy_inventory.delete',
            'pharmacy_inventory.search',
            'pharmacy_inventory.view',
            'pos.index',
            'pos.delete',
            'pos.approve_order',
            'pos.orders.updateAmount',
            'pos.orders.updateInvoice',
            'pos.with_holding_tax',
            'pos.total_tax',
            'pos.quick-view',
            'pos.variant_price',
            'pos.add-to-cart',
            'pos.remove-from-cart',
            'pos.cart_items',
            'pos.updateQuantity',
            'pos.emptyCart',
            'pos.tax',
            'pos.discount',
            'pos.customers',
            'pos.order',
            'pos.orders',
            'pos.order-details',
            'pos.invoice',
            'pos.store-keys',
            'pos.customer-store',
            'pos.orders.export',
            'pos.order-details',
            'pos.pdf',
            'pos.download',
        ];

        $pharmacistRole = Role::where('name', 'Pharmacist')->first();

        if (!$pharmacistRole) {
            $pharmacistRole = Role::create(['name' => 'Pharmacist', 'guard_name' => 'admin']);
        }

        foreach ($pharmacist_permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $pharmacistRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $pharmacistRole->givePermissionTo($permission);
            }
        }

        echo 'Pharmacist role and permissions assigned successfully.';


        $lab_permissions = [
            'dashboard',
            'dashboard.view_tests_completed_today',
            'dashboard.view_pending_tests',
            'dashboard.view_pending_test_reports',
            'dashboard.view_todays_laboratory_requests',
            'settings',
            'settings-password',
            'patient.search',
            'laboratory_request.list',
            'patient.list',
            'patient.view',
            'specimen.add-new',
            'specimen.list',
            'specimen.delete',
            'specimen.search',
            'specimen.view',
            'specimen.status.update',
            'laboratory_result.add-new',
            'laboratory_result.store',
            'laboratory_result.edit',
            'laboratory_result.update',
            'laboratory_result.list',
            'laboratory_result.delete',
            'laboratory_result.search',
            'laboratory_result.viewTestResult',
            'laboratory_result.process-status.update',
            'laboratory_result.verify-status.update',
            'laboratory_result.view',
            'laboratory_result.pdf',
        ];


        $labTechnicianRole = Role::where('name', 'Lab Technician')->first();

        if (!$labTechnicianRole) {
            $labTechnicianRole = Role::create(['name' => 'Lab Technician', 'guard_name' => 'admin']);
        }

        foreach ($lab_permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $labTechnicianRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $labTechnicianRole->givePermissionTo($permission);
            }
        }

        $doctor = [
            'dashboard',
            'dashboard.view_tests_completed_today',
            'dashboard.view_pending_tests',
            'dashboard.view_pending_test_reports',
            'dashboard.view_todays_doctor_visit_list',
            'dashboard.view_todays_visit_list',
            'settings',
            'settings-password',
            'patient.search',
            'laboratory_request.add-new',
            'laboratory_request.list',
            'laboratory_request.pdf',
            'laboratory_request.download',
            'laboratory_request.view',
            'laboratory_request.edit',
            'laboratory_request.update',
            'radiology_request.add-new',
            'radiology_request.list',
            'radiology_request.pdf',
            'radiology_request.download',
            'radiology_request.view',
            'radiology_request.edit',
            'radiology_request.update',
            'patient.list',
            'patient.view',
            'appointment_schedule.add-new',
            'appointment_schedule.store',
            'appointment_schedule.edit',
            'appointment_schedule.update',
            'appointment_schedule.list',
            'appointment_schedule.delete',
            'appointment_schedule.search',
            'appointment_schedule.view',
            'appointment.doctor_list',
            'medical_record.add-new',
            'medical_record.store',
            'medical_record.edit',
            'medical_record.update',
            'medical_record.list',
            'medical_record.delete',
            'medical_record.search',
            'medical_record.view',
            'appointment.add-new',
            'appointment.store',
            'appointment.edit',
            'appointment.update',
            'appointment.list',
            'appointment.delete',
            'appointment.search',
            'appointment.view',
            'diagnosis.add-new',
            'diagnosis.store',
            'diagnosis.edit',
            'diagnosis.update',
            'diagnosis.list',
            'diagnosis.delete',
            'diagnosis.search',
            'diagnosis.view',
            'visit.search',
            'visit.list',
            'visit.view',
            'laboratory_result.list',
            'laboratory_result.search',
            'laboratory_result.viewTestResult',
            'laboratory_result.view',
            'laboratory_result.pdf',
            'radiology_result.list',
            'radiology_result.search',
            'radiology_result.viewTestResult',
            'radiology_result.view',
            'radiology_result.pdf',
            'prescriptions.add-new',
            'prescriptions.store',
            'prescriptions.edit',
            'prescriptions.update',
            'prescriptions.list',
            'prescriptions.delete',
            'prescriptions.search',
            'emergency_prescriptions.add-new',
            'emergency_prescriptions.store',
            'emergency_prescriptions.edit',
            'emergency_prescriptions.update',
            'emergency_prescriptions.list',
            'emergency_prescriptions.delete',
            'emergency_prescriptions.search',
            'nurse_assessment.list',
            'pregnancy.add-new',
            'pregnancy.store',
            'pregnancy.edit',
            'pregnancy.update',
            'pregnancy.list',
            'pregnancy.delete',
            'pregnancy.view',

            'delivery_summary.add-new',
            'delivery_summary.store',
            'delivery_summary.edit',
            'delivery_summary.update',
            'delivery_summary.list',
            'delivery_summary.delete',
            'delivery_summary.view',

            'prenatal_visit.add-new',
            'prenatal_visit.store',
            'prenatal_visit.edit',
            'prenatal_visit.update',
            'prenatal_visit.list',
            'prenatal_visit.delete',
            'prenatal_visit.view',

            'prenatal_visit_history.add-new',
            'prenatal_visit_history.store',
            'prenatal_visit_history.edit',
            'prenatal_visit_history.update',
            'prenatal_visit_history.list',
            'prenatal_visit_history.delete',
            'prenatal_visit_history.view',

            'newborn.add-new',
            'newborn.store',
            'newborn.edit',
            'newborn.update',
            'newborn.list',
            'newborn.delete',
            'newborn.view',

            'discharge.add-new',
            'discharge.store',
            'discharge.edit',
            'discharge.update',
            'discharge.list',
            'discharge.delete',
            'discharge.view',

            'visit_document.list',
            'visit_document.update',
            'visit_document.view',
            'visit_document.store',
            'visit_document.delete',
            'visit_document.show',
            'visit_document.download',

        ];


        $doctorRole = Role::where('name', 'Doctor')->first();

        if (!$doctorRole) {
            $doctorRole = Role::create(['name' => 'Doctor', 'guard_name' => 'admin']);
        }

        foreach ($doctor as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $doctorRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $doctorRole->givePermissionTo($permission);
            }
        }

        $receptionist_permissions = [
            'dashboard',
            'dashboard.view_patient_count',
            'dashboard.view_patients_registered_today',
            'dashboard.view_pending_payments',
            'dashboard.view_todays_billings_list',
            'laboratory_request.add-new',
            'laboratory_request.list',
            'settings',
            'settings-password',
            'patient.add-new',
            'patient.store',
            'patient.edit',
            'patient.update',
            'patient.list',
            'patient.search',
            'patient.view',
            'invoice.add-new',
            'invoice.store',
            'invoice.edit',
            'invoice.update',
            'invoice.list',
            'invoice.delete',
            'invoice.search',
            'invoice.view',
            'invoice.pdf',
            'visit.add-new',
            'visit.store',
            'visit.edit',
            'visit.update',
            'visit.list',
            'visit.delete',
            'visit.search',
            'visit.view',
            'emergency_prescriptions.add-new',
            'emergency_prescriptions.store',
            'emergency_prescriptions.edit',
            'emergency_prescriptions.list',
            'service.add-service-billing',
            'prescriptions.list',
            'laboratory_result.pdf',
            'laboratory_result.list',
            'laboratory_result.search',
            'laboratory_result.viewTestResult',
            'laboratory_result.view',
        ];


        $receptionistRole = Role::where('name', 'Receptionist')->first();

        if (!$receptionistRole) {
            $receptionistRole = Role::create(['name' => 'Receptionist', 'guard_name' => 'admin']);
        }

        foreach ($receptionist_permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'admin')->first();

            if ($permission) {
                $receptionistRole->givePermissionTo($permission);
            } else {
                $permission = Permission::create(['name' => $permissionName, 'guard_name' => 'admin']);
                $receptionistRole->givePermissionTo($permission);
            }
        }

        echo 'Receptionist role and permissions assigned successfully.';
    }
}
