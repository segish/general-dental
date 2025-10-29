<?php

namespace App\Http\Controllers\Admin;

use App\Models\OPDRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Admin;
use App\Models\Appointment;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use App\CentralLogics\Helpers;

class OPDController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission:opd.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:opd.add-new,index')->only(['index']);
    }

    public function index(Request $request): Factory|View|Application
    {
        $patients = Patient::all();
        $doctors = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'medical_record.add-new');
        })->get();

        $appointments = Appointment::all();
        return view('admin-views.opd.index', compact('patients', 'doctors', 'appointments'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = OPDRecord::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('visit_datetime', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = OPDRecord::latest();
        }
        $opdRecords = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.opd.list', compact('opdRecords', 'search'));
    }

    public function create()
    {
        $patients = Patient::all();
        $doctors = Admin::all();
        return view('admin-views.opd.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_datetime' => 'required|date',
        ]);

        try {
            $opdRecord = OPDRecord::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_id' => $request->appointment_id,
                'visit_datetime' => Carbon::parse($request->visit_datetime),
            ]);

            Toastr::success(translate('OPD visit recorded successfully!'));
            return redirect()->route('admin.opd.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    public function show($id)
    {
        $opdRecord = OPDRecord::findOrFail($id);
        return view('admin-views.opd.show', compact('opdRecord'));
    }

    public function edit($id)
    {
        $opdRecord = OPDRecord::findOrFail($id);
        $patients = Patient::all();
        $doctors = Admin::all();
        return view('admin-views.opd.edit', compact('opdRecord', 'patients', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'patient_id' => 'exists:patients,id',
            'doctor_id' => 'nullable|exists:admins,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'visit_datetime' => 'date',
        ]);
        try {
            $opdRecord = OPDRecord::findOrFail($id);
            $opdRecord->update($request->all());
            Toastr::success(translate('OPD visit updated successfully!'));
            return redirect()->route('admin.opd.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        try {
            OPDRecord::findOrFail($id)->delete();
            Toastr::success(translate('OPD visit deleted successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}
