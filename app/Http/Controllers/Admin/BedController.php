<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use App\Models\Ward;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Bed;
use App\Models\BillingDetails;
use App\Models\Billing;
use App\Models\Payment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class BedController extends Controller
{
    function __construct(
        private Bed $bed,
    ) {}

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $wards = Ward::all();

        return view('admin-views.beds.index', compact('roles', 'wards'));
    }

    public function list(Request $request): Factory|View|Application
    {

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->bed->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('title', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->bed->latest();
        }
        $beds = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.beds.list', compact('beds', 'search'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.beds.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'bed_number' => 'required|unique:beds,bed_number',
            'type' => 'nullable',
            'price' => 'required|numeric',
            'ward_id' => 'nullable|exists:wards,id',
            'status' => 'nullable|in:available,occupied',
            'occupancy_status' => 'nullable|in:cleaning,maintenance,normal',
            'additional_notes' => 'nullable',
        ]);

        try {
            if (!empty($data['ward_id'])) {
                $ward = Ward::find($data['ward_id']);

                // Count existing beds in this ward
                $currentBedsCount = Bed::where('ward_id', $ward->id)->count();

                // Check if ward has a capacity limit and is full
                if (!is_null($ward->max_beds_capacity) && $currentBedsCount >= $ward->max_beds_capacity) {
                    Toastr::error(translate('The maximum bed capacity for this ward has been reached.'));
                    return redirect()->back()->withInput();
                }
            }

            Bed::create($data);
            Toastr::success(translate('Bed Saved Successfully!'));
            return redirect()->route('admin.bed.list');
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
    private function generateUniqueTransactionReference()
    {
        $reference = '';

        do {
            $reference = 'TXN' .  Str::random(10);;
        } while (Payment::where('transaction_reference', $reference)->exists());

        return $reference;
    }

    public function associate_patient($id)
    {
        try {
            request()->validate([
                'patient_id' => 'required|exists:patients,id',
            ]);

            $bed = Bed::find($id);

            if (!$bed) {
                return back()->with('error', 'Bed not found');
            }

            if ($bed->patient_id) {
                return back()->with('error', 'Bed is already occupied.');
            }

            $patient = Patient::find(request('patient_id'));

            if (!$patient) {
                return back()->with('error', 'Patient not found.');
            }


            $bed->update([
                'patient_id' => $patient->id,
                'status' => 'occupied',
                'admission_date' => now(),
                'discharge_date' => NULL,


            ]);
            $ward = $bed->ward;
            $ward->current_occupancy -= 1;
            $ward->save();
            Toastr::success(translate(('Bed Assigned to the Patient Successfully!')));
            return back()->with('success', 'Bed Assigned to the Patient Successfully!.');
        } catch (\Exception $e) {
            Toastr::success(translate($e->getMessage()));
        }
    }

    public function view(Request $request, $id)
    {

        $search = $request->date;
        $bed = Bed::findOrFail($id);
        $patients = Patient::all();
        // $services = Service::all();
        $notes = [];
        if ($bed->patient) {
            $notes = $bed->patient->notes()->get()->sortByDesc('created_at');
        }
        if ($search && $notes) {
            $notes = $notes->filter(function ($note) use ($search) {
                return $note->created_at->toDateString() === $search;
            });
        }
        return view('admin-views.beds.view', compact('bed', 'patients', 'search', 'notes'));
    }

    public function show($id)
    {
        $role = Role::find($id);
        $bed = Bed::findOrFail($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.beds.show', compact('role', 'rolePermissions'));
    }



    public function edit($id)
    {
        $bed = bed::find($id);
        $wards = Ward::all();


        return view('admin-views.beds.edit', compact('bed', 'wards'));
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
        $request->validate([
            'bed_number' => 'required|unique:beds,bed_number,' . $id,
            'type' => 'nullable',
            'price' => 'required|numeric',
            'ward_id' => 'nullable|exists:wards,id',
            'status' => 'nullable|in:available,occupied',
            'occupancy_status' => 'nullable|in:cleaning,maintenance,normal',
            'additional_notes' => 'nullable',
        ]);

        try {
            $bed = Bed::findOrFail($id);

            // Check if ward has capacity and bed is being moved to a different ward
            if (!empty($request->ward_id) && $request->ward_id != $bed->ward_id) {
                $ward = Ward::find($request->ward_id);
                $currentBedsCount = Bed::where('ward_id', $ward->id)->count();

                if (!is_null($ward->max_beds_capacity) && $currentBedsCount >= $ward->max_beds_capacity) {
                    Toastr::error(translate('The maximum bed capacity for the selected ward has been reached.'));
                    return redirect()->back()->withInput();
                }
            }

            $bed->bed_number = $request->bed_number;
            $bed->type = $request->type;
            $bed->price = $request->price;
            $bed->status = $request->status;
            $bed->ward_id = $request->ward_id;
            $bed->room_number = $request->room_number;
            $bed->occupancy_status = $request->occupancy_status;
            $bed->additional_notes = $request->additional_notes;

            $bed->save();

            Toastr::success(translate('Bed updated successfully!'));
            return redirect()->route('admin.bed.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
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
            $bed = bed::findOrFail($id);
            $bed->delete();
            Toastr::success(translate('bed Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}
