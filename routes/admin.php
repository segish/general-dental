<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Session;

Route::get('/remove-last-billing-session', function () {
    Session::forget('last_billing');
    return response()->json(['success' => true]);
})->name('remove_last_billing_session');
Route::group(['namespace' => 'Admin', 'as' => 'admin.'], function () {
    /*authentication*/
    Route::group(['namespace' => 'Auth', 'prefix' => 'auth', 'as' => 'auth.'], function () {
        Route::get('/code/captcha/{tmp}', 'LoginController@captcha')->name('default-captcha');
        Route::get('login', 'LoginController@login')->name('login');
        Route::post('login', 'LoginController@submit')->middleware('actch');
        Route::get('logout', 'LoginController@logout')->name('logout');
    });

    Route::group(['middleware' => ['admin']], function () {

        Route::get('settings', 'SystemController@settings')->name('settings');
        Route::post('settings', 'SystemController@settings_update');
        Route::post('settings-password', 'SystemController@settings_password_update')->name('settings-password');
        Route::get('dashboard/earning-statistics', 'SystemController@get_earning_statitics')->name('dashboard.earning-statistics');

        Route::group(['prefix' => 'pharmacy-reports', 'as' => 'pharmacy-reports.'], function () {
            Route::get('revenue', 'POSController@revenueReport')->name('revenue');
            Route::get('earning-graph', 'POSController@earningStatistics')->name('earning-graph');
            Route::get('earning-excel', 'POSController@downloadRevenueExcel')->name('earning-excel');
            Route::get('earning-pdf', 'POSController@downloadRevenuePdf')->name('earning-pdf');

            Route::get('product-performance', 'POSController@productPerformance')->name('product-performance');
            Route::get('top-selling-products', 'POSController@getTopSellingProducts')->name('top-selling-products');
            Route::get('low-selling-products', 'POSController@getLowSellingProducts')->name('low-selling-products');
            Route::get('product-performance/excel', 'POSController@downloadProductPerformanceExcel')->name('product-performance.excel');
            Route::get('product-performance/pdf', 'POSController@downloadProductPerformancePdf')->name('product-performance.pdf');
        });

        Route::group(['prefix' => 'roles', 'as' => 'roles.'], function () {
            Route::get('add-new', 'RoleController@index')->name('add-new');
            Route::get('branch-add-new', 'RoleController@branch_index')->name('branch-add-new');
            Route::post('store', 'RoleController@store')->name('store');
            Route::get('edit/{id}', 'RoleController@edit')->name('edit');
            Route::get('branch_edit/{id}', 'RoleController@branch_edit')->name('branch-edit');
            Route::post('update/{id}', 'RoleController@update')->name('update');
            Route::get('list', 'RoleController@list')->name('list');
            Route::delete('delete/{id}', 'RoleController@destroy')->name('delete');
            Route::post('search', 'RoleController@search')->name('search');
            Route::get('view/{id}', 'RoleController@view')->name('view');
        });

        Route::group(['prefix' => 'patient', 'as' => 'patient.'], function () {
            Route::get('add-new', 'PatientController@index')->name('add-new');
            Route::post('store', 'PatientController@store')->name('store');
            Route::get('edit/{id}', 'PatientController@edit')->name('edit');
            Route::post('update/{id}', 'PatientController@update')->name('update');
            Route::get('list', 'PatientController@list')->name('list');
            Route::delete('delete/{id}', 'PatientController@destroy')->name('delete');
            Route::post('search', 'PatientController@search')->name('search');
            Route::get('view/{id}', 'PatientController@view')->name('view');
            Route::get('get-patients', 'PatientController@getPatients')->name('get-patients');
        });

        Route::group(['prefix' => 'medical-record', 'as' => 'medical_record.'], function () {
            Route::get('add-new', 'MedicalRecordController@index')->name('add-new');
            Route::post('store', 'MedicalRecordController@store')->name('store');
            Route::get('edit/{id}', 'MedicalRecordController@edit')->name('edit');
            Route::post('update/{id}', 'MedicalRecordController@update')->name('update');
            Route::get('list', 'MedicalRecordController@list')->name('list');
            Route::delete('delete/{id}', 'MedicalRecordController@destroy')->name('delete');
            Route::post('search', 'MedicalRecordController@search')->name('search');
            Route::get('view/{id}', 'MedicalRecordController@view')->name('view');
        });

        Route::group(['prefix' => 'dental-chart', 'as' => 'dental_chart.'], function () {
            Route::get('add-new', 'DentalChartController@index')->name('add-new');
            Route::post('store', 'DentalChartController@store')->name('store');
            Route::get('edit/{id}', 'DentalChartController@edit')->name('edit');
            Route::post('update/{id}', 'DentalChartController@update')->name('update');
            Route::get('list', 'DentalChartController@list')->name('list');
            Route::delete('delete/{id}', 'DentalChartController@destroy')->name('delete');
            Route::get('view/{id}', 'DentalChartController@edit')->name('view');
        });

        Route::group(['prefix' => 'nurse-assessment', 'as' => 'nurse_assessment.'], function () {
            Route::get('add-new', 'NurseAssessmentController@index')->name('add-new');
            Route::post('store', 'NurseAssessmentController@store')->name('store');
            Route::get('edit/{id}', 'NurseAssessmentController@edit')->name('edit');
            Route::post('update/{id}', 'NurseAssessmentController@update')->name('update');
            Route::get('list', 'NurseAssessmentController@list')->name('list');
            Route::delete('delete/{id}', 'NurseAssessmentController@destroy')->name('delete');
            Route::post('search', 'NurseAssessmentController@search')->name('search');
            Route::get('view/{id}', 'NurseAssessmentController@view')->name('view');
        });

        Route::group(['prefix' => 'labour-followup', 'as' => 'labour_followup.'], function () {
            Route::get('add-new', 'LabourFollowupController@index')->name('add-new');
            Route::post('store', 'LabourFollowupController@store')->name('store');
            Route::get('edit/{id}', 'LabourFollowupController@edit')->name('edit');
            Route::post('update/{id}', 'LabourFollowupController@update')->name('update');
            Route::get('list', 'LabourFollowupController@list')->name('list');
            Route::delete('delete/{id}', 'LabourFollowupController@destroy')->name('delete');
            Route::post('search', 'LabourFollowupController@search')->name('search');
            Route::get('view/{id}', 'LabourFollowupController@view')->name('view');
        });
        Route::group(['prefix' => 'assessment-categories', 'as' => 'assessment-categories.'], function () {
            Route::get('add-new', 'AssessmentCategoryController@index')->name('add-new');
            Route::post('store', 'AssessmentCategoryController@store')->name('store');
            Route::get('edit/{id}', 'AssessmentCategoryController@edit')->name('edit');
            Route::put('update/{id}', 'AssessmentCategoryController@update')->name('update');
            Route::get('list', 'AssessmentCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'AssessmentCategoryController@destroy')->name('delete');
            Route::post('search', 'AssessmentCategoryController@search')->name('search');
            Route::get('view/{id}', 'AssessmentCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'diagnosis', 'as' => 'diagnosis.'], function () {
            Route::get('add-new', 'DiagnosisController@index')->name('add-new');
            Route::post('store', 'DiagnosisController@store')->name('store');
            Route::get('edit/{id}', 'DiagnosisController@edit')->name('edit');
            Route::post('update/{id}', 'DiagnosisController@update')->name('update');
            Route::get('list', 'DiagnosisController@list')->name('list');
            Route::delete('delete/{id}', 'DiagnosisController@destroy')->name('delete');
            Route::post('search', 'DiagnosisController@search')->name('search');
            Route::get('view/{id}', 'DiagnosisController@view')->name('view');
        });

        Route::group(['prefix' => 'visit', 'as' => 'visit.'], function () {
            Route::get('add-new', 'VisitController@index')->name('add-new');
            Route::post('store', 'VisitController@store')->name('store');
            Route::get('edit/{id}', 'VisitController@edit')->name('edit');
            Route::post('update/{id}', 'VisitController@update')->name('update');
            Route::get('list', 'VisitController@list')->name('list');
            Route::delete('delete/{id}', 'VisitController@destroy')->name('delete');
            Route::post('search', 'VisitController@search')->name('search');
            Route::get('view/{id}', 'VisitController@view')->name('view');
            // Billing routes
            Route::post('update_payment', 'VisitController@update_payment')->name('update_payment');
            Route::post('cancel-or-refund', 'VisitController@cancelOrRefund')->name('cancel-or-refund');
            Route::post('add-discount', 'VisitController@addDiscount')->name('add-discount');
            Route::post('remove-discount/{id}', 'VisitController@removeDiscount')->name('remove-discount');
        });


        Route::group(['prefix' => 'ipd-patient', 'as' => 'ipd_patient.'], function () {
            Route::get('add-new', 'IPDController@index')->name('add-new');
            Route::post('store', 'IPDController@store')->name('store');
            Route::get('edit/{id}', 'IPDController@edit')->name('edit');
            Route::post('update/{id}', 'IPDController@update')->name('update');
            Route::get('list', 'IPDController@list')->name('list');
            Route::delete('delete/{id}', 'IPDController@destroy')->name('delete');
            Route::post('search', 'IPDController@search')->name('search');
            Route::get('view/{id}', 'IPDController@view')->name('view');
        });

        Route::group(['prefix' => 'opd-patient', 'as' => 'opd_patient.'], function () {
            Route::get('add-new', 'OPDController@index')->name('add-new');
            Route::post('store', 'OPDController@store')->name('store');
            Route::get('edit/{id}', 'OPDController@edit')->name('edit');
            Route::post('update/{id}', 'OPDController@update')->name('update');
            Route::get('list', 'OPDController@list')->name('list');
            Route::delete('delete/{id}', 'OPDController@destroy')->name('delete');
            Route::post('search', 'OPDController@search')->name('search');
            Route::get('view/{id}', 'OPDController@view')->name('view');
        });

        Route::group(['prefix' => 'doctor', 'as' => 'doctor.'], function () {
            Route::get('add-new', 'DoctorController@index')->name('add-new');
            Route::post('store', 'DoctorController@store')->name('store');
            Route::get('edit/{id}', 'DoctorController@edit')->name('edit');
            Route::post('update/{id}', 'DoctorController@update')->name('update');
            Route::get('list', 'DoctorController@list')->name('list');
            Route::delete('delete/{id}', 'DoctorController@destroy')->name('delete');
            Route::post('search', 'DoctorController@search')->name('search');
            Route::get('view/{id}', 'DoctorController@view')->name('view');
        });

        Route::group(['prefix' => '{doctor_id}/appointment-schedule', 'as' => 'appointment_schedule.'], function () {
            Route::get('add-new', 'AppointmentTimeScheduleController@index')->name('add-new');
            Route::post('store', 'AppointmentTimeScheduleController@store')->name('store');
            Route::post('bulk', 'AppointmentTimeScheduleController@bulk')->name('bulk');
            Route::get('edit/{id}', 'AppointmentTimeScheduleController@edit')->name('edit');
            Route::put('update/{id}', 'AppointmentTimeScheduleController@update')->name('update');
            Route::get('list', 'AppointmentTimeScheduleController@list')->name('list');
            Route::get('doctor_list', 'AppointmentTimeScheduleController@doctor_list')->name('doctor_list');
            Route::delete('delete/{id}', 'AppointmentTimeScheduleController@destroy')->name('delete');
            Route::post('search', 'AppointmentTimeScheduleController@search')->name('search');
            Route::get('view/{id}', 'AppointmentTimeScheduleController@view')->name('view');
        });

        Route::group(['prefix' => 'appointment', 'as' => 'appointment.'], function () {
            Route::get('add-new', 'AppointmentController@index')->name('add-new');
            Route::get('get-patients', 'AppointmentController@getPatients')->name('get-patients');
            Route::get('get-patient-visits/{patient}', 'AppointmentController@getPatientVisits')->name('get-patient-visits');
            Route::post('store', 'AppointmentController@store')->name('store');
            Route::post('{appointment_id}/status', 'AppointmentController@updateStatus')->name('status');
            Route::get('edit/{id}', 'AppointmentController@edit')->name('edit');
            Route::post('update/{id}', 'AppointmentController@update')->name('update');
            Route::get('list', 'AppointmentController@list')->name('list');
            Route::delete('delete/{id}', 'AppointmentController@destroy')->name('delete');
            Route::post('search', 'AppointmentController@search')->name('search');
            Route::get('view/{id}', 'AppointmentController@view')->name('view');
        });

        Route::group(['prefix' => 'laboratory-request', 'as' => 'laboratory_request.'], function () {
            Route::get('add-new', 'LaboratoryRequestController@index')->name('add-new');
            Route::post('store', 'LaboratoryRequestController@store')->name('store');
            Route::post('status', 'LaboratoryRequestController@status')->name('status');
            Route::get('edit/{id}', 'LaboratoryRequestController@edit')->name('edit');
            Route::post('update/{id}', 'LaboratoryRequestController@update')->name('update');
            Route::post('add-tests', 'LaboratoryRequestController@addTests')->name('add-tests');
            Route::get('list', 'LaboratoryRequestController@list')->name('list');
            Route::get('fetchTestType', 'LaboratoryRequestController@fetchTestType')->name('fetchTestType');
            Route::get('fetchTestTypeCustom', 'LaboratoryRequestController@fetchTestTypeCustom')->name('fetchTestTypeCustom');
            Route::delete('delete/{id}', 'LaboratoryRequestController@destroy')->name('delete');
            Route::post('search', 'LaboratoryRequestController@search')->name('search');
            Route::get('view/{id}', 'LaboratoryRequestController@view')->name('view');
            Route::get('pdf/{id}', 'LaboratoryRequestController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'LaboratoryRequestController@downloadPdf')->name('download');
        });

        Route::group(['prefix' => 'radiology-request', 'as' => 'radiology_request.'], function () {
            Route::get('add-new', 'RadiologyRequestController@index')->name('add-new');
            Route::post('store', 'RadiologyRequestController@store')->name('store');
            Route::post('status', 'RadiologyRequestController@status')->name('status');
            Route::get('edit/{id}', 'RadiologyRequestController@edit')->name('edit');
            Route::post('update/{id}', 'RadiologyRequestController@update')->name('update');
            Route::get('list', 'RadiologyRequestController@list')->name('list');
            Route::get('fetchTestType', 'RadiologyRequestController@fetchTestType')->name('fetchTestType');
            Route::delete('delete/{id}', 'RadiologyRequestController@destroy')->name('delete');
            Route::post('search', 'RadiologyRequestController@search')->name('search');
            Route::get('view/{id}', 'RadiologyRequestController@view')->name('view');
            Route::get('pdf/{id}', 'RadiologyRequestController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'RadiologyRequestController@downloadPdf')->name('download');
        });

        Route::group(['prefix' => 'laboratory_result', 'as' => 'laboratory_result.'], function () {
            Route::get('add-new', 'LaboratoryResultController@index')->name('add-new');
            Route::get('/test-results/{testResultId}', 'LaboratoryResultController@viewTestResult')->name('viewTestResult');
            Route::post('store', 'LaboratoryResultController@store')->name('store');
            Route::post('store-custom', 'LaboratoryResultController@storeCustomResult')->name('store-custom');
            Route::post('process-status/update', 'LaboratoryResultController@updateProcessStatus')->name('process-status.update');
            Route::post('verify-status/update', 'LaboratoryResultController@updateVerifyStatus')->name('verify-status.update');
            Route::post('bulk-verify-status/update', 'LaboratoryResultController@bulkUpdateVerifyStatus')->name('bulk-verify-status.update');
            Route::get('edit/{id}', 'LaboratoryResultController@edit')->name('edit');
            Route::post('update/{id}', 'LaboratoryResultController@update')->name('update');
            Route::get('list', 'LaboratoryResultController@list')->name('list');
            Route::delete('delete/{id}', 'LaboratoryResultController@destroy')->name('delete');
            Route::post('search', 'LaboratoryResultController@search')->name('search');
            Route::get('pdf/{id}', 'LaboratoryResultController@generatePdf')->name('pdf');
            Route::get('grouped-pdf/{id}', 'LaboratoryResultController@groupedPdf')->name('grouped_pdf');
            Route::get('view/{id}', 'LaboratoryResultController@view')->name('view');
        });

        Route::group(['prefix' => 'radiology_result', 'as' => 'radiology_result.'], function () {
            Route::get('add-new', 'RadiologyResultController@index')->name('add-new');
            Route::get('/test-results/{testResultId}', 'RadiologyResultController@viewTestResult')->name('viewTestResult');
            Route::post('store', 'RadiologyResultController@store')->name('store');
            Route::post('process-status/update', 'RadiologyResultController@updateProcessStatus')->name('process-status.update');
            Route::post('verify-status/update', 'RadiologyResultController@updateVerifyStatus')->name('verify-status.update');
            Route::get('edit/{id}', 'RadiologyResultController@edit')->name('edit');
            Route::post('update/{id}', 'RadiologyResultController@update')->name('update');
            Route::get('list', 'RadiologyResultController@list')->name('list');
            Route::delete('delete/{id}', 'RadiologyResultController@destroy')->name('delete');
            Route::post('search', 'RadiologyResultController@search')->name('search');
            Route::get('pdf/{id}', 'RadiologyResultController@generatePdf')->name('pdf');
            Route::get('view/{id}', 'RadiologyResultController@view')->name('view');
        });

        Route::group(['prefix' => 'test', 'as' => 'test.'], function () {
            Route::get('add-new', 'TestController@index')->name('add-new');
            Route::post('store', 'TestController@store')->name('store');
            Route::post('status', 'TestController@status')->name('status');
            Route::get('edit/{id}', 'TestController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'TestController@update')->name('update');
            Route::get('list', 'TestController@list')->name('list');
            Route::delete('delete/{id}', 'TestController@destroy')->name('delete');
            Route::post('search', 'TestController@search')->name('search');
            Route::get('view/{id}', 'TestController@view')->name('view');
        });

        Route::group(['prefix' => 'radiology', 'as' => 'radiology.'], function () {
            Route::get('add-new', 'RadiologyController@index')->name('add-new');
            Route::post('store', 'RadiologyController@store')->name('store');
            Route::post('status', 'RadiologyController@status')->name('status');
            Route::get('edit/{id}', 'RadiologyController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'RadiologyController@update')->name('update');
            Route::get('list', 'RadiologyController@list')->name('list');
            Route::delete('delete/{id}', 'RadiologyController@destroy')->name('delete');
            Route::post('search', 'RadiologyController@search')->name('search');
            Route::get('view/{id}', 'RadiologyController@view')->name('view');
        });

        Route::group(['prefix' => 'test-category', 'as' => 'test_category.'], function () {
            Route::get('add-new', 'TestCategoryController@index')->name('add-new');
            Route::post('store', 'TestCategoryController@store')->name('store');
            Route::post('status', 'TestCategoryController@status')->name('status');
            Route::get('edit/{id}', 'TestCategoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'TestCategoryController@update')->name('update');
            Route::get('list', 'TestCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'TestCategoryController@destroy')->name('delete');
            Route::post('search', 'TestCategoryController@search')->name('search');
            Route::get('view/{id}', 'TestCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'specimen', 'as' => 'specimen.'], function () {
            Route::get('add-new', 'SpecimenController@index')->name('add-new');
            Route::post('store', 'SpecimenController@store')->name('store');
            Route::post('status', 'SpecimenController@status')->name('status');
            Route::post('status/update', 'SpecimenController@updateSpecimenStatus')->name('status.update');
            Route::get('edit/{id}', 'SpecimenController@edit')->name('edit');
            Route::match(['post', 'put'], 'update', 'SpecimenController@update')->name('update');
            Route::get('list', 'SpecimenController@list')->name('list');
            Route::delete('delete/{id}', 'SpecimenController@destroy')->name('delete');
            Route::post('search', 'SpecimenController@search')->name('search');
            Route::get('view/{id}', 'SpecimenController@view')->name('view');
            Route::get('get-tests', 'SpecimenController@getTests')->name('get.tests');
        });

        Route::group(['prefix' => 'test-attribute', 'as' => 'test_attribute.'], function () {
            Route::get('add-new', 'AttributeController@index')->name('add-new');
            Route::post('store', 'AttributeController@store')->name('store');
            Route::post('status', 'AttributeController@status')->name('status');
            Route::get('edit/{id}', 'AttributeController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'AttributeController@update')->name('update');
            Route::get('list', 'AttributeController@list')->name('list');
            Route::get('fetchTestAttributes', 'AttributeController@fetchTestAttributes')->name('fetchTestAttributes');
            Route::delete('delete/{id}', 'AttributeController@destroy')->name('delete');
            Route::post('search', 'AttributeController@search')->name('search');
            Route::get('view/{id}', 'AttributeController@view')->name('view');
        });

        Route::group(['prefix' => 'radiology-attribute', 'as' => 'radiology_attribute.'], function () {
            Route::get('add-new', 'RadiologyAttributeController@index')->name('add-new');
            Route::post('store', 'RadiologyAttributeController@store')->name('store');
            Route::post('status', 'RadiologyAttributeController@status')->name('status');
            Route::get('edit/{id}', 'RadiologyAttributeController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'RadiologyAttributeController@update')->name('update');
            Route::get('list', 'RadiologyAttributeController@list')->name('list');
            Route::get('fetchRadiologyAttributes', 'RadiologyAttributeController@fetchRadiologyAttributes')->name('fetchRadiologyAttributes');
            Route::delete('delete/{id}', 'RadiologyAttributeController@destroy')->name('delete');
            Route::post('search', 'RadiologyAttributeController@search')->name('search');
            Route::get('view/{id}', 'RadiologyAttributeController@view')->name('view');
        });

        Route::group(['prefix' => 'attribute-option', 'as' => 'attribute_option.'], function () {
            Route::get('add-new', 'AttributeOptionController@index')->name('add-new');
            Route::post('store', 'AttributeOptionController@store')->name('store');
            Route::post('status', 'AttributeOptionController@status')->name('status');
            Route::post('status/update', 'AttributeOptionController@updateSpecimenStatus')->name('status.update');
            Route::post('approval-status/update', 'AttributeOptionController@updateApprovalStatus')->name('approval-status.update');
            Route::get('edit/{id}', 'AttributeOptionController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'AttributeOptionController@update')->name('update');
            Route::get('list', 'AttributeOptionController@list')->name('list');
            Route::delete('delete/{id}', 'AttributeOptionController@destroy')->name('delete');
            Route::post('search', 'AttributeOptionController@search')->name('search');
            Route::get('view/{id}', 'AttributeOptionController@view')->name('view');
        });


        Route::group(['prefix' => 'specimen-type', 'as' => 'specimen_type.'], function () {
            Route::get('add-new', 'SpecimenTypeController@index')->name('add-new');
            Route::post('store', 'SpecimenTypeController@store')->name('store');
            Route::post('status', 'SpecimenTypeController@status')->name('status');
            Route::get('edit/{id}', 'SpecimenTypeController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'SpecimenTypeController@update')->name('update');
            Route::get('list', 'SpecimenTypeController@list')->name('list');
            Route::delete('delete/{id}', 'SpecimenTypeController@destroy')->name('delete');
            Route::post('search', 'SpecimenTypeController@search')->name('search');
            Route::get('view/{id}', 'SpecimenTypeController@view')->name('view');
        });

        Route::group(['prefix' => 'specimen-origin', 'as' => 'specimen_origin.'], function () {
            Route::get('add-new', 'SpecimenOriginController@index')->name('add-new');
            Route::post('store', 'SpecimenOriginController@store')->name('store');
            Route::post('status', 'SpecimenOriginController@status')->name('status');
            Route::get('edit/{id}', 'SpecimenOriginController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'SpecimenOriginController@update')->name('update');
            Route::get('list', 'SpecimenOriginController@list')->name('list');
            Route::delete('delete/{id}', 'SpecimenOriginController@destroy')->name('delete');
            Route::post('search', 'SpecimenOriginController@search')->name('search');
            Route::get('view/{id}', 'SpecimenOriginController@view')->name('view');
        });

        Route::group(['prefix' => 'medicine-categories', 'as' => 'medicine_categories.'], function () {
            Route::get('add-new', 'MedicineCategoryController@index')->name('add-new');
            Route::post('store', 'MedicineCategoryController@store')->name('store');
            Route::post('quick-store', 'MedicineCategoryController@quick_store')->name('quick-store');
            Route::post('status', 'MedicineCategoryController@status')->name('status');
            Route::get('edit/{id}', 'MedicineCategoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'MedicineCategoryController@update')->name('update');
            Route::get('list', 'MedicineCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'MedicineCategoryController@destroy')->name('delete');
            Route::post('search', 'MedicineCategoryController@search')->name('search');
            Route::get('view/{id}', 'MedicineCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'medicines', 'as' => 'medicines.'], function () {
            Route::get('add-new', 'MedicineController@index')->name('add-new');
            Route::post('store', 'MedicineController@store')->name('store');
            Route::post('quick-store', 'MedicineController@quick_store')->name('quick-store');
            Route::post('status', 'MedicineController@status')->name('status');
            Route::get('edit/{id}', 'MedicineController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'MedicineController@update')->name('update');
            Route::get('list', 'MedicineController@list')->name('list');
            Route::delete('delete/{id}', 'MedicineController@destroy')->name('delete');
            Route::post('search', 'MedicineController@search')->name('search');
            Route::get('view/{id}', 'MedicineController@view')->name('view');
        });

        Route::group(['prefix' => 'emergency-medicine-categories', 'as' => 'emergency_medicine_categories.'], function () {
            Route::get('add-new', 'EmergencyMedicineCategoryController@index')->name('add-new');
            Route::post('store', 'EmergencyMedicineCategoryController@store')->name('store');
            Route::post('status', 'EmergencyMedicineCategoryController@status')->name('status');
            Route::get('edit/{id}', 'EmergencyMedicineCategoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'EmergencyMedicineCategoryController@update')->name('update');
            Route::get('list', 'EmergencyMedicineCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'EmergencyMedicineCategoryController@destroy')->name('delete');
            Route::post('search', 'EmergencyMedicineCategoryController@search')->name('search');
            Route::get('view/{id}', 'EmergencyMedicineCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'emergency-medicines', 'as' => 'emergency-medicines.'], function () {
            Route::get('add-new', 'EmergencyMedicineController@index')->name('add-new');
            Route::post('store', 'EmergencyMedicineController@store')->name('store');
            Route::post('status', 'EmergencyMedicineController@status')->name('status');
            Route::get('edit/{id}', 'EmergencyMedicineController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'EmergencyMedicineController@update')->name('update');
            Route::get('list', 'EmergencyMedicineController@list')->name('list');
            Route::delete('delete/{id}', 'EmergencyMedicineController@destroy')->name('delete');
            Route::post('search', 'EmergencyMedicineController@search')->name('search');
            Route::get('view/{id}', 'EmergencyMedicineController@view')->name('view');
        });

        Route::group(['prefix' => 'products', 'as' => 'products.'], function () {
            Route::get('add-new', 'ProductController@index')->name('add-new');
            Route::post('store', 'ProductController@store')->name('store');
            Route::get('edit/{id}', 'ProductController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'ProductController@update')->name('update');
            Route::get('list', 'ProductController@list')->name('list');
            Route::delete('delete/{id}', 'ProductController@destroy')->name('delete');
            Route::post('search', 'ProductController@search')->name('search');
            Route::get('view/{id}', 'ProductController@view')->name('view');
        });

        // Supplier Routes
        Route::group(['prefix' => 'suppliers', 'as' => 'suppliers.'], function () {
            Route::get('add-new', 'SupplierController@index')->name('add-new');
            Route::post('store', 'SupplierController@store')->name('store');
            Route::get('edit/{id}', 'SupplierController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'SupplierController@update')->name('update');
            Route::get('list', 'SupplierController@list')->name('list');
            Route::delete('delete/{id}', 'SupplierController@destroy')->name('delete');
            Route::post('search', 'SupplierController@search')->name('search');
            Route::get('view/{id}', 'SupplierController@view')->name('view');
        });

        Route::group(['prefix' => 'pharmacy-stock-adjustments', 'as' => 'pharmacy_stock_adjustments.'], function () {
            Route::get('add-new', 'PharmacyStockAdjustmentController@index')->name('add-new');
            Route::post('store', 'PharmacyStockAdjustmentController@store')->name('store');
            Route::get('edit/{id}', 'PharmacyStockAdjustmentController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PharmacyStockAdjustmentController@update')->name('update');
            Route::get('list', 'PharmacyStockAdjustmentController@list')->name('list');
            Route::delete('delete/{id}', 'PharmacyStockAdjustmentController@destroy')->name('delete');
            Route::post('search', 'PharmacyStockAdjustmentController@search')->name('search');
            Route::get('view/{id}', 'PharmacyStockAdjustmentController@view')->name('view');
        });

        // Pharmacy Inventory Routes
        Route::group(['prefix' => 'pharmacy-inventory', 'as' => 'pharmacy_inventory.'], function () {
            Route::get('add-new', 'PharmacyInventoryController@index')->name('add-new');
            Route::post('store', 'PharmacyInventoryController@store')->name('store');
            Route::get('edit/{id}', 'PharmacyInventoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PharmacyInventoryController@update')->name('update');
            Route::get('list', 'PharmacyInventoryController@list')->name('list');
            Route::delete('delete/{id}', 'PharmacyInventoryController@destroy')->name('delete');
            Route::post('search', 'PharmacyInventoryController@search')->name('search');
            Route::get('view/{id}', 'PharmacyInventoryController@view')->name('view');
        });

        // Emergency Inventory Routes
        Route::group(['prefix' => 'emergency-inventory', 'as' => 'emergency_inventory.'], function () {
            Route::get('add-new', 'EmergencyInventoryController@index')->name('add-new');
            Route::post('store', 'EmergencyInventoryController@store')->name('store');
            Route::get('edit/{id}', 'EmergencyInventoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'EmergencyInventoryController@update')->name('update');
            Route::get('list', 'EmergencyInventoryController@list')->name('list');
            Route::delete('delete/{id}', 'EmergencyInventoryController@destroy')->name('delete');
            // Route::post('search', 'EmergencyInventoryController@search')->name('search');
            Route::get('view/{id}', 'EmergencyInventoryController@view')->name('view');
        });

        // Prescription Routes
        Route::group(['prefix' => 'prescriptions', 'as' => 'prescriptions.'], function () {
            Route::get('add-new', 'PrescriptionController@index')->name('add-new');
            Route::post('store', 'PrescriptionController@store')->name('store');
            Route::get('edit/{id}', 'PrescriptionController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PrescriptionController@update')->name('update');
            Route::get('list', 'PrescriptionController@list')->name('list');
            Route::delete('delete/{id}', 'PrescriptionController@destroy')->name('delete');
            Route::post('search', 'PrescriptionController@search')->name('search');
            Route::get('view/{id}', 'PrescriptionController@view')->name('view');
            Route::get('pdf/{id}', 'PrescriptionController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'PrescriptionController@downloadPdf')->name('download');
        });


        // Prescription Routes
        Route::group(['prefix' => 'emergency_prescriptions', 'as' => 'emergency_prescriptions.'], function () {
            Route::get('add-new', 'EmergencyPrescriptionController@index')->name('add-new');
            Route::post('store', 'EmergencyPrescriptionController@store')->name('store');
            Route::get('edit/{id}', 'EmergencyPrescriptionController@edit')->name('edit');
            Route::post('issued-status/update', 'EmergencyPrescriptionController@updateIssuedStatus')->name('issued-status.update');
            Route::match(['post', 'put'], 'update/{id}', 'EmergencyPrescriptionController@update')->name('update');
            Route::get('list', 'EmergencyPrescriptionController@list')->name('list');
            Route::delete('delete/{id}', 'EmergencyPrescriptionController@destroy')->name('delete');
            Route::post('search', 'EmergencyPrescriptionController@search')->name('search');
            Route::get('view/{id}', 'EmergencyPrescriptionController@view')->name('view');
        });

        // Prescription Details Routes
        Route::group(['prefix' => 'prescription-details', 'as' => 'prescription_details.'], function () {
            Route::get('add-new', 'PrescriptionDetailController@index')->name('add-new');
            Route::post('store', 'PrescriptionDetailController@store')->name('store');
            Route::get('edit/{id}', 'PrescriptionDetailController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PrescriptionDetailController@update')->name('update');
            Route::get('list', 'PrescriptionDetailController@list')->name('list');
            Route::delete('delete/{id}', 'PrescriptionDetailController@destroy')->name('delete');
            Route::post('search', 'PrescriptionDetailController@search')->name('search');
            Route::get('view/{id}', 'PrescriptionDetailController@view')->name('view');
        });

        // Sales Routes
        Route::group(['prefix' => 'sales', 'as' => 'sales.'], function () {
            Route::get('add-new', 'SaleController@index')->name('add-new');
            Route::post('store', 'SaleController@store')->name('store');
            Route::get('edit/{id}', 'SaleController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'SaleController@update')->name('update');
            Route::get('list', 'SaleController@list')->name('list');
            Route::delete('delete/{id}', 'SaleController@destroy')->name('delete');
            Route::post('search', 'SaleController@search')->name('search');
            Route::get('view/{id}', 'SaleController@view')->name('view');
        });

        // Sale Details Routes
        Route::group(['prefix' => 'sale-details', 'as' => 'sale_details.'], function () {
            Route::get('add-new', 'SaleDetailController@index')->name('add-new');
            Route::post('store', 'SaleDetailController@store')->name('store');
            Route::get('edit/{id}', 'SaleDetailController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'SaleDetailController@update')->name('update');
            Route::get('list', 'SaleDetailController@list')->name('list');
            Route::delete('delete/{id}', 'SaleDetailController@destroy')->name('delete');
            Route::post('search', 'SaleDetailController@search')->name('search');
            Route::get('view/{id}', 'SaleDetailController@view')->name('view');
        });


        // Route::group(['prefix' => 'specimen', 'as' => 'specimen.'], function () {
        //     Route::get('add-new', 'SpecimenController@index')->name('add-new');
        //     Route::post('store', 'SpecimenController@store')->name('store');
        //     Route::post('status', 'SpecimenController@status')->name('status');
        //     Route::get('edit/{id}', 'SpecimenController@edit')->name('edit');
        //     Route::post('update/{id}', 'SpecimenController@update')->name('update');
        //     Route::get('list', 'SpecimenController@list')->name('list');
        //     Route::delete('delete/{id}', 'SpecimenController@destroy')->name('delete');
        //     Route::post('search', 'SpecimenController@search')->name('search');
        //     Route::get('view/{id}', 'SpecimenController@view')->name('view');
        // });

        Route::group(['prefix' => 'invoice', 'as' => 'invoice.'], function () {
            Route::get('payment-list', 'BillingController@paymentList')->name('payment-list');
            Route::post('update_payment', 'BillingController@update_payment')->name('update_payment');
            Route::post('cancel-or-refund', 'BillingController@cancelOrRefund')->name('cancel-or-refund');
            Route::post('add-discount', 'BillingController@addDiscount')->name('add-discount');
            Route::post('/billing/{id}/remove-discount', 'BillingController@removeDiscount')->name('remove-discount');
            Route::get('edit/{id}', 'BillingController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'BillingController@update')->name('update');
            Route::get('list', 'BillingController@list')->name('list');
            Route::delete('delete/{id}', 'BillingController@destroy')->name('delete');
            Route::post('search', 'BillingController@search')->name('search');
            Route::get('view/{id}', 'BillingController@view')->name('view');
            Route::get('view/{id}', 'BillingController@view')->name('view');
            Route::get('pdf/{id}', 'BillingController@generatePdf')->name('pdf');
            Route::get('download', 'BillingController@downloadBillingReport')->name('download');
        });

        Route::group(['prefix' => 'reports', 'as' => 'reports.'], function () {
            Route::get('test', 'ReportController@testReport')->name('test'); // Test Reports
            Route::get('revenue', 'ReportController@revenueReport')->name('revenue'); // Revenue Reports
            Route::get('patients', 'ReportController@patientReport')->name('patients'); // Patient Reports
            Route::get('disease', 'ReportController@DiseaseReport')->name('disease'); // Specimen Reports
            Route::get('revenue/excel', 'ReportController@downloadExcel')->name('revenue.excel');
            Route::get('revenue/pdf', 'ReportController@downloadPDF')->name('revenue.pdf');
            Route::get('patients/{report_type}', 'ReportController@generatePatientReport')->name('patients.download');
            // Route::get('patients/{report_type}', 'ReportController@downloadPatientDemographicsReport')->name('download_patient_demographics');
        });

        Route::group(['prefix' => 'department', 'as' => 'department.'], function () {
            Route::get('add-new', 'DepartmentController@index')->name('add-new');
            Route::post('store', 'DepartmentController@store')->name('store');
            Route::get('edit/{id}', 'DepartmentController@edit')->name('edit');
            Route::put('update/{id}', 'DepartmentController@update')->name('update');
            Route::get('list', 'DepartmentController@list')->name('list');
            Route::delete('delete/{id}', 'DepartmentController@destroy')->name('delete');
            Route::post('search', 'DepartmentController@search')->name('search');
            Route::get('view/{id}', 'DepartmentController@view')->name('view');
        });

        Route::group(['prefix' => 'laboratory-machine', 'as' => 'laboratory-machine.'], function () {
            Route::get('add-new', 'LaboratoryMachineController@index')->name('add-new');
            Route::post('store', 'LaboratoryMachineController@store')->name('store');
            Route::get('edit/{id}', 'LaboratoryMachineController@edit')->name('edit');
            Route::put('update/{id}', 'LaboratoryMachineController@update')->name('update');
            Route::get('list', 'LaboratoryMachineController@list')->name('list');
            Route::delete('delete/{id}', 'LaboratoryMachineController@destroy')->name('delete');
            Route::post('search', 'LaboratoryMachineController@search')->name('search');
            Route::get('view/{id}', 'LaboratoryMachineController@view')->name('view');
        });

        Route::group(['prefix' => 'testing-method', 'as' => 'testing-method.'], function () {
            Route::get('add-new', 'TestingMethodController@index')->name('add-new');
            Route::post('store', 'TestingMethodController@store')->name('store');
            Route::get('edit/{id}', 'TestingMethodController@edit')->name('edit');
            Route::put('update/{id}', 'TestingMethodController@update')->name('update');
            Route::get('list', 'TestingMethodController@list')->name('list');
            Route::delete('delete/{id}', 'TestingMethodController@destroy')->name('delete');
            Route::post('search', 'TestingMethodController@search')->name('search');
            Route::get('view/{id}', 'TestingMethodController@view')->name('view');
        });

        Route::group(['prefix' => 'unit', 'as' => 'unit.'], function () {
            Route::get('add-new', 'UnitController@index')->name('add-new');
            Route::post('store', 'UnitController@store')->name('store');
            Route::get('edit/{id}', 'UnitController@edit')->name('edit');
            Route::put('update/{id}', 'UnitController@update')->name('update');
            Route::get('list', 'UnitController@list')->name('list');
            Route::delete('delete/{id}', 'UnitController@destroy')->name('delete');
        });

        Route::group(['prefix' => 'permissions', 'as' => 'permissions.'], function () {
            Route::get('add-new', 'PermissionsController@index')->name('add-new');
            Route::post('store', 'PermissionsController@store')->name('store');
            Route::get('edit/{id}', 'PermissionsController@edit')->name('edit');
            Route::post('update/{id}', 'PermissionsController@update')->name('update');
            Route::get('list', 'PermissionsController@list')->name('list');
            Route::get('permission-list', 'PermissionsController@listPermission')->name('list-permission');
            Route::delete('delete/{id}', 'PermissionsController@destroy')->name('delete');
            Route::post('search', 'PermissionsController@search')->name('search');
            Route::get('view/{id}', 'PermissionsController@view')->name('view');
        });
        Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
            Route::get('add-new', 'AdminUserController@index')->name('add-new');
            Route::post('store', 'AdminUserController@store')->name('store');
            Route::get('edit/{id}', 'AdminUserController@edit')->name('edit');
            Route::post('update/{id}', 'AdminUserController@update')->name('update');
            Route::get('list', 'AdminUserController@list')->name('list');
            Route::delete('delete/{id}', 'AdminUserController@destroy')->name('delete');
            Route::post('search', 'AdminUserController@search')->name('search');
            Route::get('view/{id}', 'AdminUserController@view')->name('view');
        });

        Route::group(['prefix' => 'service', 'as' => 'service.'], function () {
            Route::get('add-new', 'ServiceController@index')->name('add-new');
            Route::post('store', 'ServiceController@store')->name('store');
            Route::get('edit/{id}', 'ServiceController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'ServiceController@update')->name('update');
            Route::get('list', 'ServiceController@list')->name('list');
            Route::delete('delete/{id}', 'ServiceController@destroy')->name('delete');
            Route::post('search', 'ServiceController@search')->name('search');
            Route::get('view/{id}', 'ServiceController@view')->name('view');
            Route::post('add-service-billing', 'ServiceController@add_service_billing')->name('add-service-billing');
        });

        Route::group(['prefix' => 'service-category', 'as' => 'service_category.'], function () {
            Route::get('add-new', 'ServiceCategoryController@index')->name('add-new');
            Route::post('store', 'ServiceCategoryController@store')->name('store');
            Route::get('edit/{id}', 'ServiceCategoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'ServiceCategoryController@update')->name('update');
            Route::get('list', 'ServiceCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'ServiceCategoryController@destroy')->name('delete');
            Route::post('search', 'ServiceCategoryController@search')->name('search');
            Route::get('view/{id}', 'ServiceCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'pregnancy', 'as' => 'pregnancy.'], function () {
            Route::get('add-new', 'PregnancyController@index')->name('add-new');
            Route::post('store', 'PregnancyController@store')->name('store');
            Route::get('edit/{id}', 'PregnancyController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PregnancyController@update')->name('update');
            Route::get('list', 'PregnancyController@list')->name('list');
            Route::delete('delete/{id}', 'PregnancyController@destroy')->name('delete');
            Route::get('view/{id}', 'PregnancyController@view')->name('view');
        });

        Route::group(['prefix' => 'delivery-summary', 'as' => 'delivery_summary.'], function () {
            Route::get('add-new', 'DeliverySummaryController@index')->name('add-new');
            Route::post('store', 'DeliverySummaryController@store')->name('store');
            Route::get('edit/{id}', 'DeliverySummaryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'DeliverySummaryController@update')->name('update');
            Route::get('list', 'DeliverySummaryController@list')->name('list');
            Route::delete('delete/{id}', 'DeliverySummaryController@destroy')->name('delete');
            Route::get('view/{id}', 'DeliverySummaryController@view')->name('view');
        });

        Route::group(['prefix' => 'prenatal-visit', 'as' => 'prenatal_visit.'], function () {
            Route::get('add-new', 'PrenatalVisitController@index')->name('add-new');
            Route::post('store', 'PrenatalVisitController@store')->name('store');
            Route::get('edit/{id}', 'PrenatalVisitController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PrenatalVisitController@update')->name('update');
            Route::get('list', 'PrenatalVisitController@list')->name('list');
            Route::delete('delete/{id}', 'PrenatalVisitController@destroy')->name('delete');
            Route::get('view/{id}', 'PrenatalVisitController@show')->name('view');
        });

        Route::group(['prefix' => 'prenatal-visit-history', 'as' => 'prenatal_visit_history.'], function () {
            Route::get('add-new', 'PrenatalVisitHistoryController@index')->name('add-new');
            Route::post('store', 'PrenatalVisitHistoryController@store')->name('store');
            Route::get('edit/{id}', 'PrenatalVisitHistoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'PrenatalVisitHistoryController@update')->name('update');
            Route::get('list', 'PrenatalVisitHistoryController@list')->name('list');
            Route::delete('delete/{id}', 'PrenatalVisitHistoryController@destroy')->name('delete');
            Route::get('view/{id}', 'PrenatalVisitHistoryController@view')->name('view');
        });

        Route::group(['prefix' => 'newborn', 'as' => 'newborn.'], function () {
            Route::get('add-new', 'NewbornController@index')->name('add-new');
            Route::post('store', 'NewbornController@store')->name('store');
            Route::get('edit/{id}', 'NewbornController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'NewbornController@update')->name('update');
            Route::get('list', 'NewbornController@list')->name('list');
            Route::delete('delete/{id}', 'NewbornController@destroy')->name('delete');
            Route::get('view/{id}', 'NewbornController@view')->name('view');
        });

        Route::group(['prefix' => 'discharge', 'as' => 'discharge.'], function () {
            Route::get('add-new', 'DischargeController@index')->name('add-new');
            Route::post('store', 'DischargeController@store')->name('store');
            Route::get('edit/{id}', 'DischargeController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'DischargeController@update')->name('update');
            Route::get('list', 'DischargeController@list')->name('list');
            Route::delete('delete/{id}', 'DischargeController@destroy')->name('delete');
            Route::get('view/{id}', 'DischargeController@view')->name('view');
        });

        Route::group(['prefix' => 'ward', 'as' => 'ward.'], function () {
            Route::get('add-new', 'WardController@index')->name('add-new');
            Route::post('store', 'WardController@store')->name('store');
            Route::get('edit/{id}', 'WardController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'WardController@update')->name('update');
            Route::get('list', 'WardController@list')->name('list');
            Route::get('fetch', 'WardController@fetch')->name('fetch');
            Route::delete('delete/{id}', 'WardController@destroy')->name('delete');
            Route::post('search', 'WardController@search')->name('search');
            Route::get('view/{id}', 'WardController@view')->name('view');
        });

        Route::group(['prefix' => 'bed', 'as' => 'bed.'], function () {
            Route::get('add-new', 'BedController@index')->name('add-new');
            Route::post('store', 'BedController@store')->name('store');
            Route::get('edit/{id}', 'BedController@edit')->name('edit');
            Route::post('associate_patient/{id}', 'BedController@associate_patient')->name('associate_patient');
            Route::post('dissociate_patient', 'BedController@dissociate_patient')->name('dissociate_patient');
            Route::match(['post', 'put'], 'update/{id}', 'BedController@update')->name('update');
            Route::get('list', 'BedController@list')->name('list');
            Route::get('fetch', 'BedController@fetch')->name('fetch');
            Route::delete('delete/{id}', 'BedController@destroy')->name('delete');
            Route::post('search', 'BedController@search')->name('search');
            Route::get('view/{id}', 'BedController@view')->name('view');
        });

        Route::group(['prefix' => 'note', 'as' => 'note.'], function () {
            Route::get('add-new', 'PatientNoteController@index')->name('add-new');
            Route::post('store', 'PatientNoteController@store')->name('store');
            Route::get('edit/{id}', 'PatientNoteController@edit')->name('edit');
            Route::put('update/{id}', 'PatientNoteController@update')->name('update');
            Route::get('list', 'PatientNoteController@list')->name('list');
            Route::delete('delete/{id}', 'PatientNoteController@destroy')->name('delete');
            Route::post('search', 'PatientNoteController@search')->name('search');
            Route::get('view/{id}', 'PatientNoteController@view')->name('view');
        });

        Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
            Route::get('list', 'CustomerController@customer_list')->name('list');
            Route::get('view/{user_id}', 'CustomerController@view')->name('view');
            Route::get('edit/{id}', 'CustomerController@edit')->name('edit');
            Route::delete('delete/{id}', 'CustomerController@delete')->name('delete');
            Route::post('search', 'CustomerController@search')->name('search');
            Route::get('subscribed-emails', 'CustomerController@subscribed_emails')->name('subscribed_emails');
        });

        Route::group(['prefix' => 'orders', 'as' => 'orders.'], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::get('details/{id}', 'OrderController@details')->name('details');
            Route::get('status', 'OrderController@status')->name('status');
            Route::get('add-delivery-man/{order_id}/{delivery_man_id}', 'OrderController@add_delivery_man')->name('add-delivery-man');
            Route::get('payment-status', 'OrderController@payment_status')->name('payment-status');
            Route::post('productStatus', 'OrderController@productStatus')->name('productStatus');
            Route::get('generate-invoice/{id}', 'OrderController@generate_invoice')->name('generate-invoice');
            Route::post('add-payment-ref-code/{id}', 'OrderController@add_payment_ref_code')->name('add-payment-ref-code');
            Route::get('branch-filter/{branch_id}', 'OrderController@branch_filter')->name('branch-filter');
            Route::get('export/{status}', 'OrderController@export_orders')->name('export');
        });

        Route::group(['prefix' => 'order', 'as' => 'order.'], function () {
            Route::get('list/{status}', 'OrderController@list')->name('list');
            Route::put('updatePaymentStatus/{id}', 'OrderController@updatePaymentStatus')->name('updatePaymentStatus');
            Route::put('status-update/{id}', 'OrderController@status')->name('status-update');
            Route::get('view/{id}', 'OrderController@view')->name('view');
            Route::post('update-shipping/{id}', 'OrderController@update_shipping')->name('update-shipping');
            Route::delete('delete/{id}', 'OrderController@delete')->name('delete');
            Route::get('updateOrderStatus/{id}', 'OrderController@updateOrderStatus')->name('updateOrderStatus');
        });

        Route::group(['prefix' => 'purchase', 'as' => 'purchase.'], function () {
            Route::get('request-download', 'PurchaseController@request_download')->name('request-download');
            Route::get('request', 'PurchaseController@request')->name('request');
            Route::get('add', 'PurchaseController@add')->name('add');
            Route::post('store', 'PurchaseController@store')->name('store');
            Route::get('list', 'PurchaseController@list')->name('list');
            Route::get('view/{user_id}', 'PurchaseController@view')->name('view');
            Route::get('invoice/{id}', 'PurchaseController@generate_invoice')->name('generate_invoice');
            Route::get('edit/{id}', 'PurchaseController@edit')->name('edit');
            Route::post('update', 'PurchaseController@update')->name('update');
            Route::delete('delete/{id}', 'PurchaseController@delete')->name('delete');
            Route::put('updatePaymentStatus/{id}', 'PurchaseController@updatePaymentStatus')->name('updatePaymentStatus');
            Route::post('search', 'PurchaseController@search')->name('search');
        });

        Route::group(['prefix' => 'medical_condition', 'as' => 'medical_condition.'], function () {
            Route::get('add-new', 'MedicalConditionController@index')->name('add-new');
            Route::post('store', 'MedicalConditionController@store')->name('store');
            Route::get('edit/{id}', 'MedicalConditionController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'MedicalConditionController@update')->name('update');
            Route::get('list', 'MedicalConditionController@list')->name('list');
            Route::delete('delete/{id}', 'MedicalConditionController@destroy')->name('delete');
            Route::post('search', 'MedicalConditionController@search')->name('search');
            Route::get('view/{id}', 'MedicalConditionController@view')->name('view');
        });

        Route::group(['prefix' => 'condition_category', 'as' => 'condition_category.'], function () {
            Route::get('add-new', 'ConditionCategoryController@index')->name('add-new');
            Route::post('store', 'ConditionCategoryController@store')->name('store');
            Route::get('edit/{id}', 'ConditionCategoryController@edit')->name('edit');
            Route::match(['post', 'put'], 'update/{id}', 'ConditionCategoryController@update')->name('update');
            Route::get('list', 'ConditionCategoryController@list')->name('list');
            Route::delete('delete/{id}', 'ConditionCategoryController@destroy')->name('delete');
            Route::post('search', 'ConditionCategoryController@search')->name('search');
            Route::get('view/{id}', 'ConditionCategoryController@view')->name('view');
        });

        Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
            Route::get('/', 'POSController@index')->name('index');
            Route::delete('delete/{id}', 'POSController@delete')->name('delete');
            Route::post('approve/{id}', 'POSController@approve_order')->name('approve_order');
            Route::put('/orders/{order}/update-amount', 'POSController@updateAmount')->name('orders.updateAmount');
            Route::put('/orders/{order}/update-invoice', 'POSController@updateInvoice')->name('orders.updateInvoice');
            Route::post('with_holding_tax', 'POSController@update_with_holding_tax')->name('with_holding_tax');
            Route::post('total_tax', 'POSController@update_total_tax')->name('total_tax');
            Route::post('with_tax', 'POSController@update_with_tax')->name('with_tax');
            Route::get('quick-view', 'POSController@quick_view')->name('quick-view');
            Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
            Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
            Route::post('add-prescription-to-cart', 'POSController@addPrescriptionToCart')->name('add-prescription-to-cart');
            Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
            Route::post('cart-items', 'POSController@cart_items')->name('cart_items');
            Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
            Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
            Route::post('tax', 'POSController@update_tax')->name('tax');
            Route::post('discount', 'POSController@update_discount')->name('discount');
            Route::get('customers', 'POSController@get_customers')->name('customers');
            Route::post('order', 'POSController@place_order')->name('order');
            Route::get('orders', 'POSController@order_list')->name('orders');
            // Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
            Route::get('invoice/{id}', 'POSController@generate_invoice');
            Route::any('store-keys', 'POSController@store_keys')->name('store-keys');
            Route::post('customer-store', 'POSController@customer_store')->name('customer-store');
            Route::get('orders/export', 'POSController@export_orders')->name('orders.export');
            Route::get('order-details/{id}', 'POSController@details')->name('order-details');
            Route::get('pdf/{id}', 'POSController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'POSController@downloadPdf')->name('download');
        });

        Route::group(['prefix' => 'medical-document', 'as' => 'medical_document.'], function () {
            Route::get('add-new', 'MedicalDocumentController@index')->name('add-new');
            Route::post('store', 'MedicalDocumentController@store')->name('store');
            Route::get('edit/{id}', 'MedicalDocumentController@edit')->name('edit');
            Route::post('update/{id}', 'MedicalDocumentController@update')->name('update');
            Route::get('list', 'MedicalDocumentController@list')->name('list');
            Route::delete('delete/{id}', 'MedicalDocumentController@destroy')->name('delete');
            Route::post('search', 'MedicalDocumentController@search')->name('search');
            Route::get('view/{id}', 'MedicalDocumentController@view')->name('view');
            Route::get('pdf/{id}', 'MedicalDocumentController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'MedicalDocumentController@downloadPdf')->name('download');
        });

        // Visit Documents Routes
        Route::group(['prefix' => 'visit_document', 'as' => 'visit_document.'], function () {
            Route::get('list', 'VisitDocumentController@index')->name('list');
            Route::post('store', 'VisitDocumentController@store')->name('store');
            Route::get('show/{id}', 'VisitDocumentController@show')->name('show');
            Route::put('update/{id}', 'VisitDocumentController@update')->name('update');
            Route::delete('delete/{id}', 'VisitDocumentController@destroy')->name('delete');
            Route::get('download/{id}', 'VisitDocumentController@download')->name('download');
            Route::get('view/{id}', 'VisitDocumentController@view')->name('view');
        });

        // Route::group(['prefix' => 'medical_certification', 'as' => 'medical_certification.'], function () {
        //     Route::get('add-new', 'MedicalCertificationController@index')->name('add-new');
        //     Route::post('store', 'MedicalCertificationController@store')->name('store');
        //     Route::get('edit/{id}', 'MedicalCertificationController@edit')->name('edit');
        //     Route::post('update/{id}', 'MedicalCertificationController@update')->name('update');
        //     Route::get('list', 'MedicalCertificationController@list')->name('list');
        //     Route::delete('delete/{id}', 'MedicalCertificationController@destroy')->name('delete');
        //     Route::post('search', 'MedicalCertificationController@search')->name('search');
        //     Route::get('view/{id}', 'MedicalCertificationController@view')->name('view');
        //     Route::get('pdf/{id}', 'MedicalCertificationController@generatePdf')->name('pdf');
        //     Route::get('download/{id}', 'MedicalCertificationController@downloadPdf')->name('download');
        // });

        Route::group(['prefix' => 'referral-slip', 'as' => 'referral_slip.'], function () {
            Route::get('add-new', 'ReferralSlipFormController@index')->name('add-new');
            Route::post('store', 'ReferralSlipFormController@store')->name('store');
            Route::get('edit/{id}', 'ReferralSlipFormController@edit')->name('edit');
            Route::post('update/{id}', 'ReferralSlipFormController@update')->name('update');
            Route::get('list', 'ReferralSlipFormController@list')->name('list');
            Route::delete('delete/{id}', 'ReferralSlipFormController@destroy')->name('delete');
            Route::post('search', 'ReferralSlipFormController@search')->name('search');
            Route::get('view/{id}', 'ReferralSlipFormController@view')->name('view');
            Route::get('pdf/{id}', 'ReferralSlipFormController@generatePdf')->name('pdf');
            Route::get('download/{id}', 'ReferralSlipFormController@downloadPdf')->name('download');
        });


        Route::group(['prefix' => 'pharmacy-company-setting', 'as' => 'pharmacy-company-setting.'], function () {
            Route::get('view', 'PharmacyCompanySettingController@pharmacy_index')->name('view');
            Route::post('update-setup', 'PharmacyCompanySettingController@pharmacy_setup')->name('update-setup');
        });

        Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.', 'middleware' => ['actch']], function () {
            Route::get('ecom-setup', 'BusinessSettingsController@restaurant_index')->name('ecom-setup');
            Route::get('activity', 'ActivityLogController@activity')->name('activity');
            Route::get('activity/{id}', 'ActivityLogController@detail')->name('detail');
            Route::post('search', 'AdminUserController@search')->name('search');

            Route::post('update-setup', 'BusinessSettingsController@restaurant_setup')->name('update-setup');

            Route::get('fcm-index', 'BusinessSettingsController@fcm_index')->name('fcm-index');
            Route::post('update-fcm', 'BusinessSettingsController@update_fcm')->name('update-fcm');

            Route::post('update-fcm-messages', 'BusinessSettingsController@update_fcm_messages')->name('update-fcm-messages');

            Route::get('mail-config', 'BusinessSettingsController@mail_index')->name('mail-config');
            Route::post('mail-send', 'BusinessSettingsController@mail_send')->name('mail-send');
            Route::post('mail-config', 'BusinessSettingsController@mail_config');
            Route::get('mail-config/status/{status}', 'BusinessSettingsController@mail_config_status')->name('mail-config.status');


            Route::get('payment-method', 'BusinessSettingsController@payment_index')->name('payment-method');
            Route::post('payment-method-update/{payment_method}', 'BusinessSettingsController@payment_update')->name('payment-method-update');

            Route::get('currency-add', 'BusinessSettingsController@currency_index')->name('currency-add');
            Route::post('currency-add', 'BusinessSettingsController@currency_store');
            Route::get('currency-update/{id}', 'BusinessSettingsController@currency_edit')->name('currency-update');
            Route::put('currency-update/{id}', 'BusinessSettingsController@currency_update');
            Route::delete('currency-delete/{id}', 'BusinessSettingsController@currency_delete')->name('currency-delete');

            Route::get('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions')->name('terms-and-conditions');
            Route::post('terms-and-conditions', 'BusinessSettingsController@terms_and_conditions_update');

            Route::get('privacy-policy', 'BusinessSettingsController@privacy_policy')->name('privacy-policy');
            Route::post('privacy-policy', 'BusinessSettingsController@privacy_policy_update');

            Route::get('about-us', 'BusinessSettingsController@about_us')->name('about-us');
            Route::post('about-us', 'BusinessSettingsController@about_us_update');

            Route::get('db-index', 'DatabaseSettingsController@db_index')->name('db-index');
            Route::post('db-clean', 'DatabaseSettingsController@clean_db')->name('clean-db');

            Route::get('firebase-message-config', 'BusinessSettingsController@firebase_message_config_index')->name('firebase_message_config_index');
            Route::post('firebase-message-config', 'BusinessSettingsController@firebase_message_config')->name('firebase_message_config');

            Route::get('location-setup', 'LocationSettingsController@location_index')->name('location-setup');
            Route::post('update-location', 'LocationSettingsController@location_setup')->name('update-location');

            Route::get('sms-module', 'SMSModuleController@sms_index')->name('sms-module');
            Route::post('sms-module-update/{sms_module}', 'SMSModuleController@sms_update')->name('sms-module-update');

            //recaptcha
            Route::get('recaptcha', 'BusinessSettingsController@recaptcha_index')->name('recaptcha_index');
            Route::post('recaptcha-update', 'BusinessSettingsController@recaptcha_update')->name('recaptcha_update');

            //pages
            Route::get('return-page', 'BusinessSettingsController@return_page_index')->name('return_page_index');
            Route::post('return-page-update', 'BusinessSettingsController@return_page_update')->name('return_page_update');

            Route::get('refund-page', 'BusinessSettingsController@refund_page_index')->name('refund_page_index');
            Route::post('refund-page-update', 'BusinessSettingsController@refund_page_update')->name('refund_page_update');

            Route::get('cancellation-page', 'BusinessSettingsController@cancellation_page_index')->name('cancellation_page_index');
            Route::post('cancellation-page-update', 'BusinessSettingsController@cancellation_page_update')->name('cancellation_page_update');

            //app settings
            Route::get('app-setting', 'BusinessSettingsController@app_setting_index')->name('app_setting');
            Route::post('app-setting', 'BusinessSettingsController@app_setting_update');

            Route::get('currency-position/{position}', 'BusinessSettingsController@currency_symbol_position')->name('currency-position');
            Route::get('maintenance-mode', 'BusinessSettingsController@maintenance_mode')->name('maintenance-mode');

            Route::get('pagination-limit', 'BusinessSettingsController@mail_index')->name('pagination-limit');
            Route::post('pagination-limit', 'BusinessSettingsController@mail_config');

            Route::get('map-api-settings', 'BusinessSettingsController@map_api_settings')->name('map_api_settings');
            Route::post('map-api-settings', 'BusinessSettingsController@update_map_api');

            //Social Icon
            Route::get('social-media', 'BusinessSettingsController@social_media')->name('social-media');
            Route::get('fetch', 'BusinessSettingsController@fetch')->name('fetch');
            Route::post('social-media-store', 'BusinessSettingsController@social_media_store')->name('social-media-store');
            Route::post('social-media-edit', 'BusinessSettingsController@social_media_edit')->name('social-media-edit');
            Route::post('social-media-update', 'BusinessSettingsController@social_media_update')->name('social-media-update');
            Route::post('social-media-delete', 'BusinessSettingsController@social_media_delete')->name('social-media-delete');
            Route::post('social-media-status-update', 'BusinessSettingsController@social_media_status_update')->name('social-media-status-update');

            Route::get('otp-setup', 'BusinessSettingsController@otp_index')->name('otp-setup');
            Route::post('update-otp', 'BusinessSettingsController@update_otp')->name('update-otp');

            Route::get('cookies-setup', 'BusinessSettingsController@cookies_setup')->name('cookies-setup');
            Route::post('update-cookies', 'BusinessSettingsController@cookies_setup_update')->name('update-cookies');


            Route::get('social-media-login', 'BusinessSettingsController@social_media_login')->name('social-media-login');
            Route::get('social_login_status/{medium}/{status}', 'BusinessSettingsController@change_social_login_status')->name('social_login_status');

            Route::get('social-media-chat', 'BusinessSettingsController@social_media_chat')->name('social-media-chat');
            Route::post('update-social-media-chat', 'BusinessSettingsController@update_media_chat')->name('update-social-media-chat');
        });

        Route::group(['prefix' => 'supplier', 'as' => 'supplier.'], function () {
            Route::get('add-new', 'SupplierController@index')->name('add-new');
            Route::post('store', 'SupplierController@store')->name('store');
            Route::get('edit/{id}', 'SupplierController@edit')->name('edit');
            Route::put('update/{id}', 'SupplierController@update')->name('update');
            Route::get('list', 'SupplierController@list')->name('list');
            Route::delete('delete/{id}', 'SupplierController@destroy')->name('delete');
        });
    });
});

// Route::get('appointment/get-patient-visits/{patient}', [\App\Http\Controllers\Admin\AppointmentController::class, 'getPatientVisits'])->name('admin.appointment.get-patient-visits');
