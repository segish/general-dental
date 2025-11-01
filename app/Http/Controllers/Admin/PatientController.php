<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\EmergencyInventory;
use App\Models\Test;
use App\Models\SpecimenType;
use App\Models\AssessmentCategory;
use App\Models\SpecimenOrigin;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use App\Models\Visit;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use App\Models\BillingDetail;
use App\Models\Billing;
use Illuminate\Support\Facades\DB;
use App\Models\BillingService;
use App\Models\EmergencyMedicine;
use App\Models\MedicalCondition;
use App\Models\Radiology;
use Carbon\Carbon;
use App\Models\Pregnancy;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Log;
use App\Models\BusinessSetting;
use App\Models\DoseInterval;
use App\Models\MedicineCategory;

class PatientController extends Controller
{
    function __construct(
        private Patient $patient,
        private Visit $visit

    ) {
        $this->middleware('checkAdminPermission:patient.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:patient.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $lastPatient = Patient::latest()->first();
        $lastRegistrationNo = $lastPatient ? $lastPatient->registration_no : null;

        if ($lastRegistrationNo) {
            // Extract the numeric part of the registration_no
            $numericPart = preg_replace('/[^0-9]/', '', $lastRegistrationNo);

            // Add 1 to the numeric part
            $newNumericPart = str_pad(intval($numericPart) + 1, strlen($numericPart), '0', STR_PAD_LEFT);


            // Generate the new registration number
            $newRegistrationNo = 'HMS' . $newNumericPart;
        } else {
            // If there are no existing patients, generate a new random registration number
            $newRegistrationNo = 'HMS' . Str::random(6);
        }

        return view('admin-views.patients.index', compact('newRegistrationNo'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $currentUser = auth('admin')->user();
        $search = $request['search'];

        if ($currentUser->hasRole('Super Admin')) {
            // User is a Super Admin, fetch all patients
            $query = $this->patient->latest();

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->latest();
                $query_param = ['search' => $request['search']];
            }
        } elseif ($currentUser->can('doctor_dashboard')) {
            // User is a doctor, filter patients by medical histories associated with the doctor
            $doctor = Doctor::where('admin_id', $currentUser->id)->first();

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->whereHas('medicalHistories', function ($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id);
                })->latest();

                $query_param = ['search' => $request['search']];
            } else {
                // Fetch patients who have medical histories with the specific doctor
                $query = $this->patient->whereHas('medicalHistories', function ($q) use ($doctor) {
                    $q->where('doctor_id', $doctor->id);
                })->latest();
            }
        } elseif ($currentUser->can('nurse_dashboard')) {
            $nurse = Nurse::where('admin_id', $currentUser->id)->first();
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->where('nurse_id', $nurse->id)->latest();
                $query_param = ['search' => $request['search']];
            } else {
                $query = $this->patient->where('nurse_id', $nurse->id)->latest();
            }
        } elseif ($currentUser->can('radiologist_dashboard')) {
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->whereHas('medicalHistories', function ($q) {
                    $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
                })->latest();

                $query_param = ['search' => $request['search']];
            } else {
                $query = $this->patient->whereHas('medicalHistories', function ($q) {
                    $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
                })->latest();
            }
        } elseif ($currentUser->can('lab_technician_dashboard')) {
            // User is a pharmacist, filter patients with medical history and lab_test_required

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->whereHas('medicalHistories', function ($q) {
                    $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
                })->latest();

                $query_param = ['search' => $request['search']];
            } else {
                $query = $this->patient->whereHas('medicalHistories', function ($q) {
                    $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
                })->latest();
            }
        } else {
            // User is a Super Admin, fetch all patients
            $query = $this->patient->latest();

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->patient->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('full_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%")
                            ->orWhere('registration_no', 'like', "%{$value}%");
                    }
                })->latest();
                $query_param = ['search' => $request['search']];
            }
        }


        $patients = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.patients.list', compact('patients', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function view(Request $request, $id): Factory|View|Application
    {
        $currentUser = auth('admin')->user();
        $patient = Patient::find($id);
        $tests = Test::query()
            ->select('tests.*')
            ->selectSub(function ($q) {
                $q->from('laboratory_request_test as lrt')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('lrt.test_id', 'tests.id');
            }, 'request_count')
            ->where('is_active', 1)
            ->orderByDesc('request_count')
            ->orderBy('tests.test_name', 'asc')
            ->get();

        // Add another query for tests where is_active = 0 (inactive)
        $outTests = Test::query()
            ->select('tests.*')
            ->selectSub(function ($q) {
                $q->from('laboratory_request_test as lrt')
                    ->selectRaw('COUNT(*)')
                    ->whereColumn('lrt.test_id', 'tests.id');
            }, 'request_count')
            ->where('is_active', 0)
            ->orderByDesc('request_count')
            ->orderBy('tests.test_name', 'asc')
            ->get();
        $radiologies = Radiology::all();
        $specimenOrigins = SpecimenOrigin::all();
        $medications = Medicine::orderBy('category_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();
        $serviceCategories = ServiceCategory::all();
        $pregnancy = Pregnancy::where('patient_id', $patient->id)
            ->where('status', 'Ongoing')
            ->orderByDesc('lmp') // latest
            ->first();


        $pregnancy_edit = Pregnancy::where('patient_id', $patient->id)
            ->orderByDesc('lmp') // latest
            ->first();

        $currentWeek = null;
        $doctors = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'medical_record.add-new')
                ->orWhere('name', 'delivery_summary.add-new');
        })->latest()->get();
        $labTechs = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'laboratory_result.verify-status.update');
        })->latest()->get();
        if ($pregnancy) {
            $startDate = Carbon::parse($pregnancy->start_date);
            $currentWeek = $startDate->diffInWeeks(Carbon::now()) + 1; // +1 to include week 1
        }
        $diseases = MedicalCondition::with('category')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Disease');
            })
            ->get();
        $treatments = MedicalCondition::with('category')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Treatment');
            })
            ->get();
        $medicalRecords = MedicalCondition::with('category')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Medical Record');
            })
            ->get();

        $fourMonthsFromNow = Carbon::now()->addMonths(4);
        $emergencyPrescreptions = EmergencyInventory::with('medicine')
            ->where('quantity', '>', 0)
            ->where(function ($query) use ($fourMonthsFromNow) {
                $query->whereNull('expiry_date')
                    ->orWhere('expiry_date', '>', $fourMonthsFromNow);
            })
            ->get();

        $vitalSigns = AssessmentCategory::with('unit')->where('category_type', 'Vital Sign')->get();
        $labourFollowups = AssessmentCategory::with('unit')->where('category_type', 'Labour Followup')->get();

        $billingServices = BillingService::where('billing_type', 'one-time')->get();
        $query_param = [];
        $conditions = MedicalCondition::with('category')
            ->whereHas('category', function ($query) {
                $query->where('name', 'Disease');
            })
            ->get();
        $visitQuery = $patient->visits()->with([
            'medicalRecord.values.field.options',
            'laboratoryRequest.testResults2.attributes' => function ($query) {
                $query->select('id', 'attribute_id', 'test_result_id', 'result_value', 'comments'); // Select only necessary columns
                $query->with([
                    'attribute' => function ($query) {
                        $query->select('id', 'attribute_name', 'index')
                            ->with('attributeReferences')
                            ->with('unit');
                    }
                ]);
            },

            'radiologyRequest.radiologyResults2.attributes' => function ($query) {
                $query->select('id', 'radiology_attribute_id', 'radiology_result_id', 'result_value'); // Select only necessary columns
                $query->with([
                    'attribute' => function ($query) {
                        $query->select('id', 'attribute_name'); // Only select the 'id' and 'name' from the 'attribute' table
                    }
                ]);
            },
            'opdRecord',
            'ipdRecord.ward',
            'ipdRecord.bed',

            'laboratoryRequest.billing',
            'laboratoryRequest.billing2',
            'laboratoryRequest.tests.result',
            'laboratoryRequest.tests.test.testCategory',
            'laboratoryRequest.testResults2.processedBy:id,f_name,l_name',
            'laboratoryRequest.testResults2.verifiedBy:id,f_name,l_name',
            'laboratoryRequest.specimens',

            'radiologyRequest.radiologies',
            'radiologyRequest.radiologies.radiology',
            'radiologyRequest.radiologyResults2.processedBy:id,f_name,l_name',
            'radiologyRequest.radiologyResults2.verifiedBy:id,f_name,l_name',
            'radiologyRequest.radiologyResults2.radiologyRequestTest.radiology',

            'prescription',
            'emergencyPrescriptions.details.medicine.medicine',
            'emergencyPrescriptions.billing',
            'emergencyPrescriptions.details.issuedBy:id,f_name,l_name',
            'nurseAssessments',
            'labourFollowups',
            'medicalRecord',
            'diagnosisTreatment',
            'diagnosisTreatment.diseases',
            'serviceCategory',
            'discharge',
            'pregnancy',
            'prenatalVisit',
            'prenatalVisitHistory',
            'visitDocuments.uploadedBy',
        ]);


        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $visitQuery->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('created_at', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        // Step 2: Clone for locating active page
        $allVisits = (clone $visitQuery)->latest('created_at')->get();

        $page = 1;
        if ($request->has('active')) {
            $activeId = $request->get('active');
            $position = $allVisits->search(fn($item) => $item->id == $activeId);
            if ($position !== false) {
                $page = ceil(($position + 1) / 2); // assuming 2 per page
            }
        } elseif ($request->has('page')) {
            $page = $request->get('page');
        }

        // Step 3: Paginate the base query
        $visits = $visitQuery->latest('created_at')->paginate(2, ['*'], 'page', $page);
        $visits->appends($query_param ?? []);

        // Step 4: Eager load relationships manually for paginated visits
        $visits->load([
            'procedures',
            'documents',
            'dentalCharts.creator',
            'laboratoryRequest.testResults2.attributes.attribute.unit',
            'laboratoryRequest.testResults2.attributes.attribute.attributeReferences',
            'laboratoryRequest.testResults2.laboratoryRequestTest.test.testCategory',
            'radiologyRequest.radiologyResults2.attributes.attribute',
            'laboratoryRequest.testResults2.processedBy:id,f_name,l_name',
            'laboratoryRequest.testResults2.verifiedBy:id,f_name,l_name',
            // ... other relations from your original with()
        ]);

        // Step 5: Filter test results only if user has "list" permission but not "add-new"
        $hasAddPermission = auth('admin')->user()->can('laboratory_result.add-new');
        $hasListPermission = auth('admin')->user()->can('laboratory_result.list');
        $isListOnly = !$hasAddPermission && $hasListPermission;

        if ($isListOnly) {
            foreach ($visits as $visit) {
                if ($visit->laboratoryRequest && $visit->laboratoryRequest->testResults2) {
                    $visit->laboratoryRequest->testResults2 = $visit->laboratoryRequest->testResults2
                        ->filter(fn($result) => $result->verify_status === 'approved')
                        ->values();
                }
            }
        }

        $medicineCategories = MedicineCategory::all();
        $doseIntervals = DoseInterval::all();
        // Step 6: Append query parameters to pagination links
        $visits->appends($query_param);

        // Aggregate emergency prescription detail counts for modal
        $emergencyPrescriptionStats = [];
        foreach ($visits as $visit) {
            foreach ($visit->emergencyPrescriptions as $prescription) {
                foreach ($prescription->details as $detail) {
                    $emergencyPrescriptionStats[(string)$detail->id] = [
                        'prescribed' => $detail->quantity,
                        'issued' => $detail->issued_quantity ?? 0,
                        'cancelled' => $detail->cancelled_quantity ?? 0,
                        'pending' => ($detail->quantity - ($detail->issued_quantity ?? 0) - ($detail->cancelled_quantity ?? 0)),
                    ];
                }
            }
        }

        // Get medical record fields
        $medicalRecordFields = \App\Models\MedicalRecordField::active()->ordered()->with('options')->get();

        return view('admin-views.patients.view', compact(
            'patient',
            'visits',
            'vitalSigns',
            'labourFollowups',
            'billingServices',
            'tests',
            'outTests',
            'radiologies',
            'specimenOrigins',
            'medications',
            'emergencyPrescreptions',
            'conditions',
            'diseases',
            'treatments',
            'medicalRecords',
            'pregnancy',
            'pregnancy_edit',
            'currentWeek',
            'doctors',
            'labTechs',
            'medicalRecordFields',
            'serviceCategories',
            'medicineCategories',
            'doseIntervals',
            'emergencyPrescriptionStats', // pass to view
        ));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.patients.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'registration_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'marital_status' => 'nullable|string|in:Single,Married,Divorced',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'is_flexible_payment' => 'required|boolean',
        ]);

        try {

            $validatedData['registration_no'] = $this->generateUniqueRegistrationNumber();
            // Create the patient
            $patient = Patient::create($validatedData);

            // Fetch all recurring billing services
            $recurringServices = BillingService::where('billing_type', 'recurring')->where('is_active', true)->get();

            if ($recurringServices->isEmpty()) {
                throw new \Exception("No recurring services found.");
            }

            return response()->json(['patient' => $patient], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function generateUniqueRegistrationNumber(): string
    {
        $prefix = trim(BusinessSetting::where('key', 'patient_reg_prefix')->first()?->value ?? '');
        $suffix = trim(BusinessSetting::where('key', 'patient_reg_suffix')->first()?->value ?? '');

        do {
            $code = strtoupper($prefix . date('ymd') . mt_rand(1000, 9999) . $suffix);
        } while (Patient::where('registration_no', $code)->exists());

        return $code;
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.patients.show', compact('role', 'rolePermissions'));
    }
    public function getPatients(Request $request)
    {
        $search = $request->input('search');
        $patients = Patient::where('full_name', 'LIKE', "%{$search}%")
            ->orWhere('registration_no', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->take(20) // Limit results for better performance
            ->get(['id', 'full_name', 'registration_no', 'phone']);

        dd($patients);
        return response()->json($patients);
    }
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);

        return view('admin-views.patients.edit', compact('patient'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }


        $validatedData = $request->validate([
            'registration_date' => 'required|date',
            'date_of_birth' => 'required|date',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string|in:male,female',
            'marital_status' => 'nullable|string|in:single,married,divorced,widowed',
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'email' => 'nullable|email|max:255',
            'tin_no' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'required|string|regex:/^[0-9]{10,15}$/',
            'is_flexible_payment' => 'required|boolean',
        ]);
        try {
            // Update the patient instance with the validated data
            $patient->update($validatedData);

            return response()->json($patient, 200);
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Patient::findOrFail($id);

        // Delete the role
        $role->delete();
        Toastr::success(translate('Patient Deleted Successfully!'));
        return back();
    }
}
