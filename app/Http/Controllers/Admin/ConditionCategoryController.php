<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\ConditionCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;

class ConditionCategoryController extends Controller
{
    function __construct(
        private ConditionCategory $conditionCategory,
    ) {
        $this->middleware('checkAdminPermission:condition_category.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:condition_category.add-new,index')->only(['index']);
    }

    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        return view('admin-views.condition_category.index', compact('roles'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->conditionCategory->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('type', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->conditionCategory->latest();
        }
        $categories = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.condition_category.list', compact('categories', 'search'));
    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.condition_category.create', compact('permission'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:condition_categories',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = new ConditionCategory([
                'name' => $request->get('name'),
                'type' => $request->get('type'),
                'description' => $request->get('description'),
            ]);
            $category->save();
            Toastr::success(translate('Category saved successfully!'));
            return redirect()->route('admin.condition_category.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }

    public function show($id)
    {
        $category = ConditionCategory::with('conditions')->findOrFail($id);
        return view('admin-views.condition_category.show', compact('category'));
    }

    public function edit($id)
    {
        $category = ConditionCategory::findOrFail($id);
        return view('admin-views.condition_category.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:condition_categories,name,' . $id,
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $category = ConditionCategory::findOrFail($id);
            $category->name = $request->get('name');
            $category->type = $request->get('type');
            $category->description = $request->get('description');
            $category->save();

            Toastr::success(translate('Category updated successfully!'));
            return redirect()->route('admin.condition_category.list');
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }

    public function destroy($id)
    {
        try {
            $category = ConditionCategory::findOrFail($id);
            $category->delete();
            Toastr::success(translate('Category deleted successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::error(translate($e->getMessage()));
            return back();
        }
    }
}
