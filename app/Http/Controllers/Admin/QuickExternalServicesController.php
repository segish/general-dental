<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuickService;
use App\CentralLogics\Helpers;
use App\Models\TestType;
use App\Models\RadiologyType;
use App\Models\MedicalLabResult;
use App\Models\RadiologyTestResult;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Department;
use App\Models\Appointment;
use App\Models\TimeSchedule;
use App\Models\Patient;
use App\Models\Admin;
use Illuminate\Support\Str;
class QuickExternalServicesController extends Controller
{

    function __construct(
        private QuickService $quick_service,

     ) {
        // $this->middleware('checkAdminPermission:quick_service.list,list')->only(['list']);
        // $this->middleware('checkAdminPermission:quick_service.add-new,index')->only(['index']);
     }
    
    public function request_tests(Request $request){

      
        $request->validate([
            'service_name' => 'required|string',
        ]);
    
        $quickService = QuickService::create([
            'service_name' => $request->input('service_name'),
            'patient_name' => $request->input('patient_name'),
            'requested_by' => auth('admin')->user()->id, // or however you determine the requesting admin
            'patient_id' => $request->input('patient_id'),
            'status' => 'pending', // or set the default status as needed
        ]);
    
        if ($request->hasFile('doc')) {
        $doc = $request->file('doc');
        $docName = now()->format('YmdHis') . '_' . $doc->getClientOriginalName();
        $docPath = $doc->storeAs('quick_service_docs', $docName, 'public');
        $quickService->doc_path =  $docPath;
        $quickService->save();
        }
        if ($request->hasFile('images')) {
            $images = $request->file('images');
        
            $imagePaths = [];
        
            foreach ($images as $image) {
                $imageName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('quick_service_images', $imageName, 'public');
        
                $imagePaths[] = $imagePath;
            }
        
            $quickService->image_path = json_encode($imagePaths);
            $quickService->save();
        }
        
        // if ($request->hasFile('images')) {
        //     $image = $request->file('images');
        //     $imageName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
        //     $imagePath = $image->storeAs('quick_service_images', $imageName, 'public');
        //     $quickService->image_path =  $imagePath ;
        //     $quickService->save();
        // }

        
        // Attach Test Types
        if ( $request->input('lab_test_required')) {
            $quickService->testTypes()->attach($request->input('test_types'));
        }
    
        // Attach Radiology Types
        if ($request->input('radiology_is_required')) {
            $quickService->radiologyTypes()->attach($request->input('radiology_types'));
        }

        return response()->json([], 200);
     
    }

    public function other_services(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string',
            'admin_id' => 'required|exists:admins,id',
        ]);
    
        $otherService = QuickService::create([
            'service_name' => $request->input('service_name'),
            'requested_by' => auth('admin')->user()->id,
            'patient_name' => $request->input('patient_name'),
            'assigned_to' => $request->input('admin_id'),
            'patient_id' => $request->input('patient_id'),
            'status' => 'pending',
        ]);
    
        if ($request->hasFile('doc')) {
            $doc = $request->file('doc');
            $docName = now()->format('YmdHis') . '_' . $doc->getClientOriginalName();
            $docPath = $doc->storeAs('other_service_docs', $docName, 'public');
            $otherService->doc_path =  $docPath;
            $otherService->save();
        }
    
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imagePaths = [];
    
            foreach ($images as $image) {
                $imageName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('other_service_images', $imageName, 'public');
                $imagePaths[] = $imagePath;
            }
    
            $otherService->image_path = json_encode($imagePaths);
            $otherService->save();
        }
    
        return response()->json([], 200);
    }

    public function status(Request $request)
    {
        $request->validate([
            'quick_service_id' => 'required|exists:quick_services,id',
            'status' => 'required|in:pending,completed',
        ]);

        QuickService::find($request->input('quick_service_id'))
            ->update(['status' => $request->input('status')]);

        return response()->json(['message' => 'status updated successfully'], 200);
    }
    public function list(Request $request): Factory|View|Application
    {
        $testTypes = TestType::all();
        $radiologyTypes = RadiologyType::all();
        $admins = Admin::all();
        $patients = Patient::all();
        $query_param = [];
        $search = $request['search'];
        $currentUser = auth('admin')->user();
        if ($currentUser->can('receptionist_dashboard') || $currentUser->hasRole('Super Admin')){
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->quick_service->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                })->latest();
                $query_param = ['search' => $request['search']];
            } else {
            $query = $this->quick_service->latest();
            }
        }
        else if ($currentUser->can('lab_technician_dashboard')){
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->quick_service->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                })->whereNull('assigned_to')->whereHas('testTypes')->latest();
                $query_param = ['search' => $request['search']];
            } else {
            $query = $this->quick_service->whereNull('assigned_to')->whereHas('testTypes')->latest();
            }
        }
        else if ($currentUser->can('radiologist_dashboard')){
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->quick_service->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                })->whereNull('assigned_to')->whereHas('radiologyTypes')->latest();
                $query_param = ['search' => $request['search']];
            } else {
            $query = $this->quick_service->whereNull('assigned_to')->whereHas('radiologyTypes')->latest();
            }
        } else{
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->quick_service->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                })->where('assigned_to', $currentUser->id)->latest();
                $query_param = ['search' => $request['search']];
            } else {
            $query = $this->quick_service->where('assigned_to', $currentUser->id)->latest();
            }
        }
        $quick_services = $query->paginate(Helpers::pagination_limit())->appends($query_param);
         return view('admin-views.quick_services.list', compact('quick_services','testTypes','radiologyTypes','patients', 'search','admins'));
    }

    public function view($id)
    {
        $quick_service = QuickService::find($id);
      

        return view('admin-views.quick_services.view',compact('quick_service'));
    }


    public function rad_result(Request $request)
    {

       
        $request->validate([
            'quick_service_id' => 'required|exists:quick_services,id',
            'test_result' => 'required|string',
            'radiology_type_id' => 'required|exists:radiology_types,id', // Add this validation rule
        ]);

        $img_names = [];
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image_data = Helpers::upload('radiology_results/', 'png', $img);
                $img_names[] = $image_data;
            }
            $image_data = json_encode($img_names);
        } else {
            $image_data = json_encode([]);
        }

        $labResult = new RadiologyTestResult([
            'quick_service_id' => $request->input('quick_service_id'),
            'result_content' => $request->input('test_result'),
            'image'=>$image_data,
        ]);

        $labResult->save();

        return response()->json(['message' => 'Radiology result added successfully'], 200);
    }

    public function lab_result(Request $request)
    {

       
        $request->validate([
            'quick_service_id' => 'required|exists:quick_services,id',
            'test_result' => 'required|string',
            'test_type_id' => 'required|exists:test_types,id', // Add this validation rule
        ]);

        $img_names = [];
        if (!empty($request->file('images'))) {
            foreach ($request->images as $img) {
                $image_data = Helpers::upload('radiology_results/', 'png', $img);
                $img_names[] = $image_data;
            }
            $image_data = json_encode($img_names);
        } else {
            $image_data = json_encode([]);
        }

        $labResult = new MedicalLabResult([
            'quick_service_id' => $request->input('quick_service_id'),
            'result_content' => $request->input('test_result'),
            'test_name' => 'Test Name Here',
            'image'=>$image_data,
        ]);

        $labResult->save();

        return response()->json(['message' => 'test result added successfully'], 200);
    }

}
