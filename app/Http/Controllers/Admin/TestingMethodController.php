<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestingMethod;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;

class TestingMethodController extends Controller
{
    public function index()
    {
        return view('admin-views.testing-method.index');
    }

    public function list(Request $request)
    {
        $search = $request->get('search');
        $query = TestingMethod::query();

        if ($search) {
            $query->where('method_code', 'like', "%{$search}%")
                ->orWhere('method_description', 'like', "%{$search}%");
        }

        $methods = $query->paginate(Helpers::pagination_limit());

        return view('admin-views.testing-method.list', compact('methods', 'search'));
    }

    public function create()
    {
        return view('admin-views.testing-method.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'method_code' => 'required|string|max:255|unique:testing_methods,method_code',
            'method_description' => 'required|string',
        ]);

        try {
            TestingMethod::create($request->all());
            Toastr::success('Testing Method created successfully!');
            return redirect()->route('admin.testing-method.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $method = TestingMethod::findOrFail($id);
        return view('admin-views.testing-method.edit', compact('method'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'method_code' => "required|string|max:255|unique:testing_methods,method_code,{$id}",
            'method_description' => 'required|string',
        ]);

        try {
            $method = TestingMethod::findOrFail($id);
            $method->update($request->all());
            Toastr::success('Testing Method updated successfully!');
            return redirect()->route('admin.testing-method.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $method = TestingMethod::findOrFail($id);
            $method->delete();
            Toastr::success('Testing Method deleted successfully!');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
        return back();
    }
}
