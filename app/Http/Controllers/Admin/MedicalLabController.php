<?php

// namespace App\Http\Controllers\admin;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\MedicalLabResult;
// use App\Models\TestType;
// use App\Models\RadiologyType;
// use App\Models\Patient;
// use App\CentralLogics\Helpers;
// use Illuminate\Contracts\View\Factory;
// use Illuminate\Contracts\View\View;
// use Illuminate\Contracts\Foundation\Application;
// use DB;
// class MedicalLabController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      *
//      * @return \Illuminate\Http\Response
//      */

//     function __construct(
//         private Patient $patient,
//         private MedicalLabResult $lab_result,

//     ) {
//         $this->middleware('checkAdminPermission:lab_result.list,list')->only(['list']);
//         $this->middleware('checkAdminPermission:lab_result.add-new,index')->only(['index']);
//     }

//     public function index()
//     {
//         //
//     }

//     /**
//      * Show the form for creating a new resource.
//      *
//      * @return \Illuminate\Http\Response
//      */
//     public function lab_technician_dashboard(Request $request)
//     {

//         $testType_count = TestType::all()->count();


//         $query_param = [];
//         $search = $request['search'];

//         if ($request->has('search')) {
//             $key = explode(' ', $request['search']);
//             $query = $this->patient->where(function ($q) use ($key) {
//                 foreach ($key as $value) {
//                     $q->orWhere('id', 'like', "%{$value}%")
//                         ->orWhere('full_name', 'like', "%{$value}%")
//                         ->orWhere('phone', 'like', "%{$value}%")
//                         ->orWhere('email', 'like', "%{$value}%")
//                         ->orWhere('registration_no', 'like', "%{$value}%");
//                 }
//             })->whereHas('medicalHistories', function ($q) {
//                 $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
//             })->latest();

//             $query_param = ['search' => $request['search']];
//         } else {
//             $query = $this->patient->whereHas('medicalHistories', function ($q) {
//                 $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
//             })->latest();
//         }

//         $patients = $query->paginate(Helpers::pagination_limit())->appends($query_param);
//         $patient_count = $patients->count();


//         return view('admin-views.labs.dashboard', compact('patients', 'testType_count', 'patient_count'));

//     }

//     public function radiologist_dashboard(Request $request)
//     {

//         $radiologyType_count = RadiologyType::all()->count();


//         $query_param = [];
//         $search = $request['search'];

//         if ($request->has('search')) {
//             $key = explode(' ', $request['search']);
//             $query = $this->patient->where(function ($q) use ($key) {
//                 foreach ($key as $value) {
//                     $q->orWhere('id', 'like', "%{$value}%")
//                         ->orWhere('full_name', 'like', "%{$value}%")
//                         ->orWhere('phone', 'like', "%{$value}%")
//                         ->orWhere('email', 'like', "%{$value}%")
//                         ->orWhere('registration_no', 'like', "%{$value}%");
//                 }
//             })->whereHas('medicalHistories', function ($q) {
//                 $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
//             })->latest();

//             $query_param = ['search' => $request['search']];
//         } else {
//             $query = $this->patient->whereHas('medicalHistories', function ($q) {
//                 $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
//             })->latest();
//         }

//         $patients = $query->paginate(Helpers::pagination_limit())->appends($query_param);
//         $patient_count = $patients->count();


//         return view('admin-views.radiology.dashboard', compact('patients', 'radiologyType_count', 'patient_count'));

//     }

//     /**
//      * Store a newly created resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @return \Illuminate\Http\Response
//      */
//     public function store(Request $request)
//     {
//         $request->validate([
//             'medical_history_id' => 'required|exists:medical_histories,id',
//             'test_name' => 'string',
//             'test_result' => 'required|string',
//             'test_type_id' => 'required|exists:test_types,id',
//             'images.*' => 'file|mimes:png,jpg,jpeg,dcm', // Allow DICOM files
//         ]);

//         $img_names = [];
//         if (!empty($request->file('images'))) {
//             foreach ($request->images as $img) {
//                 $image_data = Helpers::upload('assets/lab_results/', 'png', $img);
//                 $img_names[] = $image_data;
//             }
//             $image_data = json_encode($img_names);
//         } else {
//             $image_data = json_encode([]);
//         }

//         $labResult = new MedicalLabResult([
//             'medical_history_id' => $request->input('medical_history_id'),
//             'test_name' => 'Test Name Here',
//             'result_content' => $request->input('test_result'),
//             'image' => $image_data,
//         ]);

//         $labResult->save();

//         // Attach the specific test type to the lab result
//         $testTypeId = $request->input('test_type_id');
//         $labResult->testTypes()->attach($testTypeId);

//         // Use MedicalHistory relationship to update status
//         $medicalHistoryId = $request->input('medical_history_id');
//         $medicalHistory = MedicalHistory::findOrFail($medicalHistoryId);

//         // Update the pivot status to 'done' for the given test type
//         $medicalHistory->testTypes()->updateExistingPivot($testTypeId, ['status' => 'done']);

//         // Check if all test types for the medical history are done
//         $allTestTypesDone = $medicalHistory->testTypes()->wherePivot('status', '<>', 'done')->doesntExist();

//         if ($allTestTypesDone) {
//             $medicalHistory->update(['lab_test_progress' => 'done']);
//         }

//         return response()->json(['message' => 'Lab result added successfully'], 200);
//     }


//     public function status(Request $request)
//     {
//         $request->validate([
//             'medical_history_id' => 'required|exists:medical_histories,id',
//             'lab_test_progress' => 'required|in:pending,accepted,done',
//         ]);

//         MedicalHistory::find($request->input('medical_history_id'))
//             ->update(['lab_test_progress' => $request->input('lab_test_progress')]);

//         return response()->json(['message' => 'Lab test progress updated successfully'], 200);
//     }

//     /**
//      * Display the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function show($id)
//     {
//         //
//     }

//     public function list(Request $request): Factory|View|Application
//     {
//         $query_param = [];
//         $currentUser = auth('admin')->user();
//         $search = $request['search'];

//         if ($currentUser->hasRole('Super Admin')) {
//             // User is a Super Admin, fetch all patients
//             $query = $this->patient->latest();

//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->latest();
//                 $query_param = ['search' => $request['search']];
//             }
//             $query = MedicalHistory::with([
//                 'testTypes' => function ($q) {
//                     $q->orderBy('created_at', 'desc'); // Order test types by created_at
//                 }
//             ]);

//         } elseif ($currentUser->can('doctor_dashboard')) {
//             // User is a doctor, filter patients by medical histories associated with the doctor
//             $doctor = Doctor::where('admin_id', $currentUser->id)->first();

//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->whereHas('medicalHistories', function ($q) use ($doctor) {
//                     $q->where('doctor_id', $doctor->id);
//                 })->latest();

//                 $query_param = ['search' => $request['search']];
//             } else {
//                 // Fetch patients who have medical histories with the specific doctor
//                 $query = MedicalHistory::with([
//                     'testTypes' => function ($q) {
//                         $q->orderBy('created_at', 'desc'); // Order test types by created_at
//                     }
//                 ]);

//                 $query->where('doctor_id', $doctor->id);
//             }
//         } elseif ($currentUser->can('nurse_dashboard')) {
//             $nurse = Nurse::where('admin_id', $currentUser->id)->first();
//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->where('nurse_id', $nurse->id)->latest();
//                 $query_param = ['search' => $request['search']];
//             } else {
//                 $query = MedicalHistory::with([
//                     'testTypes' => function ($q) {
//                         $q->orderBy('created_at', 'desc'); // Order test types by created_at
//                     }
//                 ]);
//             }
//         } elseif ($currentUser->can('radiologist_dashboard')) {
//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->whereHas('medicalHistories', function ($q) {
//                     $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
//                 })->latest();

//                 $query_param = ['search' => $request['search']];
//             } else {
//                 $query = $this->patient->whereHas('medicalHistories', function ($q) {
//                     $q->where('radiology_test_required', true)->where('radiology_test_progress', 'pending');
//                 })->latest();
//             }

//         } elseif ($currentUser->can('lab_technician_dashboard')) {
//             // User is a pharmacist, filter patients with medical history and lab_test_required

//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->whereHas('medicalHistories', function ($q) {
//                     $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
//                 })->latest();

//                 $query_param = ['search' => $request['search']];
//             } else {
//                 $query = $this->patient->whereHas('medicalHistories', function ($q) {
//                     $q->where('lab_test_required', true)->where('lab_test_progress', 'pending');
//                 })->latest();
//             }
//         } else {
//             // User is a Super Admin, fetch all patients
//             $query = $this->patient->latest();

//             if ($request->has('search')) {
//                 $key = explode(' ', $request['search']);
//                 $query = $this->patient->where(function ($q) use ($key) {
//                     foreach ($key as $value) {
//                         $q->orWhere('id', 'like', "%{$value}%")
//                             ->orWhere('full_name', 'like', "%{$value}%")
//                             ->orWhere('phone', 'like', "%{$value}%")
//                             ->orWhere('email', 'like', "%{$value}%")
//                             ->orWhere('registration_no', 'like', "%{$value}%");
//                     }
//                 })->latest();
//                 $query_param = ['search' => $request['search']];
//             }
//         }


//         $medicalHistories = $query->paginate(Helpers::pagination_limit())->appends($query_param);

//         $totalTestTypes = $medicalHistories->sum(function ($history) {
//             return $history->testTypes ? $history->testTypes->count() : 0;
//         });

//         return view('admin-views.labs.list', compact('medicalHistories', 'search', 'totalTestTypes'));
//     }

//     /**
//      * Show the form for editing the specified resource.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function edit($id)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      *
//      * @param  \Illuminate\Http\Request  $request
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function update(Request $request, $id)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      *
//      * @param  int  $id
//      * @return \Illuminate\Http\Response
//      */
//     public function destroy($id)
//     {
//         //
//     }
// }
