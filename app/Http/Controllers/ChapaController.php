<?php

namespace App\Http\Controllers;


use App\CentralLogics\Helpers;
use App\Models\BusinessSetting;
use App\Models\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChapaController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    protected $reference;
    protected $secretKey;
    protected $publicKey;
    protected $baseUrl;

    public function __construct()

    {

        $payment = Helpers::get_business_settings('chapa');

        $this->publicKey = $payment["chapa_client_id"];
        $this->secretKey = $payment["chapa_secret"];

        $this->reference =  env('APP_NAME') . '_' . 'chapa_' . uniqid(time());
        $this->baseUrl = 'https://api.chapa.co/v1';
    }
    public function initialize()
    {
        $reference = $this->reference;
        $order = Order::with(['details'])->where(['id' => session('order_id'), 'user_id' => session('customer_id')])->first();
        $user_data = User::find(session('customer_id'));
        // Enter the details of the payment
        $logo = Helpers::get_business_settings('logo');
        $business_name = Helpers::get_business_settings('business_name');
        $return_url = route('payment-success');
        if($order->callback){
            $return_url = $order->callback . '/success';
        }

        $data = [
            'amount' => $order->order_amount,
            'email' => $user_data['email'],
            'tx_ref' => $reference,
            'currency' => Helpers::currency_code(),
            'callback_url' => route('chapa-callback', [$reference]),
            'return_url' => $return_url,
            'first_name' => $user_data['f_name'],
            'last_name' => $user_data['l_name'],
            "customization" => [
                'logo' => 'https://admin.agtaa.com/public/storage/app/public/assets/ecommerce/2023-09-25-651197dd57df7.png',
                "title" => "AGTA PLC" ,
                "description" => strval($order->id),
            ]
        ];

        $payment = Http::withToken($this->secretKey)->post(
            $this->baseUrl . '/transaction/initialize',
            $data
        )->json();

        if (
            $payment['status'] !== 'success'
        ) {
            $order->order_status = 'failed';
            $order->save();
            if ($order->callback != null) {
                return redirect($order->callback . '&status=fail');
            } else {
                return \redirect()->route('payment-fail');
            }
        } else {
            $order->transaction_reference = $reference;
            $order->save();
            return redirect($payment['data']['checkout_url']);
        }
    }

    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($reference)
    {
        $order = Order::with(['details'])->where(['transaction_reference' => $reference])->first();
        $data = Http::withToken($this->secretKey)->get($this->baseUrl . "/transaction/" . 'verify/' . $reference)->json();

        //if payment is successful
        if ($data["status"] ==  'success') {
            $order->payment_method = 'chapa';
            $order->payment_status = 'paid';
            $order->order_status = 'confirmed';
            $order->save();
            if ($order->callback != null) {
                return redirect($order->callback . '&status=success');
            }
            return \redirect()->route('payment-success');
        } else {
            $order->order_status = 'failed';
            $order->save();
            if ($order->callback != null) {
                return redirect($order->callback . '&status=fail');
            }
            return \redirect()->route('payment-fail');
        }
    }
}
