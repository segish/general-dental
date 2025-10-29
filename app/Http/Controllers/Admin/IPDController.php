<?php

namespace App\Http\Controllers\Admin;

use App\Models\IPDRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\Ward;
use App\Models\Bed;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use App\CentralLogics\Helpers;

class IPDController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission:ipd.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:ipd.add-new,index')->only(['index']);
    }

    public function index(Request $request): Factory|View|Application
    {
        $patients = Patient::all();
        $wards = Ward::all();
        $beds = Bed::all();
        $doctors = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'medical_record.add-new');
        })->get();

        $appointments = Appointment::all();
        return view('admin-views.ipd.index', compact('patients', 'beds', 'wards', 'doctors', 'appointments'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = IPDRecord::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('ipd_status', 'like', "%{$value}%")
                        ->orWhere('admission_date', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = IPDRecord::latest();
        }
        $ipdRecords = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.ipd.list', compact('ipdRecords', 'search'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Admin::all();
        $wards = Ward::all();
        $beds = Bed::all();
        return view('admin-views.ipd.create', compact('patients', 'doctors', 'wards', 'beds'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_datetime' => 'required|date',
            'ward_id' => 'required|exists:wards,id',
            'bed_id' => 'required|exists:beds,id',
            'admitting_doctor_id' => 'required|exists:admins,id',
            'admission_date' => 'required|date',
            'discharge_summary' => 'nullable|string',
        ]);

        try {
            $ipdRecord = IPDRecord::create([
                'visit_id' => $request->visit_id,  // Assuming visit_id is generated from VisitController
                'ward_id' => $request->ward_id,
                'bed_id' => $request->bed_id,
                'admitting_doctor_id' => $request->admitting_doctor_id,
                'admission_date' => Carbon::parse($request->admission_date),
                'discharge_summary' => $request->discharge_summary,
                'ipd_status' => 'Admitted',
            ]);

            Toastr::success(translate('IPD visit recorded successfully!'));
            return redirect()->route('admin.ipd.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        $ipdRecord = IPDRecord::findOrFail($id);
        return view('admin-views.ipd.show', compact('ipdRecord'));
    }

    public function edit($id)
    {
        $ipdRecord = IPDRecord::findOrFail($id);
        $patients = Patient::all();
        $doctors = Admin::all();
        $wards = Ward::all();
        $beds = Bed::all();
        return view('admin-views.ipd.edit', compact('ipdRecord', 'patients', 'doctors', 'wards', 'beds'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_datetime' => 'date',
            'ward_id' => 'exists:wards,id',
            'bed_id' => 'exists:beds,id',
            'admitting_doctor_id' => 'exists:admins,id',
            'admission_date' => 'date',
            'discharge_summary' => 'nullable|string',
        ]);
        try {
            $ipdRecord = IPDRecord::findOrFail($id);
            $ipdRecord->update($request->all());
            Toastr::success(translate('IPD visit updated successfully!'));
            return redirect()->route('admin.ipd.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            IPDRecord::findOrFail($id)->delete();
            Toastr::success(translate('IPD visit deleted successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}
