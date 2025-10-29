<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\TimeSchedule;
use App\Models\Admin;
use App\Models\Department;
use App\Models\AppointmentSlot;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class AppointmentTimeScheduleController extends Controller
{
    function __construct(
        private TimeSchedule $timeSchedule,
    ) {
        $this->middleware('checkAdminPermission:appointment_schedule.add-new,index')->only(['index']);
    }

    public function index(Request $request, $doctor_id)
    {


        $doctor = Admin::find($doctor_id);
        $timeSchedules = $doctor->timeSchedules;


        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $timeSchedules->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $timeSchedules;
        }
        $doctors = $query;
        return view('admin-views.time_schedules.index', compact('doctor', 'timeSchedules', 'search'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->timeSchedule->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->timeSchedule->latest();
        }
        $timeSchedules = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.time_schedules.list', compact('timeSchedules', 'search'));
    }

    public function doctor_list(Request $request): JsonResponse
    {
        $doctorId = $request->input('doctor_id');
        $day = $request->input('day');

        // Retrieve the doctor
        $doctor = Admin::find($doctorId);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Get all time schedules for the doctor on the given day
        $timeSchedules = $doctor->timeSchedules()
            ->where('day', $day)
            ->orderBy('start')
            ->get(['id', 'day', 'start', 'end']);

        return response()->json([
            'doctor_id' => $doctorId,
            'day' => $day,
            'time_schedules' => $timeSchedules
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.time_schedules.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request, $doctor_id)
    {
        try {
            Log::info("Store function called for doctor_id: $doctor_id", ['request' => $request->all()]);

            // Validate input
            $validatedData = $request->validate([
                'day' => 'required|string',
                'start' => 'required|date_format:H:i',
                'end' => 'required|date_format:H:i',
            ]);

            $start = \Carbon\Carbon::parse($validatedData['start'])->format('H:i');
            $end = \Carbon\Carbon::parse($validatedData['end'])->format('H:i');

            if ($end <= $start) {
                Toastr::error(translate('End Time must be after the start time!'));
                return back();
            }

            // Check for overlapping schedules
            $overlap = TimeSchedule::where('doctor_id', $doctor_id)
                ->where('day', $validatedData['day'])
                ->where(function ($query) use ($start, $end) {
                    $query->whereBetween('start', [$start, $end])
                        ->orWhereBetween('end', [$start, $end])
                        ->orWhere(function ($query) use ($start, $end) {
                            $query->where('start', '<=', $start)
                                ->where('end', '>=', $end);
                        });
                })
                ->exists();

            if ($overlap) {
                Toastr::error(translate('This time range overlaps with an existing schedule!'));
                return back();
            }

            // Save the time schedule
            $timeSchedule = TimeSchedule::create([
                'doctor_id' => $doctor_id,
                'day' => $validatedData['day'],
                'start' => $start,
                'end' => $end,
            ]);

            Log::info("TimeSchedule saved successfully", ['timeSchedule' => $timeSchedule]);

            Toastr::success(translate('Schedule added successfully!'));
            return back();
        } catch (\Exception $e) {
            Log::error("Error storing time schedule: {$e->getMessage()}", ['exception' => $e]);
            Toastr::error(translate('Something went wrong!'));
            return back();
        }
    }




    public function bulk(Request $request, $doctor_id)
    {
        try {
            $cartData = json_decode($request->input('cart'), true);

            foreach ($cartData as $scheduleData) {
                try {
                    $day = $scheduleData['day'];
                    $start = \Carbon\Carbon::parse($scheduleData['start'])->format('H:i');
                    $end = \Carbon\Carbon::parse($scheduleData['end'])->format('H:i');

                    if ($end <= $start) {
                        Log::warning("Invalid time range skipped: $start - $end");
                        continue;
                    }

                    // Check for overlapping schedules for the same doctor and day
                    $overlap = TimeSchedule::where('doctor_id', $doctor_id)
                        ->where('day', $day)
                        ->where(function ($query) use ($start, $end) {
                            $query->whereBetween('start', [$start, $end])
                                ->orWhereBetween('end', [$start, $end])
                                ->orWhere(function ($q) use ($start, $end) {
                                    $q->where('start', '<=', $start)
                                        ->where('end', '>=', $end);
                                });
                        })->exists();

                    if ($overlap) {
                        Log::info("Skipped overlapping schedule on $day: $start - $end");
                        continue;
                    }

                    $doctor = Admin::find($doctor_id);
                    if (!$doctor) {
                        Log::error("Doctor not found: $doctor_id");
                        continue;
                    }

                    $timeSchedule = new TimeSchedule([
                        'day' => $day,
                        'start' => $start,
                        'end' => $end,
                    ]);

                    $doctor->timeSchedules()->save($timeSchedule);

                    Log::info("Time schedule saved for $day: $start - $end");
                } catch (\Exception $e) {
                    Log::error("Error saving schedule for $doctor_id: " . $e->getMessage());
                    continue;
                }
            }

            return response()->json(['message' => 'Bulk schedule created successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function storeForBulk(Request $request, $doctor_id)
    {

        try {
            // Validate the request data

            $validatedData = $request->validate([
                'day' => 'required|string',
                // 'time_type' => 'required|string',
                'start' => 'required|date_format:H:i',
                'end' => 'required|date_format:H:i',
                // 'slot_duration' => 'required|integer',
            ]);
            $start =  \Carbon\Carbon::parse($request->input('start'))->format('H:i');
            $end =  \Carbon\Carbon::parse($request->input('end'))->format('H:i');

            if ($end <= $start) {
                Toastr::error(translate('End Time must be after the start time!'));
                return back();
            }


            // Check for an existing schedule for the specific doctor
            $existingSchedule = TimeSchedule::where('doctor_id', $doctor_id)
                ->where('day', $validatedData['day'])
                //->where('time_type', $validatedData['time_type'])
                ->first();

            if ($existingSchedule) {
                Toastr::error(translate('You already added a schedule at this day and time frame!'));
                return back();
            }

            // Find the doctor by ID
            $doctor = Admin::findOrFail($doctor_id);

            $timeSchedule = new TimeSchedule([
                'day' => $validatedData['day'],
                // 'time_type' => $validatedData['time_type'],
                'start' => $validatedData['start'],
                'end' => $validatedData['end'],
                // 'slot_duration' => $validatedData['slot_duration'],
            ]);

            // Save the time$timeSchedule record
            $doctor->timeSchedules()->save($timeSchedule);

            try {
                // Get the time schedule details
                $startTime = $timeSchedule->start;
                $endTime = $timeSchedule->end;
                $slotDuration = $timeSchedule->slot_duration;

                // Parse the start and end times
                $startTime = \Carbon\Carbon::createFromFormat('H:i', $startTime);
                $endTime = \Carbon\Carbon::createFromFormat('H:i', $endTime);

                // Initialize the current time
                $currentTime = $startTime;

                // Create appointment slots
                while ($currentTime->lt($endTime)) {
                    // Calculate end time for the slot
                    $slotEndTime = $currentTime->copy()->addMinutes($slotDuration);

                    // Adjust slot end time if it goes beyond the end time
                    if ($slotEndTime->gt($endTime)) {
                        $slotEndTime = $endTime;
                    }

                    // Log information for debugging
                    Log::info("Creating slot: {$currentTime->format('H:i')} - {$slotEndTime->format('H:i')}");

                    // Create an appointment slot record
                    AppointmentSlot::create([
                        'time_schedule_id' => $timeSchedule->id,
                        'date' => now(),
                        'start_time' => $currentTime->format('H:i'),
                        'end_time' => $slotEndTime->format('H:i'),
                    ]);

                    // Move to the next time slot
                    $currentTime->addMinutes($slotDuration);
                }

                Log::info('Appointment slots created successfully.');
            } catch (\Exception $e) {
                Log::error("Error creating appointment slots: {$e->getMessage()}");
            }
            Toastr::success(translate('TimeSchedule Saved Successfully!'));

            return back();
        } catch (\Exception $e) {
            Toastr::success(translate('Something went wrong!'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $timeSchedule = TimeSchedule::find($id);


        return view('admin-views.time_schedules.view', compact('TimeSchedule'));
    }



    public function edit($id)
    {
        $timeSchedule = TimeSchedule::find($id);
        $roles = Role::where('guard_name', 'admin')->get();
        $departments = Department::all();
        return view('admin-views.time_schedules.edit', compact('TimeSchedule', 'roles', 'departments'));
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

            // Find the TimeSchedule by ID
            $timeSchedule = TimeSchedule::findOrFail($id);

            // Update Admin (User) Record
            $admin = $timeSchedule->admin;
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

            // Update TimeSchedule Record

            if ($request->hasFile('image')) {
                // Delete existing image if any
                Storage::disk('public')->delete($timeSchedule->image);

                // Store the new image
                $imagePath = $request->file('image')->store('staff_images', 'public');
                $timeSchedule->image = $imagePath;
            }

            $timeSchedule->update([
                'experience' => $validatedData['experience'],
                'gender' => $validatedData['gender'],
                'specialization' => $validatedData['specialization'],
                'about' => $validatedData['about'],
            ]);

            if ($request->hasFile('image')) {
                $timeSchedule->update([
                    'image' => $imagePath,
                ]);
            }

            // Associate TimeSchedule with Admin
            $timeSchedule->admin()->associate($admin);

            // Associate TimeSchedule with Department
            $department = Department::findOrFail($request->department_id);
            $department->TimeSchedules()->save($timeSchedule);

            Toastr::success(translate('TimeSchedule Data Updated successfully!'));
            return redirect()->route('admin.TimeSchedule.list');
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
    public function  destroy($doctor_id, $id)
    {
        try {
            $timeSchedule = TimeSchedule::findOrFail($id);
            // Delete the TimeSchedule
            $timeSchedule->delete();

            Toastr::success(translate('TimeSchedule Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}
