<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssessmentCategory;
use App\Models\Unit;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class AssessmentCategoryController extends Controller
{
    public function __construct(private AssessmentCategory $assessmentCategory)
    {
        $this->middleware('checkAdminPermission:assessment-categories.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:assessment-categories.add-new,index')->only(['index']);
    }

    public function index()
    {
        $categories = AssessmentCategory::with('unit')->get();
        $units = Unit::all();
        return view('admin-views.assessment-category.index', compact('categories', 'units'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->assessmentCategory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('category_type', 'like', "%{$value}%")
                        ->orWhereHas('unit', function ($q) use ($value) {
                            $q->where('name', 'like', "%{$value}%");
                        });
                }
            })->with('unit')->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->assessmentCategory->with('unit')->latest();
        }
        $categories = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.assessment-category.list', compact('categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_type' => 'required|in:Vital Sign,Physical Tests,Labour Followup',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        try {
            AssessmentCategory::create($request->all());
            Toastr::success('Assessment category added successfully!');
            return redirect()->route('admin.assessment-categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $category = AssessmentCategory::with('unit')->findOrFail($id);
        $units = Unit::all();
        return view('admin-views.assessment-category.edit', compact('category', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'category_type' => 'required|in:Vital Sign,Physical Tests,Labour Followup',
            'unit_id' => 'nullable|exists:units,id',
        ]);

        try {
            $category = AssessmentCategory::findOrFail($id);
            $category->update($request->all());
            Toastr::success('Assessment category updated successfully!');
            return redirect()->route('admin.assessment-categories.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = AssessmentCategory::findOrFail($id);
            $category->delete();
            Toastr::success('Assessment category deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}
