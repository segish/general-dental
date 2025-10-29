<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class SupplierController extends Controller
{
    function __construct(
        private Supplier $supplier
    ) {
        $this->middleware('checkAdminPermission:supplier.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:supplier.add-new,index')->only(['index']);
    }

    public function index()
    {
        return view('admin-views.supplier.index');
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->supplier->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('contact_person', 'like', "%{$value}%")
                        ->orWhere('phone', 'like', "%{$value}%")
                        ->orWhere('email', 'like', "%{$value}%")
                        ->orWhere('address', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->supplier->latest();
        }
        $suppliers = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.supplier.list', compact('suppliers', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            Supplier::create($request->all());
            Toastr::success('Supplier added successfully!');
            return redirect()->route('admin.supplier.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        return view('admin-views.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->update($request->all());
            Toastr::success('Supplier updated successfully!');
            return redirect()->route('admin.supplier.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->delete();
            Toastr::success('Supplier deleted successfully!');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
        }
        return back();
    }
}
