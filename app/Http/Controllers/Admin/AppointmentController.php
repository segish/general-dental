<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\TimeSchedule;
use App\Models\Patient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Department;
use Illuminate\Support\Str;
use App\CentralLogics\SMS_module;

class AppointmentController extends Controller
{
    function __construct(
        private Appointment $appointment,
    ) {
        $this->middleware('checkAdminPermission:dashboard,dashboard')->only(['dashboard']);
        $this->middleware('checkAdminPermission:appointment.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:appointment.status,updateStatus')->only(['updateStatus']);
    }

    public function index(Request $request)
    {
        $patients = Patient::all();
        $doctors = Doctor::all();


        return view('admin-views.appointments.index', compact('patients', 'doctors'));
    }

    public function getPatients(Request $request)
    {
        $search = $request->input('search');
        $patients = Patient::with('visits')
            ->where('full_name', 'LIKE', "%{$search}%")
            ->orWhere('registration_no', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->take(20) // Limit results for better performance
            ->get(['id', 'full_name', 'registration_no', 'phone']);

        return response()->json($patients);
    }

    /**
     * AJAX: Get visits for a specific patient (for dynamic dropdown)
     */
    public function getPatientVisits($patientId)
    {
        $visits = \App\Models\Visit::where('patient_id', $patientId)
            ->with('doctor', 'patient')
            ->orderByDesc('visit_datetime')
            ->get();

        $results = $visits->map(function ($visit) {
            return [
                'id' => $visit->id,
                'doctor_name' => $visit->doctor ? $visit->doctor->full_name : '---',
                'visit_datetime' => $visit->visit_datetime ? $visit->visit_datetime->format('M d, Y h:i A') : '',
                'patient_full_name' => $visit->patient ? $visit->patient->full_name : '',
            ];
        });

        return response()->json($results);
    }

    public function list(Request $request): Factory|View|Application
    {

        $patients = Patient::all();
        $doctors = Admin::whereHas('roles.permissions', function ($query) {
            $query->where('name', 'medical_record.add-new');
        })->get();
        $lastPatient = Patient::latest()->first();
        $lastRegistrationNo = $lastPatient ? $lastPatient->registration_no : null;
        $time_schedules = TimeSchedule::all();


        $query_param = [];
        $search = $request['search'];
        if (auth('admin')->user()->hasRole('Super Admin')) {
            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->appointment->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                })->latest();
                $query_param = ['search' => $request['search']];
            } else {
                $query = $this->appointment->latest();
            }
        } else if (auth('admin')->check() && auth('admin')->user()->can('medical_record.add-new')) {
            $doctor_id = auth('admin')->id();

            $query = $this->appointment->where('doctor_id', $doctor_id);

            if ($request->has('search')) {
                $key = explode(' ', $request->search);
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('title', 'like', "%{$value}%")
                            ->orWhere('description', 'like', "%{$value}%");
                    }
                });
            }

            $query = $query->latest();
        } else if (auth('admin')->user()->can('visit.add-new')) {

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $query = $this->appointment->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhereHas('doctor', function ($doctorQuery) use ($value) {
                                $doctorQuery->whereHas('admin', function ($adminQuery) use ($value) {
                                    $adminQuery->whereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ["%{$value}%"]);
                                });
                            })
                            ->orWhereHas('patient', function ($patientQuery) use ($value) {
                                $patientQuery->where('full_name', 'like', "%{$value}%");
                            });
                    }
                });

                if ($request->has('date')) {
                    // Use '=' instead of 'like' for date comparison
                    $query->whereDate('date', '=', $request->input('date'));
                }
                $query->latest();
                $query_param = ['search' => $request->input('search'), 'date' => $request->input('date')];
            } else {
                $query = $this->appointment->latest();
            }
        }
        $appointments = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.appointments.list', compact('appointments', 'search', 'doctors', 'patients'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.appointments.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $appointment_id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,done',
        ]);

        $appointment = Appointment::find($appointment_id);

        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $appointment->status = $request->status;
        $appointment->save();

        $patient_email = $appointment->patient->email;

        if ($appointment->patient->phone) {
            try {

                $data = [
                    'description' => 'Appointment Schedule'

                ];

                //send message with phone
                $yegara_sms = Helpers::get_business_settings('yegara_sms');
                $to = "0" . substr($appointment->patient->phone, 4, 9);
                if (isset($yegara_sms) && ($yegara_sms['status'] == 1 || $yegara_sms['status'] == "1")) {
                    SMS_module::send($to, $appointment->patient->full_name . ',' . $data['description'] . ',' . now(), 'reminder');
                }
            } catch (\Exception $e) {
                \Log::error($e->getMessage());
                return response()->json([$e->getMessage()], 403);
            }
        }

        if ($appointment->patient->email) {
            try {
                $emailServices = Helpers::get_business_settings('mail_config');
                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($appointment->patient->email)->send(new \App\Mail\AppointmentStatusUpdate($appointment));
                }
            } catch (\Throwable $e) {
                return response()->json([$e->getMessage()], 403);
                \Log::error($e->getMessage());
            }
        }

        return response()->json(['message' => 'Status updated successfully'], 200);
    }



    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'date' => 'required|date',
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:admins,id',
            'slot_id' => 'required|exists:time_schedules,id',
            'notes' => 'nullable|string',
        ]);

        // Create the appointment directly
        $appointment = new Appointment([
            'date' => $validatedData['date'],
            'patient_id' => $validatedData['patient_id'],
            'doctor_id' => $validatedData['doctor_id'],
            'time_schedule_id' => $validatedData['slot_id'],
            'notes' => $validatedData['notes'] ?? null,
            'status' => 'Scheduled',
            'appointed_by' => auth()->id(), // Optional
        ]);

        $appointment->save();

        // Send SMS (if enabled)
        try {
            $data = ['description' => 'Appointment Schedule'];
            $yegara_sms = Helpers::get_business_settings('yegara_sms');
            $to = "0" . substr($appointment->patient->phone, 4, 9);

            if (isset($yegara_sms) && ($yegara_sms['status'] == 1)) {
                SMS_module::send(
                    $to,
                    $appointment->patient->full_name . ', ' . $data['description'] . ', ' . now(),
                    'reminder'
                );
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([$e->getMessage()], 403);
        }

        // Send Email (if enabled)
        try {
            $emailServices = Helpers::get_business_settings('mail_config');

            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($appointment->patient->email)
                    ->send(new \App\Mail\NotifyAppointmentSchedul($appointment));
            }
        } catch (\Throwable $e) {
            \Log::error($e->getMessage());
        }

        Toastr::success(translate('Appointment Created Successfully!'));
        return back();
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $appointment = appointment::find($id);


        return view('admin-views.appointments.view', compact('appointment'));
    }



    public function edit($id)
    {
        $appointment = appointment::find($id);
        $roles = Role::where('guard_name', 'admin')->get();
        $departments = Department::all();
        return view('admin-views.appointments.edit', compact('appointment', 'roles', 'departments'));
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
        try {
            // Validate the request
            $validatedData = $request->validate([
                'f_name' => 'required|string',
                'l_name' => 'required|string',
                'email' => 'required|email',
                'password' => 'nullable|min:6',
                'phone' => 'required|string',
                'roles' => 'required|array',
                'experience' => 'required|numeric',
                'gender' => 'required|string',
                'department_id' => 'required|exists:departments,id',
                'specialization' => 'required|string',
                'about' => 'required|string',
            ]);

            // Find the appointment by ID
            $appointment = appointment::findOrFail($id);

            // Update Admin (User) Record
            $admin = $appointment->admin;
            $admin->update([
                'f_name' => $validatedData['f_name'],
                'l_name' => $validatedData['l_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
            ]);


            // Update Password if provided
            if ($request->has('password')) {
                $admin->update([
                    'password' => bcrypt($validatedData['password']),
                ]);
            }

            // Update Roles
            $admin->syncRoles($request->input('roles'));

            // Update appointment Record

            if ($request->hasFile('image')) {
                // Delete existing image if any
                Storage::disk('public')->delete($appointment->image);

                // Store the new image
                $imagePath = $request->file('image')->store('staff_images', 'public');
                $appointment->image = $imagePath;
            }

            $appointment->update([
                'experience' => $validatedData['experience'],
                'gender' => $validatedData['gender'],
                'specialization' => $validatedData['specialization'],
                'about' => $validatedData['about'],
            ]);

            if ($request->hasFile('image')) {
                $appointment->update([
                    'image' => $imagePath,
                ]);
            }

            // Associate appointment with Admin
            $appointment->admin()->associate($admin);

            // Associate appointment with Department
            $department = Department::findOrFail($request->department_id);
            $department->appointments()->save($appointment);

            Toastr::success(translate('appointment Data Updated successfully!'));
            return redirect()->route('admin.appointment.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
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
        try {
            $appointment = appointment::findOrFail($id);
            $admin = Admin::findOrFail($appointment->admin_id);

            // Delete the appointment
            $appointment->delete();

            // Delete associated admin
            $admin->delete();

            Toastr::success(translate('appointment Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}
