<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Title -->
    <title>{{ translate('Invoice') }}</title>
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&amp;display=swap" rel="stylesheet">
    <!-- CSS Implementing Plugins -->
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/vendor.min.css">
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/vendor/icon-set/style.css">
    <!-- CSS Front Template -->
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/theme.minc619.css?v=1.0">
    <link rel="stylesheet" href="{{ asset(config('app.asset_path') . '/admin') }}/css/style.css">
</head>

<body class="footer-offset">

    <main id="content" role="main" class="main pointer-event">
        <div class="content container-fluid">
            <div class="row">
                <div class="col-12 text-center mb-3">
                    <img width="150"
                        src="{{ asset('public/storage/ecommerce') }}/{{ \App\Models\BusinessSetting::where(['key' => 'logo'])->first()->value }}">
                    <h3 class="mb-5 mt-2">{{ translate('Invoice') }} : #{{ $order['id'] }}</h3>
                </div>
                <div class="col-6 text-dark">
                    @if ($order->customer)
                        <h3>{{ translate('Customer Info') }}</h3>

                        <div class="">{{ $order->customer['f_name'] . ' ' . $order->customer['l_name'] }}</div>
                        <div class="">{{ $order->customer['email'] }}</div>
                        <div class="">{{ $order->customer['phone'] }}</div>
                        <div class="">{{ $order->delivery_address ? $order->delivery_address['address'] : '' }}</div>
                        <br>
                    @endif
                </div>

                <div class="col-6 text-dark text-right">
                    <h3>{{ translate('Billing Address') }}</h3>
                    <div>{{ \App\Models\BusinessSetting::where(['key' => 'phone'])->first()->value }}</div>
                    <div>{{ \App\Models\BusinessSetting::where(['key' => 'email_address'])->first()->value }}</div>
                    <div>{{ \App\Models\BusinessSetting::where(['key' => 'address'])->first()->value }}</div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="invoice-border"></div>
                    <table class="table table-bordered mt-3 text-dark">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('ID') }}</th>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Description') }}</th>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Batch Number') }}</th>
                                {{-- <th class="border-bottom-0">{{\App\CentralLogics\translate('Exp.Date')}}</th> --}}
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Unit') }}</th>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Qty') }}</th>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Price') }}</th>
                                <th class="border-bottom-0">{{ \App\CentralLogics\translate('Sub Total') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php($sub_total = 0)
                            @php($total_tax = 0)
                            @php($total_dis_on_pro = 0)
                            @foreach ($order->details as $index => $detail)

                                    <tr>
                                        <td class="">
                                            {{ $index + 1 }}
                                        </td>

                                        <td class="">
                                            {{ $detail->inventory->product->name }}
                                        </td>


                                        <td class="">
                                            @if ($detail->inventory)
                                                {{ $detail->inventory->batch_number }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td class="">
                                            {{ $detail->inventory->product->unit->name  }}
                                        </td>

                                        <td class="">
                                            {{ $detail['quantity'] }}
                                        </td>

                                        <td class="">
                                            {{ $detail['price'] }}
                                        </td>

                                        <td>
                                            @php($amount = ($detail['price'] - $detail['discount_on_product']) * $detail['quantity'])
                                            {{ Helpers::set_symbol($amount) }}
                                        </td>
                                    </tr>
                                    @php($sub_total += $amount)
                                    @php($total_tax += $detail['tax_amount'] * $detail['quantity'])
                            @endforeach
                        </tbody>
                    </table>
                    <div class="row pt-3 pb-2">
                        <div class="col-7">
                            <p style="display: flex; gap:10px;">Payment Method : - {{ $order->payment_method }} </p>

                            {{-- <p style="display: flex; gap:10px;">Amount In Words : {{\App\CentralLogics\translate( \Rmunate\Utilities\SpellNumber::value($order->order_amount)->locale('en')->currency('Birr')->fraction('cents')->toMoney()  )}} --}}
                        </div>

                        <div class="col-5">
                            <table class="table table-nowrap table-align-middle card-table">
                                <tr>
                                    <th
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        Sub Total</th>
                                    <td
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        {{ Helpers::set_symbol($order->subtotal + $order['extra_discount']) }}</td>
                                </tr>
                                <tr>
                                    <th
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        VAT</th>
                                    <td
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        {{ Helpers::set_symbol($order->total_tax_amount) }}</td>
                                </tr>
                                <tr>
                                    <th
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        Grand Total</th>
                                    <td
                                        style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                                        {{ Helpers::set_symbol($order->subtotal + $order->total_tax_amount) }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div>
                        {{-- <p class="mt-3 mb-3"><strong>Note:</strong> Must be Paid within 60 days. Due Date: {{ date('M d, Y', strtotime('+60 days')) }}</p> --}}

                    </div>
                    <div class="invoice-border"></div>

                    <div class="mt-3">
                        <div class="text-right" style="display: flex; gap:10px;">
                            <strong>{{ \App\CentralLogics\translate('PREPARED BY ') }}:</strong>
                            <p>{{ auth('admin')->user()->f_name }} {{ auth('admin')->user()->l_name }} </p>
                        </div>

                        <div class="text-right" style="display: flex; gap:10px;">
                            <strong>{{ \App\CentralLogics\translate('APPROVED BY ') }}:</strong>
                            <p> __________________________ </p>
                        </div>

                        <div class="text-right" style="display: flex; gap:10px;">
                            <strong>{{ \App\CentralLogics\translate('CUSTOMER NAME AND SIGN ') }}:</strong>
                            <p> __________________________ </p>

                        </div>
                    </div>
                    <!-- End Row -->
                </div>
            </div>

            <div class="text-dark mt-10">
                <div class="text-center mb-3">If you require any assistance or have feedback or suggestions about our
                    site you
                    Can <br /> email us at <a href="#"
                        class="text-primary">{{ \App\Models\BusinessSetting::where(['key' => 'email_address'])->first()?->value }}</a>
                </div>

                <div class="invoice-footer-bg py-5 px-4">
                    <div class="text-center">
                        <div>{{ translate('phone') }}:
                            {{ \App\Models\BusinessSetting::where(['key' => 'phone'])->first()?->value }}</div>
                        <div>{{ translate('eamil') }}:
                            {{ \App\Models\BusinessSetting::where(['key' => 'email_address'])->first()?->value }}</div>
                        <div><?php echo url('/'); ?></div>
                        <div>
                            &copy;
                            {{ \App\Models\BusinessSetting::where(['key' => 'restaurant_name'])->first()?->value }}.
                            {{ \App\Models\BusinessSetting::where(['key' => 'footer_text'])->first()?->value }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/demo.js"></script>
    <!-- JS Implementing Plugins -->
    <!-- JS Front -->
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/vendor.min.js"></script>
    <script src="{{ asset(config('app.asset_path') . '/admin') }}/js/theme.min.js"></script>
    <script>
        window.print();
    </script>
</body>

</html>
