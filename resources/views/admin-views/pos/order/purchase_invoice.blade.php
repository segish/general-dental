
<style>

    hr.new4 {
    border: 2px solid #363636;
    width: 100%;
    margin: 20px 0;
    }

</style>


<div  class="mx-auto" style="background-image: url('assets/admin/img/attachment.jpg'); background-repeat: repeat-y; background-size: contain;">
    <div class="row ">

        <div  class="col-12 text-dark d-flex flex-column justify-content-center align-items-center gap-4">
            <div>
                <h2 style="font-size: 28px; font-weight: bolder">
                    {{\App\Models\BusinessSetting::where(['key'=>'restaurant_name'])->first()->value}}
                </h2>
            </div>

            <div>
                <h2 style="font-size: 20px; font-weight: bolder">
                    {{\App\Models\BusinessSetting::where(['key'=>'address'])->first()->value}}
                </h2>
            </div>
        </div>

        <hr class="new4">

        <div class="col-12 text-dark  d-flex justify-content-between align-items-center">
            <h3 style="font-size: 15px;">{{ translate(' ') }} </h3>
             <h2 style="font-size: 35px; text-align: center"> <br> </h2>
             <div>
                 <h2 style="font-size: 20px; font-weight: bolder">Date : {{date('M d, Y')}}</h2>
             </div>
        </div>



    </div>


    <h3 class="text-center">Purchase (Recieve Inventory)</h3>
    <div class="invoice-border"></div>
    <div class="row pt-3 pb-2" >
        <div class="col-6">
            <table class="table table-nowrap table-align-middle card-table">
                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Bill To</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                        @if(isset($supplier) && $supplier->supplier_name)
                            {{$supplier->supplier_name}}
                        @else
                        Walking Supplier
                        @endif

                    </td>
                </tr>
                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">TIN</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">
                        @if(isset($supplier) && $supplier->tin)
                        {{$supplier->tin}}
                        @else
                        00000
                        @endif
                    </td>
                </tr>
                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Address</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">

                        @if(isset($supplier) && $supplier->address)
                        {{$supplier->address}}
                        @else
                        Addis Ababa
                        @endif
                    </td>
                </tr>

                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">A/C Number</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">

                        @if(isset($supplier) && $supplier->ac_number)
                        {{$supplier->ac_number}}
                        @else
                        00000
                        @endif
                    </td>
                </tr>

            </table>
        </div>

        <div class="col-6">
            <table class="table table-nowrap table-align-middle card-table">
                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Date</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">{{date('M d, Y')}}</td>
                </tr>
                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Reference </th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Ref_{{$order->id}}</td>
                </tr>


                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">GRV </th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">GRV_{{$order->id}}</td>
                </tr>


                <tr>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Store </th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Store 1</td>
                </tr>
            </table>
        </div>

    </div>
    <div class="row pt-3 pb-2">
        {{-- <div class="col-6">
            <h5>{{\App\CentralLogics\translate('Order ID')}} : {{$order['id']}}</h5>
        </div>
        <div class="col-6">
            <div class="text-right text-dark">
                {{date('d M Y h:i a',strtotime($order['created_at']))}}
            </div>
        </div>
        @if($order->customer)
            <div class="col-12 text-dark pb-2">
                <div>{{\App\CentralLogics\translate('Customer Name')}} : {{$order->customer['f_name'].' '.$order->customer['l_name']}}</div>
                <div>{{\App\CentralLogics\translate('Phone')}} : {{$order->customer['phone']}}</div>
                <div>
                    {{\App\CentralLogics\translate('Address')}}
                    : {{isset($order->delivery_address)?json_decode($order->delivery_address, true)['address']:''}}
                </div>
            </div>
        @endif --}}
    </div>
    <div class="invoice-border"></div>
    <table class="table table-bordered mt-3 text-dark">
        <thead>
            <tr>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Item ID')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Description')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Batch Number')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Unit')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Qty')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Price')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Line Total')}}</th>
            </tr>
        </thead>

        <tbody>
        @php($sub_total=0)
        @php($total=0)
        @php($total_dis_on_pro=0)
        @foreach($order->purchase_details as $ord)

                <tr>
                    <td class="">
                        {{$ord->product['productID']}}
                    </td>

                    <td class="">
                        {{$ord->product['name']}}
                    </td>

                    <td class="">
                        @if($ord->storeProduct)
                        {{ $ord->storeProduct->batch_number }}
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="">
                        {{$ord->product['unit']}}
                    </td>

                    <td class="">
                        {{$ord['quantity']}}
                    </td>

                    <td class="">
                        {{$ord['price']}}
                    </td>

                    <td>
                        @php($amount=($ord['price'])*$ord['quantity'])
                        {{ Helpers::set_symbol($amount) }}
                    </td>
                </tr>
                @php($sub_total+=$amount)
                @php($total+=$sub_total)
        @endforeach
        </tbody>
    </table>
    <div class="row pt-3 pb-2" >
        <div class="col-7">
            <p style="display: flex; gap:10px;">Payment Method :

                @if(isset($order->payment_method))
                - {{$order->payment_method}} 

                @if($order->payment_method=='credit' && $order->purchase_credit_end_date)
                    <span class="badge badge-danger">{{$order->purchase_credit_end_date}}</span>
                @endif
                @else
                N/A
                @endif

            </p>

            <p style="display: flex; gap:10px;">Amount In Words : {{\App\CentralLogics\translate( \Rmunate\Utilities\SpellNumber::value($sub_total)->locale('en')->currency('Birr')->fraction('cents')->toMoney()  )}}
        </div>

        <div class="col-5">
            <table class="table table-nowrap table-align-middle card-table">

                <t>
                    <th style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">Total</th>
                    <td style="padding:3px !important; padding-inline-end:5px important; padding-inline-start:5px !important; border: 1px solid rgb(44, 43, 43) !important;">{{ Helpers::set_symbol($sub_total) }} </td>
                </t>
            </table>
        </div>
    </div>

    <div class="invoice-border"></div>

    <div class="mt-3">
        <div class="text-right" style="display: flex; gap:10px;">
            <strong>{{\App\CentralLogics\translate('Comment ')}}:</strong>
            <p> __________________________ </p>
        </div>

        <div class="text-right" style="display: flex; gap:10px;">
            <strong>{{\App\CentralLogics\translate('PREPARED BY ')}}:</strong>
            <p>{{auth('admin')->user()->f_name}}  {{auth('admin')->user()->l_name}} </p>
        </div>

        <div class="text-right" style="display: flex; gap:10px;">
            <strong>{{\App\CentralLogics\translate('APPROVED BY ')}}:</strong>
            <p>  __________________________ </p>
        </div>

    </div>
    {{-- <dl class="row text-dark mt-2">
        <dt class="col-6">{{\App\CentralLogics\translate('Items Price')}}:</dt>
        <dd class="col-6 text-right">{{ Helpers::set_symbol($sub_total) }}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Tax')}} / {{\App\CentralLogics\translate('VAT')}}:</dt>
        <dd class="col-6 text-right">{{Helpers::set_symbol($total) }}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Subtotal')}}:</dt>
        <dd class="col-6 text-right">{{ Helpers::set_symbol($order->order_amount + $order['extra_discount']) }}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Coupon Discount')}}:</dt>
        <dd class="col-6 text-right">
            - {{ Helpers::set_symbol($order['coupon_discount_amount']) }}
        </dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Extra Discount')}}:</dt>
        <dd class="col-6 text-right">
            - {{ Helpers::set_symbol($order['extra_discount']) }}
        </dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Delivery Fee')}}:</dt>
        <dd class="col-6 text-right">
            @if($order['order_type']=='take_away')
                @php($del_c=0)
            @else
                @php($del_c=$order['delivery_charge'])
            @endif
            {{ Helpers::set_symbol($del_c) }}
        </dd>
        <dt class="col-6 font-weight-bold">{{\App\CentralLogics\translate('Total')}}:</dt>
        <dd class="col-6 text-right font-weight-bold">{{ Helpers::set_symbol($order->order_amount) }}</dd>
    </dl> --}}
    <div class="invoice-border mt-5"></div>
    <h5 class="text-center mb-0 py-3">
        """{{\App\CentralLogics\translate('THANK YOU')}}"""
    </h5>
    <div class="invoice-border"></div>
</div>
