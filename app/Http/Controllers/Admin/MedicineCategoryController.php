<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\CentralLogics\Helpers;
use App\Models\Medicine;

class MedicineCategoryController extends Controller
{
    public function __construct(private MedicineCategory $medicineCategory)
    {
        $this->middleware('checkAdminPermission:medicine_categories.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medicine_categories.add-new,index')->only(['index']);
    }

    public function index()
    {
        $categories = MedicineCategory::all();
        return view('admin-views.medicine-category.index', compact('categories'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->medicineCategory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->medicineCategory->latest();
        }
        $query = $query
            ->orderBy('id', 'asc');
        $categories = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.medicine-category.list', compact('categories', 'search'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:medicine_categories,name',
            'description' => 'nullable|string',
        ]);

        try {
            MedicineCategory::create($request->all());
            Toastr::success('Medicine Category added successfully!');
            return redirect()->route('admin.medicine_categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $category = MedicineCategory::find($id);


        return view('admin-views.medicine-category.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:medicine_categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        try {
            $category = MedicineCategory::findOrFail($id);
            $category->update($request->all());
            Toastr::success('Medicine Category updated successfully!');
            return redirect()->route('admin.medicine_categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            MedicineCategory::findOrFail($id)->delete();
            Toastr::success('Medicine Category deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }
}
