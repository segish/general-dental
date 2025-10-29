<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Medicine;
use App\Models\Unit;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;

class ProductController extends Controller
{
    public function __construct(private Product $product)
    {
        $this->middleware('checkAdminPermission:products.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:products.add-new,index')->only(['index']);
    }

    public function index()
    {
        $products = Product::with(['medicine', 'unit'])->get();
        $medicines = Medicine::all();
        $units = Unit::all();
        return view('admin-views.product.index', compact('products', 'medicines', 'units'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request->input('search');

        $query = $this->product
            ->select('products.*')
            ->join('medicines', 'products.medicine_id', '=', 'medicines.id')
            ->with(['medicine', 'unit']);

        if ($search) {
            $key = explode(' ', $search);
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('products.name', 'like', "%{$value}%")
                        ->orWhere('products.product_code', 'like', "%{$value}%")
                        ->orWhereHas('medicine', function ($q) use ($value) {
                            $q->where('medicines.name', 'like', "%{$value}%");
                        });
                }
            });
            $query_param = ['search' => $search];
        }

        $query = $query->orderBy('medicines.category_id', 'asc')
            ->orderBy('products.medicine_id', 'asc')
            ->orderBy('products.id', 'asc');

        $products = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.product.list', compact('products', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'name' => 'required|string',
            'product_code' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_id' => 'nullable|exists:units,id',
            'tax' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'expiry_alert_days' => 'nullable|integer|min:1'
        ]);

        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = Helpers::upload('products/', 'png', $request->file('image'));
            }

            Product::create($data);
            Toastr::success('Product added successfully!');
            return redirect()->route('admin.products.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $product = Product::with(['medicine', 'unit'])->findOrFail($id);
        $medicines = Medicine::all();
        $units = Unit::all();
        return view('admin-views.product.edit', compact('product', 'medicines', 'units'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'name' => 'required|string',
            'product_code' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'unit_id' => 'nullable|exists:units,id',
            'tax' => 'nullable|numeric|min:0|max:100',
            'discount' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:fixed,percentage',
            'low_stock_threshold' => 'nullable|integer|min:1',
            'expiry_alert_days' => 'nullable|integer|min:1'
        ]);

        try {
            $product = Product::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = Helpers::upload('products/', 'png', $request->file('image'));
                Helpers::delete('products/' . $product->image);
            }

            $product->update($data);
            Toastr::success('Product updated successfully!');
            return redirect()->route('admin.products.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            if ($product->image) {
                Helpers::delete('products/' . $product->image);
            }
            $product->delete();
            Toastr::success('Product deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }
}
