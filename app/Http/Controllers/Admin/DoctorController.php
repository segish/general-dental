<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Admin;
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
use App\Models\Appointment;
use App\Models\TimeSchedule;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class DoctorController extends Controller
{
    function __construct(
        private Appointment $appointment,

     ) {
        $this->middleware('checkAdminPermission:doctor.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:doctor.add-new,index')->only(['index']);
     }

     public function doctor_dashboard(Request $request)
     {
         // Ensure the user is authenticated and has a doctor relationship
         $user = auth('admin')->user();

         // Fetch doctor's ID
         $doctor_id = $user->doctor->id;

         // Fetch patient and appointment counts
         $patient_count = Patient::where('doctor_id', $doctor_id)->count();
         $appointment_count = $this->appointment->where('doctor_id', $doctor_id)->count();

         // Search functionality
         $query_param = [];
         $search = $request->input('search');
         if ($search) {
             $key = explode(' ', $search);
             $query = $this->appointment->where(function ($q) use ($key) {
                 foreach ($key as $value) {
                     $q->orWhere('id', 'like', "%{$value}%")
                       ->orWhere('title', 'like', "%{$value}%")
                       ->orWhere('description', 'like', "%{$value}%");
                 }
             })->where('doctor_id', $doctor_id)->latest();
             $query_param = ['search' => $search];
         } else {
             $query = $this->appointment->where('doctor_id', $doctor_id)->latest();
         }

         // Paginate the appointments
         $appointments = $query->paginate(Helpers::pagination_limit())->appends($query_param);
         // Return the doctor dashboard view
         return view('admin-views.doctors.dashboard', compact('appointments', 'patient_count', 'appointment_count'));
     }
         public function index(Request $request)
     {
         $roles = Role::where('guard_name','admin')->get();
         $departments = Department::all();


         return view('admin-views.doctors.index', compact('roles','departments'));

     }

     public function list(Request $request): Factory|View|Application
     {

         $query_param = [];
         $search = $request['search'];
         if ($request->has('search')) {
             $key = explode(' ', $request['search']);
             $query = $this->doctor->where(function ($q) use ($key) {
                 foreach ($key as $value) {
                     $q->orWhere('id', 'like', "%{$value}%")
                         ->orWhere('title', 'like', "%{$value}%")
                         ->orWhere('description', 'like', "%{$value}%");
                 }
             })->latest();
             $query_param = ['search' => $request['search']];
         } else {
            $query = $this->doctor->latest();
         }
         $doctors = $query->paginate(Helpers::pagination_limit())->appends($query_param);
          return view('admin-views.doctors.list', compact('doctors', 'search'));
     }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $permission = Permission::get();
         return view('admin-views.doctors.create',compact('permission'));
     }

     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */


      public function store(Request $request)
      {

        try{
          $validatedData = $request->validate([
              'f_name' => 'required|string',
              'l_name' => 'required|string',
              'email' => 'required|email|unique:admins',
              'password' => 'required|min:6',
              'phone' => 'required|unique:admins',
              'roles' => 'required|array',
              'experience' => 'required|numeric',
              'gender' => 'required|string',
              'department_id' => 'required|exists:departments,id',
              'specialization' => 'required|string',
              'about' => 'required|string',
          ]);

          // Create Admin (User) Record
          $admin = Admin::create([
              'f_name' => $validatedData['f_name'],
              'l_name' => $validatedData['l_name'],
              'email' => $validatedData['email'],
              'password' => bcrypt($validatedData['password']),
              'phone' => $validatedData['phone'],
              'remember_token' => Str::random(10),
              'created_at' => now(),
              'updated_at' => now()
          ]);

          // Assign Roles to Admin
          $admin->assignRole($request->input('roles'));

            $image = $request->file('image');
            $imageName = now()->format('YmdHis') . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('staff_images', $imageName, 'public');

       // Create Doctor Record
            $doctor = new Doctor([
                'experience' => $validatedData['experience'],
                'gender' => $validatedData['gender'],
                'specialization' => $validatedData['specialization'],
                'about' => $validatedData['about'],
                'image' => $imagePath,
            ]);

            // Associate Doctor with Admin
            $doctor->admin()->associate($admin);

            $department = Department::findOrfail($request->department_id);
            $department->doctors()->save($doctor);
            // Save the Doctor record
            $doctor->save();
            Toastr::success(translate('Doctor Data Saved successfully!'));
            return redirect()->route('admin.list');

        } catch (ValidationException $e) {
            // Get validation errors
            $errors = $e->validator->getMessageBag()->all();
            // Flash errors to the session
            foreach ($errors as $error) {
                Toastr::error(translate($error));
            }
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
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
         $doctor = Doctor::find($id);


         return view('admin-views.doctors.view',compact('doctor'));
     }



     public function edit($id)
     {
         $doctor = doctor::find($id);
         $roles = Role::where('guard_name','admin')->get();
         $departments = Department::all();
         return view('admin-views.doctors.edit', compact('doctor','roles','departments'));
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
                  'email' => 'required|email' ,
                  'password' => 'nullable|min:6',
                  'phone' => 'required|string',
                  'roles' => 'required|array',
                  'experience' => 'required|numeric',
                  'gender' => 'required|string',
                  'department_id' => 'required|exists:departments,id',
                  'specialization' => 'required|string',
                  'about' => 'required|string',
              ]);

              // Find the Doctor by ID
              $doctor = Doctor::findOrFail($id);

              // Update Admin (User) Record
              $admin = $doctor->admin;
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

              // Update Doctor Record

              if ($request->hasFile('image')) {
                // Delete existing image if any
                Storage::disk('public')->delete($doctor->image);

                // Store the new image
                $imagePath = $request->file('image')->store('staff_images', 'public');
                $doctor->image = $imagePath;
            }

              $doctor->update([
                  'experience' => $validatedData['experience'],
                  'gender' => $validatedData['gender'],
                  'specialization' => $validatedData['specialization'],
                  'about' => $validatedData['about'],
              ]);

              if ($request->hasFile('image')) {
                $doctor->update([
                    'image' => $imagePath,
                ]);
            }

              // Associate Doctor with Admin
              $doctor->admin()->associate($admin);

              // Associate Doctor with Department
              $department = Department::findOrFail($request->department_id);
              $department->doctors()->save($doctor);

              Toastr::success(translate('Doctor Data Updated successfully!'));
              return redirect()->route('admin.list');

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
            $doctor = Doctor::findOrFail($id);
            $admin = Admin::findOrFail($doctor->admin_id);

            // Delete the doctor
            $doctor->delete();

            // Delete associated admin
            $admin->delete();

            Toastr::success(translate('Doctor Deleted Successfully!'));
            return back();


          } catch (\Exception $e) {
            Toastr::success($e->getMessage());
          }
      }
 }
