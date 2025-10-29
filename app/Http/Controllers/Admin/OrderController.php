<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\DeliveryMan;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CentralLogics\translate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\CentralLogics\SMS_module;
use Illuminate\Support\Facades\Gate;


class OrderController extends Controller
{
    public function __construct(
        private Order $order,
        private OrderDetail $order_detail,
    ) {

        $this->middleware('checkAdminPermission:order.list,list')->only(['list']);
    }
    /**
     * @param $id
     * @return Application|Factory|View
     */
    public function generate_invoice($id) : View|Factory|Application
    {
        $order = $this->order->with('details.inventory.medicine.unit')->where('id', $id)->first();
        return view('admin-views.order.invoice-2', compact('order'));
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function payment_status(Request $request): RedirectResponse
    {
        $order = $this->order->find($request->id);

        $order->payment_status = $request->payment_status;

        $order->save();


        Toastr::success(translate('Payment status updated!'));
        return back();
    }
}
