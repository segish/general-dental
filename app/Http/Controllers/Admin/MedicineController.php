<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MedicineCategory;
use App\Models\Medicine;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;


class MedicineController extends Controller
{
    public function __construct(private Medicine $medicine)
    {
        $this->middleware('checkAdminPermission:medicines.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medicines.add-new,index')->only(['index']);
    }

    public function index()
    {
        $medicines = Medicine::with('category')->get();
        $categories = MedicineCategory::all();
        return view('admin-views.medicine.index', compact('medicines', 'categories'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->medicine->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('description', 'like', "%{$value}%")
                        ->orWhereHas('category', function ($q) use ($value) {
                            $q->where('name', 'like', "%{$value}%");
                        });
                }
            })->with('category')->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->medicine->with('category');
        }

        $query = $query
            ->orderBy('category_id', 'asc')
            ->orderBy('id', 'asc');

        $medicines = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.medicine.list', compact('medicines', 'search'));
    }

    public function quick_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:medicines,name',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:medicine_categories,id',
        ]);

        try {
            $medicine = Medicine::create($request->all());
            Toastr::success('Medicine added successfully!');
            return response()->json([
                'success' => true,
                'medicine' => $medicine->load('category')
            ]);
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:medicines,name',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:medicine_categories,id',
        ]);

        try {
            Medicine::create($request->all());
            Toastr::success('Medicine added successfully!');
            return redirect()->route('admin.medicines.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $medicine = Medicine::with('category')->findOrFail($id);
        $categories = MedicineCategory::all();
        return view('admin-views.medicine.edit', compact('medicine', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:medicines,name,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:medicine_categories,id',
        ]);

        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->update($request->all());
            Toastr::success('Medicine updated successfully!');
            return redirect()->route('admin.medicines.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);

            // Check if medicine has any associated products
            if ($medicine->products()->exists()) {
                Toastr::error('Cannot delete medicine with associated products!');
                return back();
            }

            $medicine->delete();
            Toastr::success('Medicine deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function products($id)
    {
        $medicine = Medicine::with(['products' => function ($query) {
            $query->with(['unit', 'pharmacyInventories']);
        }])->findOrFail($id);

        return view('admin-views.medicine.products', compact('medicine'));
    }

    public function toggleStatus($id)
    {
        try {
            $medicine = Medicine::findOrFail($id);
            $medicine->status = $medicine->status === 'active' ? 'inactive' : 'active';
            $medicine->save();

            Toastr::success('Medicine status updated successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}
