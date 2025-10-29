<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class ServiceCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('checkAdminPermission:service_category.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:service_category.add-new,index')->only(['index']);
    }

    public function index(): Factory|View|Application
    {
        return view('admin-views.service-categories.index');
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request->get('search');

        if ($search) {
            $key = explode(' ', $search);
            $query = ServiceCategory::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $search];
        } else {
            $query = ServiceCategory::latest();
        }

        $categories = $query->paginate(10)->appends($query_param);
        return view('admin-views.service-categories.list', compact('categories', 'search'));
    }

    public function create(): View
    {
        return view('admin-views.service-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_categories',
            'description' => 'nullable|string',
            'service_type.*' => 'nullable|in:prescription,medical record,billing service,diagnosis,lab test,radiology,vital sign,pregnancy,delivery summary,newborn,discharge,pregnancy history,Labour Followup',
        ]);

        try {
            ServiceCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'service_type' => $request->service_type ? implode(',', $request->service_type) : null,
            ]);
            Toastr::success('Service category created successfully!');
            return redirect()->route('admin.service_category.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function edit($id): View
    {
        $serviceCategory = ServiceCategory::findOrFail($id);
        return view('admin-views.service-categories.edit', compact('serviceCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:service_categories,name,' . $id,
            'description' => 'nullable|string',
            'service_type.*' => 'nullable|in:prescription,medical record,billing service,diagnosis,lab test,radiology,vital sign,pregnancy,delivery summary,newborn,discharge,pregnancy history,Labour Followup',
        ]);

        try {
            $serviceCategory = ServiceCategory::findOrFail(id: $id);
            $serviceCategory->update([
                'name' => $request->name,
                'description' => $request->description,
                'service_type' => $request->service_type, // array
            ]);
            Toastr::success('Service category updated successfully!');
            return redirect()->route('admin.service_category.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            ServiceCategory::findOrFail($id)->delete();
            Toastr::success('Service category deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}
