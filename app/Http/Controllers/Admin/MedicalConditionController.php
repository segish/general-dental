<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\MedicalCondition;
use App\Models\ConditionCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MedicalConditionController extends Controller
{
    function __construct(
        private MedicalCondition $medicalcondition,
    ) {
        $this->middleware('checkAdminPermission:medical_condition.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medical_condition.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $categories = ConditionCategory::all();
        return view('admin-views.medical_condition.index', compact('roles', 'categories'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->medicalcondition->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('code', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->medicalcondition->latest();
        }
        $medical_conditions = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.medical_condition.list', compact('medical_conditions', 'search'));
    }

    public function create()
    {
        $permission = Permission::get();
        $categories = ConditionCategory::all();
        return view('admin-views.medical_condition.create', compact('permission', 'categories'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:medical_conditions',
            'code' => 'nullable|string|max:255|unique:medical_conditions',
            'category_id' => 'required|exists:condition_categories,id',
            'description' => 'nullable|string',
        ]);

        try {
            $medicalcondition = new MedicalCondition([
                'name' => $request->get('name'),
                'code' => $request->get('code'),
                'category_id' => $request->get('category_id'),
                'description' => $request->get('description'),
            ]);
            $medicalcondition->save();
            Toastr::success(translate('Medical condition saved successfully!'));
            return redirect()->route('admin.medical_condition.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }

    public function show($id)
    {
        $medicalcondition = MedicalCondition::with('category')->findOrFail($id);
        return view('admin-views.medical_condition.show', compact('medicalcondition'));
    }

    public function edit($id)
    {
        $medicalcondition = MedicalCondition::findOrFail($id);
        $categories = ConditionCategory::all();
        return view('admin-views.medical_condition.edit', compact('medicalcondition', 'categories'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:medical_conditions,name,' . $id,
            'code' => 'nullable|string|max:255|unique:medical_conditions,code,' . $id,
            'category_id' => 'required|exists:condition_categories,id',
            'description' => 'nullable|string',
        ]);

        try {
            $medicalcondition = MedicalCondition::findOrFail($id);
            $medicalcondition->name = $request->get('name');
            $medicalcondition->code = $request->get('code');
            $medicalcondition->category_id = $request->get('category_id');
            $medicalcondition->description = $request->get('description');
            $medicalcondition->save();

            Toastr::success(translate('Medical condition updated successfully!'));
            return redirect()->route('admin.medical_condition.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            $medicalcondition = MedicalCondition::findOrFail($id);
            $medicalcondition->delete();
            Toastr::success(translate('Medical condition deleted successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }
}
