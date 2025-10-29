<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Mail\QuantityLeftNotification;
use App\Mail\NotifyAdmin;
use App\Mail\TestEmailSender;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\MedicineCategory;
use App\Models\Product;
use App\Models\Admin;
use App\Models\ShopProduct;
use App\Models\Customer;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use function App\CentralLogics\translate;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\Bincard;
use App\Models\Bank;
use App\Models\Medicine;
use App\Models\PharmacyInventory;
use App\Models\PharmacyInventoryLog;
use App\Models\Prescription;
use PayPal\Api\Amount;

class POSController extends Controller
{
    public function __construct(
        private Order $order,
        private OrderDetail $order_detail,
        private Medicine $medicine,
        private Product $product,
        private Customer $user
    ) {

        $this->middleware('checkAdminPermission:pos.orders,order_list')->only(['order_list']);
        $this->middleware('checkAdminPermission:pos.index,index')->only(['index']);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $category = $request->query('category_id', 0);
        $medicine = $request->query('medicine_id', 0);

        $categories = MedicineCategory::orderBy('id', 'asc')->get();
        $medicines = Medicine::orderBy('category_id', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $keyword = $request->keyword;
        $key = $keyword ? explode(' ', $keyword) : [];

        $query = $this->product
            ->select('products.*')
            ->join('medicines', 'products.medicine_id', '=', 'medicines.id')
            ->whereHas('pharmacyInventories', function ($query) {
                $query->where('quantity', '>', 0);
            })
            ->when($category != 0, function ($query) use ($category) {
                $query->where('medicines.category_id', $category);
            })
            ->when($medicine != 0, function ($query) use ($medicine) {
                $query->where('products.medicine_id', $medicine);
            })
            ->when($keyword, function ($query) use ($key) {
                $query->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('products.name', 'like', "%{$value}%")
                            ->orWhere('products.product_code', 'like', "%{$value}%");
                    }
                });
            })
            ->with('pharmacyInventories')
            ->orderBy('medicines.category_id', 'asc')
            ->orderBy('products.medicine_id', 'asc')
            ->orderBy('products.id', 'asc')
            ->orderBy('products.product_code', 'asc');

        $products = $query->paginate(Helpers::getPagination());

        $users = $this->user->all();
        $prescriptions = Prescription::with('details.medicine.products.pharmacyInventories')
            ->where('created_at', '>=', now()->subMonth())
            ->latest()
            ->get();

        return view('admin-views.pos.index', compact(
            'categories',
            'medicines',
            'medicine',
            'keyword',
            'users',
            'category',
            'prescriptions',
            'products'
        ));
    }



    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function details($id): View|Factory|RedirectResponse|Application
    {
        $order = $this->order->with('details.inventory.product.medicine', 'patient', 'details.inventory.product.unit')->where(['id' => $id])->first();
        if (isset($order)) {
            return view('admin-views.order.order-view', compact('order'));
        } else {
            Toastr::info(translate('No more orders!'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function quick_view(Request $request): JsonResponse
    {
        $product = $this->product->findOrFail($request->product_id);

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos._quick-view-data', compact('product'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return float[]|int[]
     */
    public function variant_price(Request $request): array
    {
        $medicine = $this->medicine->find($request->id);
        $str = '';
        $price = 0;
        $stock = 0;

        foreach (json_decode($medicine->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-' . str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($medicine->variations));
            for ($i = 0; $i < $count; $i++) {
                if (json_decode($medicine->variations)[$i]->type == $str) {
                    $price = json_decode($medicine->variations)[$i]->price - Helpers::discount_calculate($medicine, $medicine->price);
                    $stock = json_decode($medicine->variations)[$i]->stock;
                }
            }
        } else {
            $price = $medicine->price - Helpers::discount_calculate($medicine, $medicine->price);
            $stock = $medicine->total_stock;
        }

        return array('price' => ($price * $request->quantity), 'stock' => $stock);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */

    public function get_customers(Request $request): \Illuminate\Http\JsonResponse
    {
        $key = explode(' ', $request['q']);
        $data = DB::table('customers')
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('fullname', 'like', "%{$value}%")
                        ->orWhere('phone_number', 'like', "%{$value}%");
                }
            })
            ->whereNotNull('fullname')
            ->limit(8)
            ->get([
                DB::raw('id, CONCAT(fullname,
                IF(phone_number IS NOT NULL AND phone_number != "", CONCAT(" (", phone_number, ")"), "")
            ) as text')
            ]);


        $data[] = (object)['id' => false, 'text' => translate('walk_in_customer')];

        return response()->json($data);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_tax(Request $request): RedirectResponse
    {
        if ($request->tax < 0) {
            Toastr::error(translate('Tax_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->tax > 100) {
            Toastr::error(translate('Tax_can_not_be_more_than_100_percent'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['tax'] = $request->tax;
        $request->session()->put('cart', $cart);
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_discount(Request $request): RedirectResponse
    {
        $subtotal = session()->get('subtotal');
        $total = session()->get('total');

        if ($request->type == 'percent' && $request->discount < 0) {
            Toastr::error(translate('Extra_discount_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->type == 'amount' && $request->discount < 0) {
            Toastr::error(translate('Extra_discount_can_not_be_less_than_0'));
            return back();
        } elseif ($request->type == 'percent' && $request->discount > 100) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_100_percent'));
            return back();
        } elseif ($request->type == 'amount' && $request->discount > $total) {
            Toastr::error(translate('Extra_discount_can_not_be_more_than_total_price'));
            return back();
        } elseif ($request->type == 'percent' && ($request->session()->get('cart')) == null) {
            Toastr::error(translate('cart_is_empty'));
            return back();
        } elseif ($request->type == 'percent' && $request->discount > 0) {
            $extra_discount = ($subtotal * $request->discount) / 100;
            if ($extra_discount >= $total) {
                Toastr::error(translate('Extra_discount_can_not_be_more_or_equal_than_total_price'));
                return back();
            }
        }


        $cart = $request->session()->get('cart', collect([]));
        $cart['extra_discount'] = $request->discount;
        $cart['extra_discount_type'] = $request->type;
        $request->session()->put('cart', $cart);

        Toastr::success(translate('Discount_applied'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function updateQuantity(Request $request): JsonResponse
    {
        $cart = $request->session()->get('cart', collect([]));
        $cart = $cart->map(function ($object, $key) use ($request) {
            if ($key == $request->key) {
                $object['quantity'] = $request->quantity;
            }
            return $object;
        });
        $request->session()->put('cart', $cart);
        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addToCart(Request $request): JsonResponse
    {

        $product = $this->product->find($request->id);

        $data = array();
        $data['id'] = $product->id;
        $price = 0;
        if ($request->session()->has('cart')) {
            if (count($request->session()->get('cart')) > 0) {
                foreach ($request->session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $request['id'] && $cartItem['batch'] == $request['batch']) {
                        return response()->json([
                            'data' => 1
                        ]);
                    }
                }
            }
        }

        if (!$request['batch']) {
            return response()->json(
                'Batch Number Not Selected',
                400
            );
        }

        $batch = $product->pharmacyInventories->firstWhere('id', $request['batch']);
        $price = $batch ? $batch->selling_price : 0;


        $data['quantity'] = $request['quantity'];
        $data['price'] = $request['unit_price'] ?? $price;
        $data['name'] = $product->name;
        $data['discount'] = Helpers::discount_calculate($product, $request['unit_price'] ?? $price);
        $data['image'] = $product->image;
        $data['batch'] = $request->batch;

        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->push($data);
        } else {
            $cart = collect([$data]);
            $request->session()->put('cart', $cart);
        }
        $request->session()->put('withTax', 1);
        return response()->json([
            'data' => $data
        ]);
    }

    public function update_with_tax(Request $request): RedirectResponse
    {
        $request->session()->put('withTax', $request->with_tax);
        Toastr::success(translate('With Tax Updated'));
        return back();
    }

    public function addPrescriptionToCart(Request $request): JsonResponse
    {
        $noBatch = false;
        foreach ($request->items as $item) {
            $product = $this->product->find($item['product_id']);

            $data = array();
            $data['id'] = $product->id;
            $price = 0;

            $isDuplicate = false;

            if ($request->session()->has('cart')) {
                foreach ($request->session()->get('cart') as $key => $cartItem) {
                    if (
                        is_array($cartItem) &&
                        $cartItem['id'] == $item['product_id'] &&
                        $cartItem['batch'] == $item['batch']
                    ) {
                        $isDuplicate = true;
                        break;
                    }
                }
            }

            if ($isDuplicate) {
                continue; // Skip to the next item in the $request->items loop
            }

            if (!$item['batch']) {
                $noBatch = true;
                continue;
            }

            $batch = $product->pharmacyInventories->firstWhere('id', $item['batch']);
            $price = $batch ? $batch->selling_price : 0;


            $data['quantity'] = $item['quantity'];
            $data['price'] = $item['unit_price'] ?? $price;
            $data['name'] = $product->name;
            $data['discount'] = Helpers::discount_calculate($product, $item['unit_price'] ?? $price);
            $data['image'] = $product->image;
            $data['batch'] = $item['batch'];

            if ($request->session()->has('cart')) {
                $cart = $request->session()->get('cart', collect([]));
                $cart->push($data);
            } else {
                $cart = collect([$data]);
                $request->session()->put('cart', $cart);
            }
        }
        $request->session()->put('buyer_type', 'prescription');
        $request->session()->put('customer_id', $request->customer_id);

        if ($noBatch) {
            return response()->json([
                'data' => 1
            ]);
        }
        $request->session()->put('withTax', 1);

        return response()->json([
            'data' => $request->session()->get('cart', collect([]))
        ]);
    }
    /**
     * @return Application|Factory|View
     */
    public function cart_items(): Factory|View|Application
    {
        return view('admin-views.pos._cart');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function emptyCart(Request $request): JsonResponse
    {
        session()->forget('cart');
        Session::forget('customer_id');
        Session::forget('branch_id');
        Session::forget('withTax');
        Session::forget('buyer_type');

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFromCart(Request $request): JsonResponse
    {
        if ($request->session()->has('cart')) {
            $cart = $request->session()->get('cart', collect([]));
            $cart->forget($request->key);
            $request->session()->put('cart', $cart);
        }

        return response()->json([], 200);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function order_list(Request $request): Factory|View|Application
    {
        $search = $request->search;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $payment_status = $request->payment_status;
        $payment_method = $request->payment_method;
        $buyer_type = $request->buyer_type;
        $sale_id = $request->sale_id;

        $query_param = compact(
            'search',
            'start_date',
            'end_date',
            'payment_status',
            'payment_method',
            'buyer_type',
            'sale_id'
        );


        $query = $this->order->with(['customer', 'user', 'details', 'details.inventory', 'details.inventory.product.medicine']) // assuming 'user' is the admin
            ->when(!auth('admin')->user()->hasRole('Super Admin') &&
                !auth('admin')->user()->can('pos.view_all_orders'), function ($q) {
                $q->where('user_id', auth('admin')->id());
            })
            ->when($start_date && $end_date, fn($q) => $q->whereBetween('created_at', [$start_date, $end_date]))
            ->when($payment_status, fn($q) => $q->where('payment_status', $payment_status))
            ->when($sale_id, fn($q) => $q->where('user_id', $sale_id))
            ->when($payment_method, fn($q) => $q->where('payment_method', $payment_method))
            ->when($buyer_type, fn($q) => $q->where('buyer_type', $buyer_type));

        if ($search) {
            $keywords = explode(' ', $search);
            $query->where(function ($q) use ($keywords) {
                foreach ($keywords as $word) {
                    $q->orWhere('invoice_no', 'like', "%$word%")
                        ->orWhere('transaction_reference', 'like', "%$word%")
                        ->orWhere('payment_status', 'like', "%$word%")
                        ->orWhere('payment_method', 'like', "%$word%")
                        ->orWhereHas('customer', function ($q2) use ($word) {
                            $q2->whereRaw("CONCAT(f_name, ' ', l_name) LIKE ?", ["%{$word}%"]);
                        });
                }
            });
        }

        $orders = $query->orderByDesc('id')
            ->paginate(Helpers::getPagination())
            ->appends($query_param);

        $sales = Admin::all(); // If you want to filter only cashier/admins, add a scope

        return view('admin-views.pos.order.list', compact(
            'orders',
            'sales',
            'sale_id',
            'payment_method',
            'payment_status',
            'buyer_type',
            'search',
            'start_date',
            'end_date'
        ));
    }

    /**
     * @param $id
     * @return Application|Factory|View|RedirectResponse
     */
    public function order_details($id): View|Factory|RedirectResponse|Application
    {
        $order = $this->order->with('details', 'patient')->where(['id' => $id])->first();

        $delivery_man = $this->delivery_man
            ->where(function ($query) use ($order) {
                $query->where('branch_id', $order->branch_id)
                    ->orWhere('branch_id', 0);
            })
            ->get();

        if (isset($order)) {
            return view('admin-views.pos.order.order-view', compact('order', 'delivery_man'));
        } else {
            Toastr::info(translate('No more orders!'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function place_order(Request $request)
    {
        if (!$request->session()->has('cart') || count($request->session()->get('cart')) < 1) {
            Toastr::error(translate('cart_empty_warning'));
            return back();
        }

        $cartItems = $request->session()->get('cart');
        DB::beginTransaction();

        try {
            $order = $this->order;
            if (session('buyer_type') == 'prescription') {
                $order->patient_id = session('customer_id', null);
            } else {
                $order->customer_id = session('customer_id', null);
            }
            $order->buyer_type = session('buyer_type') ?? 'walk-in';
            $order->user_id = auth('admin')->id();
            $order->payment_method = $request->type;
            $order->transaction_reference = $request->transaction_reference;
            $order->note = $request->note;

            $totalTax = 0;
            $totalMedicinePrice = 0;
            $totalOriginalPrice = 0;
            $medicine_total_discount = 0;
            $orderDetails = [];

            foreach ($cartItems as $item) {
                if (is_array($item)) {
                    $product = $this->product->findOrFail($item['id']);
                    $inventory = PharmacyInventory::findOrFail($item['batch']);

                    if ($inventory->quantity < $item['quantity']) {
                        Toastr::error($product->name . ' ' . translate('is out of stock'));
                        DB::rollBack();
                        return back();
                    }

                    $itemSubtotal = $item['price'] * $item['quantity'];
                    $itemDiscount = $item['discount'] * $item['quantity'];

                    $taxAmount = Helpers::tax_calculate2($product, $item['price']);
                    $discountAmount = Helpers::discount_calculate($product, $item['price']);

                    $orderDetails[] = [
                        'inventory_id' => $item['batch'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                        'tax_amount' => $taxAmount * $item['quantity'],
                        'unit' => $product->unit->code,
                        'discount_on_product' => $discountAmount * $item['quantity'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $totalTax += $taxAmount * $item['quantity'];
                    $totalMedicinePrice += ($itemSubtotal - $itemDiscount);
                    $medicine_total_discount += $itemDiscount;
                    $totalOriginalPrice += $itemSubtotal;

                    $inventory->decrement('quantity', $item['quantity']);
                    PharmacyInventoryLog::create([
                        'product_id' => $item['id'],
                        'inventory_id' => $item['batch'],
                        'seller_id' => auth('admin')->id(),
                        'buyer_id' => session('buyer_type') == 'prescription' ? null : session('customer_id', null),
                        'buyer_type' => session('buyer_type') ?? 'walk-in',
                        'action' => 'out',
                        'quantity' => $item['quantity'],
                        'balance_after' => $inventory->quantity,
                    ]);
                }
            }

            $extraDiscount = 0;
            if (isset($cartItems['extra_discount'])) {
                $extraDiscount = $cartItems['extra_discount_type'] === 'percent' && $cartItems['extra_discount'] > 0
                    ? (($totalOriginalPrice * $cartItems['extra_discount']) / 100)
                    : $cartItems['extra_discount'];

                $totalMedicinePrice -= $extraDiscount;
            }
            if ($request->amount_recieved > $totalMedicinePrice + $totalTax) {
                Toastr::error(translate('Amount recieved can not be greater than the total amount'));
                DB::rollBack();
                return back();
            }
            if (round($request->amount_recieved, 2) == round($totalMedicinePrice + $totalTax, 2)) {
                $order->payment_status = 'paid';
            } elseif (round($request->amount_recieved, 2) < round($totalMedicinePrice + $totalTax, 2) && round($request->amount_recieved, 2) > 0) {
                $order->payment_status = 'partial';
            } else {
                $order->payment_status = 'unpaid';
            }
            $order->extra_discount = $extraDiscount;
            $order->total_tax_amount = $totalTax;
            $order->subtotal = $totalMedicinePrice;
            $order->total = $totalMedicinePrice + $totalTax;
            $order->amount_paid = $request->amount_recieved;
            $order->medicine_total_discount = $medicine_total_discount;
            $order->invoice_no = $this->generatePharmacyInvoiceId();
            $order->save();

            foreach ($orderDetails as &$detail) {
                $detail['order_id'] = $order->id;
            }
            $this->order_detail->insert($orderDetails);

            session()->forget(['cart', 'customer_id', 'branch_id', 'withTax', 'buyer_type']);
            session(['last_order' => $order->id]);

            DB::commit();

            Toastr::success(translate('order_placed_successfully'));
            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed:', ['error' => $e->getMessage()]);
            Toastr::warning(translate('failed_to_place_order'));
            return back();
        }
    }

    private function generatePharmacyInvoiceId(): string
    {
        $date = now()->format('ymd'); // e.g., 250407
        $micro = now()->format('u');  // full microsecond, e.g., 345612
        $milliseconds = substr($micro, 0, 3); // just the first 3 digits

        $invoiceId = 'PHINV-' . $date . $milliseconds;

        // Add randomness or counter to ensure uniqueness if needed
        $random = str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT);
        $invoiceId .= $random;

        // Check uniqueness in the DB
        while (
            DB::table('orders')
            ->where('invoice_no', $invoiceId)
            ->exists()
        ) {
            $random = str_pad(mt_rand(0, 99), 2, '0', STR_PAD_LEFT);
            $invoiceId = 'PHINV-' . $date . $milliseconds . $random;
        }

        return $invoiceId;
    }


    public function generatePdf($id)
    {
        $order = Order::findOrFail($id);

        // Generate the PDF
        $pdf = PDF::loadView('admin-views.pos.invoice', compact('order'))
            ->setPaper('a5', 'portrait');

        // Return the PDF inline (view in browser)
        return $pdf->stream('order.pdf', [
            'Attachment' => false,
            'Content-Disposition' => 'inline; filename="prescription.pdf"',
            'Content-Type' => 'application/pdf',
            'Cache-Control' => 'private, max-age=0, must-revalidate',
            'Pragma' => 'public'
        ]);
    }

    public function downloadPdf($id)
    {
        $order = Order::findOrFail($id);
        // Load the view for the PDF content
        $pdf = PDF::loadView('admin-views.pos.invoice', compact('order'));

        return $pdf->download('order.pdf');
    }
    public function updateAmount(Request $request, Order $order)
    {
        $request->validate([
            'amount_received' => 'required|numeric',
        ]);

        $Amount = $order->amount_paid + $request->amount_received;
        if ($Amount > $order->total) {
            Toastr::error(translate('Amount is more than total amount'));
            return redirect()->back();
        }

        $order->amount_paid += $request->amount_received;

        $order->save();

        if ($order->total <= $order->amount_paid) {
            $order->payment_status = 'paid';
            $order->save();
        } elseif ($order->total > $order->amount_paid && $order->amount_paid > 0) {
            $order->payment_status = 'partial';
            $order->save();
        } else {
            $order->payment_status = 'unpaid';
            $order->save();
        }

        Toastr::success(translate('payment updated successfully'));
        return redirect()->back();
    }

    public function updateInvoice(Request $request, Order $order)
    {

        $order->remark = $request->remark;

        $order->save();


        return redirect()->back()->with('success', 'Invoice Number Saved Successfully.');
    }


    /**
     * @param $id
     * @return JsonResponse
     */
    public function generate_invoice($id): JsonResponse
    {
        $order = $this->order->where('id', $id)->first();

        return response()->json([
            'success' => 1,
            'view' => view('admin-views.pos.order.invoice', compact('order'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store_keys(Request $request): JsonResponse
    {
        session()->put($request['key'], $request['value']);
        if ($request->key == 'customer_id') {
            session()->put('buyer_type', $request->value ? 'registered' : 'walk-in');
        }
        return response()->json('', 200);
    }

    public function customer_store(Request $request): RedirectResponse
    {
        $request->validate([
            'fullname' => 'required',
            'phone_number' => 'nullable',
            'email' => 'nullable|email',
        ]);

        if (isset($request->phone)) {
            $user_phone = $this->user->where('phone_number', $request->phone)->first();
            if (isset($user_phone)) {
                Toastr::error(translate('The phone is already taken'));
                return back();
            }
        }
        if (isset($request->email)) {
            $user_email = $this->user->where('email', $request->email)->first();
            if (isset($user_email)) {
                Toastr::error(translate('The email is already taken'));
                return back();
            }
        }



        $this->user->create([
            'fullname' => $request->f_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
            'password' => bcrypt('password'),
        ]);

        Toastr::success(translate('customer added successfully'));
        return back();
    }

    /**
     * @param Request $request
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_orders(Request $request): StreamedResponse|string
    {
        $query_param = [];
        $search = $request['search'];
        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $query = $this->order->pos()->with(['customer', 'branch'])
            ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                return $query->where('branch_id', $branch_id);
            })
            ->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                return $query->whereDate('created_at', '>=', $start_date)
                    ->whereDate('created_at', '<=', $end_date);
            });
        $query_param = ['branch_id' => $branch_id, 'start_date' => $start_date, 'end_date' => $end_date];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('transaction_reference', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $orders = $query->orderBy('id', 'desc')->get();
        $storage = [];
        foreach ($orders as $order) {
            $storage[] = [
                'Order Id' => $order['id'],
                'Order Date' => date('d M Y', strtotime($order['created_at'])),
                'Customer' => $order->customer ? $order->customer->f_name . ' ' . $order->customer->l_name : 'Walking Customer',
                'Branch' => $order->branch ? $order->branch->name : '',
                'Order Amount' => $order['order_amount'],
                'Order Status' => $order['order_status'],
                'Order Type' => $order['order_type'],
                'Payment Status' => $order['payment_status'],
                'Payment Method' => $order['payment_method'],
            ];
        }
        return (new FastExcel($storage))->download('pos-orders.xlsx');
    }

    public function update_total_tax(Request $request): RedirectResponse
    {
        if ($request->total_tax < 0) {
            Toastr::error(translate('Tax_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->total_tax > 100) {
            Toastr::error(translate('Tax_can_not_be_more_than_100_percent'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['total_tax'] = $request->total_tax;
        $request->session()->put('cart', $cart);
        return back();
    }


    public function update_with_holding_tax(Request $request): RedirectResponse
    {
        if ($request->withHoldingTax < 0) {
            Toastr::error(translate('Tax_can_not_be_less_than_0_percent'));
            return back();
        } elseif ($request->withHoldingTax > 100) {
            Toastr::error(translate('Tax_can_not_be_more_than_100_percent'));
            return back();
        }

        $cart = $request->session()->get('cart', collect([]));
        $cart['withHoldingTax'] = $request->withHoldingTax;
        $request->session()->put('cart', $cart);
        return back();
    }

    public function approve_order(Request $request, $id)
    {
        $request->validate([
            'is_approved' => 'required',
        ]);

        $order = $this->order->with('details')->findOrFail($id);

        foreach ($order->details as $detail) {
            $medicine = Product::find($detail['product_id']);
            $latestBincard = Bincard::where('product_id', $medicine->id)
                ->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->first();

            $previousBalance = $latestBincard ? $latestBincard->balance : 0;

            $newBalance = $previousBalance;
            $newBalance -= $detail['quantity'];

            Bincard::create([
                'product_id' => $detail['product_id'],
                'purchase_invoice_id' => $order->id,
                'purchase_invoice_type' => 'App\Models\Order',
                'status' => 'issued', // received or issued
                'quantity' => $detail['quantity'],
                'received_issued_to_id' => $order->user_id ?? 1,
                'received_issued_to_type' => 'App\Customer',
                'store_shop_id' => $detail['shop_product_id'],
                'store_shop_type' => 'App\Models\ShopProduct',
                'balance' => $newBalance,
            ]);


            $shopProduct = ShopProduct::find($detail['shop_product_id']);
            if ($shopProduct) {
                $availableQuantityInShop = $shopProduct->quantity;
                $requestedQuantity = $detail['quantity'];

                $updatedQuantity = max(0, $availableQuantityInShop - $requestedQuantity);

                $shopProduct->update(['quantity' => $updatedQuantity]);
            }


            $totalStock = max(0, $medicine['total_stock'] - $detail['quantity']);

            $this->medicine->where(['id' => $medicine['id']])->update([
                'total_stock' => $totalStock,
            ]);
        }

        $order->is_approved = $request->is_approved;
        $order->order_status = 'confirmed';

        $order->save();
        return back()->with('success', 'Order approved successfully');
    }

    public function delete($id)
    {
        $order = Order::find($id);

        if ($order) {
            foreach ($order->details as $detail) {
                $detail->delete();
            }
            $order->delete();
            Toastr::success(translate('Order removed!'));
            return back();
        } else {
            Toastr::success(translate('Something Went Wrong!'));
            return back();
        }
    }

    public function revenueReport(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        // Get total bills count
        $total_bills = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Get partial paid amount
        $partial_paid = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'partial')
            ->sum('amount_paid');

        // Get partial unpaid amount
        $partial_paid_total = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'partial')
            ->sum('total');
        $partial_unpaid = $partial_paid_total - $partial_paid;
        // Get total fully paid amount
        $total_paid_fully = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total');
        // Get total fully paid amount
        $total_paid = $partial_paid + $total_paid_fully;
        // Get total unpaid amount
        $total_fully_unpaid = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'unpaid')
            ->sum('total');

        $total_unpaid = $total_fully_unpaid + $partial_unpaid;

        // Get revenue by service type
        $revenueByService = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('orders', 'order_details.order_id', '=', 'orders.id')
            ->select(
                'orders.buyer_type as service_type',
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue'),
                DB::raw('COUNT(DISTINCT orders.id) as order_count')
            )
            ->groupBy('orders.buyer_type')
            ->get();


        // If still empty, try without date filter
        if ($revenueByService->isEmpty()) {
            $revenueByService = OrderDetail::join('orders', 'order_details.order_id', '=', 'orders.id')
                ->select(
                    'orders.buyer_type as service_type',
                    DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue'),
                    DB::raw('COUNT(DISTINCT orders.id) as order_count')
                )
                ->groupBy('orders.buyer_type')
                ->get();
        }

        // Get daily revenue data for chart
        $revenues = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(total_tax_amount) as total_tax'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN amount_paid ELSE 0 END) as total_paid'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN total - amount_paid ELSE 0 END) as outstanding'),
                DB::raw('SUM(total - total_tax_amount - (
                    SELECT SUM(od.quantity * pi.buying_price)
                    FROM order_details od
                    JOIN pharmacy_inventory pi ON od.inventory_id = pi.id
                    WHERE od.order_id = orders.id
                )) as total_profit')
            )
            ->groupBy('date')
            ->get();

        // Prepare chart data
        $chartData = [
            'labels' => $revenues->pluck('date'),
            'total_revenue' => $revenues->pluck('total_revenue'),
            'total_paid' => $revenues->pluck('total_paid'),
            'outstanding' => $revenues->pluck('outstanding'),
        ];

        // Get yearly earnings for the statistics chart
        $earning = [];
        for ($i = 1; $i <= 12; $i++) {
            $earning[$i] = Order::whereYear('created_at', Carbon::now()->year)
                ->whereMonth('created_at', $i)
                // ->where('payment_status', 'paid')
                // ->orhere('payment_status', 'paid')
                ->sum('total');
        }

        return view('admin-views.report.pharmacy-revenue-report', compact(
            'startDate',
            'endDate',
            'total_bills',
            'total_paid',
            'total_unpaid',
            'partial_paid',
            'revenueByService',
            'revenues',
            'chartData',
            'earning'
        ));
    }

    public function earningStatistics(Request $request)
    {
        $type = $request->type;
        $earning = [];
        $earning_label = [];

        switch ($type) {
            case 'yearEarn':
                // Get yearly earnings for current year
                for ($i = 1; $i <= 12; $i++) {
                    $earning[] = Order::whereYear('created_at', Carbon::now()->year)
                        ->whereMonth('created_at', $i)
                        ->sum('total');
                    $earning_label[] = Carbon::create()->month($i)->format('M');
                }
                break;

            case 'MonthEarn':
                // Get monthly earnings for current month
                $daysInMonth = Carbon::now()->daysInMonth;
                for ($i = 1; $i <= $daysInMonth; $i++) {
                    $earning[] = Order::whereYear('created_at', Carbon::now()->year)
                        ->whereMonth('created_at', Carbon::now()->month)
                        ->whereDay('created_at', $i)
                        ->sum('total');
                    $earning_label[] = $i;
                }
                break;

            case 'WeekEarn':
                // Get weekly earnings for current week
                $startOfWeek = Carbon::now()->startOfWeek();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startOfWeek->copy()->addDays($i);
                    $earning[] = Order::whereDate('created_at', $date)
                        ->sum('total');
                    $earning_label[] = $date->format('D');
                }
                break;
        }

        return response()->json([
            'earning' => $earning,
            'earning_label' => $earning_label
        ]);
    }

    public function downloadRevenueExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $revenues = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(total_tax_amount) as total_tax'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN amount_paid ELSE 0 END) as total_paid'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN total - amount_paid ELSE 0 END) as outstanding'),
                DB::raw('SUM(total - total_tax_amount - (
                    SELECT SUM(od.quantity * pi.buying_price)
                    FROM order_details od
                    JOIN pharmacy_inventory pi ON od.inventory_id = pi.id
                    WHERE od.order_id = orders.id
                )) as total_profit')
            )
            ->groupBy('date')
            ->get();

        $data = [];
        foreach ($revenues as $revenue) {
            $data[] = [
                'Date' => $revenue->date,
                'Total Revenue' => number_format($revenue->total_revenue, 2),
                'Total Paid' => number_format($revenue->total_paid, 2),
                'Outstanding' => number_format($revenue->outstanding, 2),
                'Total_Tax' => number_format($revenue->total_tax, 2),
                'Total_Profit' => number_format($revenue->total_profit, 2),
            ];
        }

        // Add summary row
        $data[] = [
            'Date' => 'Total',
            'Total Revenue' => number_format($revenues->sum('total_revenue'), 2),
            'Total Paid' => number_format($revenues->sum('total_paid'), 2),
            'Outstanding' => number_format($revenues->sum('outstanding'), 2),
            'Total Tax' => number_format($revenues->sum('total_tax'), 2),
            'Total Profit' => number_format($revenues->sum('total_profit'), 2),
        ];

        return (new FastExcel($data))->download('revenue-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.xlsx');
    }

    public function downloadRevenuePdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();

        $revenues = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(total_tax_amount) as total_tax'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN amount_paid ELSE 0 END) as total_paid'),
                DB::raw('SUM(CASE WHEN payment_status IN ("unpaid", "partial") THEN total - amount_paid ELSE 0 END) as outstanding'),
                DB::raw('SUM(total - total_tax_amount - (
                    SELECT SUM(od.quantity * pi.buying_price)
                    FROM order_details od
                    JOIN pharmacy_inventory pi ON od.inventory_id = pi.id
                    WHERE od.order_id = orders.id
                )) as total_profit')
            )
            ->groupBy('date')
            ->get();

        // Calculate totals
        $totalRevenue = $revenues->sum('total_revenue');
        $totalPaid = $revenues->sum('total_paid');
        $totalOutstanding = $revenues->sum('outstanding');
        $totalTax = $revenues->sum('total_tax');
        $totalProfit = $revenues->sum('total_profit');

        $pdf = PDF::loadView('admin-views.report.pharmacy-revenue-pdf', compact(
            'revenues',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalPaid',
            'totalOutstanding',
            'totalTax',
            'totalProfit'
        ));

        return $pdf->download('revenue-report-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
    }

    public function getTopSellingProducts(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $limit = $request->limit ?? 10;

        $topSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.id',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();
        Log::error($topSellingProducts);
        return response()->json([
            'success' => 1,
            'data' => $topSellingProducts
        ]);
    }

    public function getLowSellingProducts(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $limit = $request->limit ?? 10;

        $lowSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                'products.id',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'asc')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => 1,
            'data' => $lowSellingProducts
        ]);
    }

    public function productPerformance()
    {
        return view('admin-views.report.product-performance');
    }

    public function downloadProductPerformanceExcel(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $limit = $request->limit ?? 10;

        // Get top selling products
        $topSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();

        // Get low selling products
        $lowSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'asc')
            ->limit($limit)
            ->get();

        $data = [];

        // Add top selling products
        $data[] = ['Top Selling Products'];
        $data[] = ['Product Name', 'Total Quantity', 'Total Revenue'];
        foreach ($topSellingProducts as $product) {
            $data[] = [
                $product->name,
                $product->total_quantity,
                number_format($product->total_revenue, 2)
            ];
        }

        // Add empty row
        $data[] = [];

        // Add low selling products
        $data[] = ['Low Selling Products'];
        $data[] = ['Product Name', 'Total Quantity', 'Total Revenue'];
        foreach ($lowSellingProducts as $product) {
            $data[] = [
                $product->name,
                $product->total_quantity,
                number_format($product->total_revenue, 2)
            ];
        }

        return (new FastExcel($data))->download('product-performance-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.xlsx');
    }

    public function downloadProductPerformancePdf(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->startOfMonth();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->endOfMonth();
        $limit = $request->limit ?? 10;

        // Get top selling products
        $topSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit)
            ->get();

        // Get low selling products
        $lowSellingProducts = OrderDetail::whereBetween('order_details.created_at', [$startDate, $endDate])
            ->join('pharmacy_inventory', 'order_details.inventory_id', '=', 'pharmacy_inventory.id')
            ->join('products', 'pharmacy_inventory.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_details.quantity) as total_quantity'),
                DB::raw('SUM((order_details.price * order_details.quantity)+(order_details.tax_amount-order_details.discount_on_product)) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'asc')
            ->limit($limit)
            ->get();

        $pdf = PDF::loadView('admin-views.report.product-performance-pdf', compact(
            'topSellingProducts',
            'lowSellingProducts',
            'startDate',
            'endDate'
        ));

        return $pdf->download('product-performance-' . $startDate->format('Y-m-d') . '-to-' . $endDate->format('Y-m-d') . '.pdf');
    }
}
