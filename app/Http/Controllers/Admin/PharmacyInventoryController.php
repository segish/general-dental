<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{MedicineCategory, Medicine, PharmacyInventory, Supplier, Product};
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class PharmacyInventoryController extends Controller
{
    public function __construct(private PharmacyInventory $inventory)
    {
        $this->middleware('checkAdminPermission:pharmacy_inventory.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:pharmacy_inventory.add-new,index')->only(['index']);
    }

    public function index()
    {
        $inventories = PharmacyInventory::with(['product', 'supplier'])->orderBy(Product::select('product_code')->whereColumn('products.id', 'pharmacy_inventory.product_id'))->get();
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('admin-views.pharmacy-inventory.index', compact('inventories', 'products', 'suppliers'));
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request->input('search');
        $query_param = $request->all();

        $query = $this->inventory
            ->select('pharmacy_inventory.*')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->join('medicines', 'products.medicine_id', '=', 'medicines.id')
            ->with(['product', 'supplier']);

        // Filtering by expiry status
        $query->where(function ($q) use ($request) {
            if ($request->has('expired') && $request['expired'] === 'expired') {
                $q->where('pharmacy_inventory.expiry_date', '<', now());
            } elseif ($request->has('expired') && $request['expired'] === 'soon') {
                $q->whereRaw('pharmacy_inventory.expiry_date <= DATE_ADD(NOW(), INTERVAL products.expiry_alert_days DAY)')
                    ->where('pharmacy_inventory.expiry_date', '>', now());
            }

            if ($request->has('low_stock')) {
                $q->whereColumn('pharmacy_inventory.quantity', '<=', 'products.low_stock_threshold');
            }
        });

        // Search filter
        if ($search) {
            $key = explode(' ', $search);
            $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('pharmacy_inventory.batch_number', 'like', "%{$value}%")
                        ->orWhere('pharmacy_inventory.manufacturer', 'like', "%{$value}%")
                        ->orWhereHas('product', function ($q) use ($value) {
                            $q->where('products.name', 'like', "%{$value}%");
                        })
                        ->orWhereHas('supplier', function ($q) use ($value) {
                            $q->where('suppliers.name', 'like', "%{$value}%");
                        });
                }
            });
            $query_param['search'] = $search;
        }

        // Add ordering
        $query->orderBy('medicines.category_id', 'asc')
            ->orderBy('products.medicine_id', 'asc')
            ->orderBy('pharmacy_inventory.product_id', 'asc')
            ->orderBy('pharmacy_inventory.id', 'asc');

        $inventories = $query->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.pharmacy-inventory.list', compact('inventories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string',
            'barcode' => 'nullable|string|unique:pharmacy_inventory,barcode',
            'quantity' => 'required|integer|min:1',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
            'received_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'manufacturer' => 'nullable|string'
        ]);

        try {
            PharmacyInventory::create($request->all());
            Toastr::success('Inventory item added successfully!');
            return redirect()->route('admin.pharmacy_inventory.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function edit($id)
    {
        $inventory = PharmacyInventory::with(['product', 'supplier'])->findOrFail($id);
        $products = Product::all();
        $suppliers = Supplier::all();
        return view('admin-views.pharmacy-inventory.edit', compact('inventory', 'products', 'suppliers'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'batch_number' => 'required|string',
            'barcode' => 'nullable|string|unique:pharmacy_inventory,barcode,' . $id,
            'quantity' => 'required|integer|min:1',
            'buying_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'expiry_date' => 'required|date|after:today',
            'received_date' => 'nullable|date',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'manufacturer' => 'nullable|string'
        ]);

        try {
            $inventory = PharmacyInventory::findOrFail($id);
            $inventory->update($request->all());
            Toastr::success('Inventory item updated successfully!');
            return redirect()->route('admin.pharmacy_inventory.list');
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            PharmacyInventory::findOrFail($id)->delete();
            Toastr::success('Inventory item deleted successfully!');
            return back();
        } catch (\Exception $e) {
            Toastr::error($e->getMessage());
            return back();
        }
    }

    public function lowStock()
    {
        $inventories = PharmacyInventory::with(['product', 'supplier'])
            ->whereHas('product', function ($query) {
                $query->whereColumn('quantity', '<=', 'low_stock_threshold');
            })
            ->latest()
            ->paginate(Helpers::pagination_limit());
        return view('admin-views.pharmacy-inventory.low-stock', compact('inventories'));
    }

    public function expiringSoon()
    {
        $inventories = PharmacyInventory::with(['product', 'supplier'])
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>', now())
            ->latest()
            ->paginate(Helpers::pagination_limit());
        return view('admin-views.pharmacy-inventory.expiring-soon', compact('inventories'));
    }
}
