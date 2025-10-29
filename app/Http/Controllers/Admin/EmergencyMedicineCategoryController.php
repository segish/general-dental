<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmergencyMedicineCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\CentralLogics\Helpers;

class EmergencyMedicineCategoryController extends Controller
{
    public function __construct(private EmergencyMedicineCategory $emergencyMedicineCategory)
    {
        $this->middleware('checkAdminPermission:emergency_medicine_categories.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:emergency_medicine_categories.add-new,index')->only(['index']);
    }

    public function index()
    {
        $categories = EmergencyMedicineCategory::all();
        return view('admin-views.emergency-medicine-category.index', compact('categories'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->emergencyMedicineCategory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->emergencyMedicineCategory->latest();
        }
        $categories = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.emergency-medicine-category.list', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:emergency_medicine_categories,name',
            'description' => 'nullable|string',
        ]);

        try {
            EmergencyMedicineCategory::create($request->all());
            Toastr::success('Emergency Medicine Category added successfully!');
            return redirect()->route('admin.emergency_medicine_categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $category = EmergencyMedicineCategory::findOrFail($id);
        return view('admin-views.emergency-medicine-category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:emergency_medicine_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $category = EmergencyMedicineCategory::findOrFail($id);
            $category->update($request->all());
            Toastr::success('Emergency Medicine Category updated successfully!');
            return redirect()->route('admin.emergency_medicine_categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = EmergencyMedicineCategory::findOrFail($id);

            // Check if category has any associated medicines
            if ($category->medicines()->exists()) {
                Toastr::error('Cannot delete category with associated medicines!');
                return back();
            }

            $category->delete();
            Toastr::success('Emergency Medicine Category deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}
