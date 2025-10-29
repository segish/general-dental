
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
            {{-- <div>
                <h2 style="font-size: 20px; font-weight: bolder">
                    {{\App\Models\BusinessSetting::where(['key'=>'address'])->first()->value}}
                </h2>
            </div> --}}
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


    <h3 class="text-center">Customer Requisition Form</h3>
    <div class="d-flex align-items-center gap-2">
        <h4> To : </h4>
        <h4 style="text-decoration: underline;">
            @if($supplier)
                {{$supplier->f_name}} {{$supplier->l_name}}
            @endif
        </h4>
    </div>
    <div class="invoice-border"></div>


    <div class="invoice-border"></div>
    <table class="table table-bordered mt-3 text-dark">
        <thead>
            <tr>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('No')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Item Code')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Item Name')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Price')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Unit')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Qty')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Batch Number')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Exp Date')}}</th>
            </tr>
        </thead>

        <tbody>
        {{-- @php($sub_total=0)
        @php($total=0)
        @php($total_dis_on_pro=0) --}}
        @foreach($order->requisition_details as $key=>$ord)

                <tr>
                    <td class="">{{$key+1}}</td>
                    <td class="">
                        {{$ord->product['productID']}}
                    </td>

                    <td class="">
                        {{$ord->product['name']}}
                    </td>
                    <td class="">
                        {{$ord['price']}}
                    </td>

                    <td class="">
                        {{$ord->product['unit']}}
                    </td>

                    <td class="">
                        {{$ord['quantity']}}
                    </td>
                    <td class="">
                        @if($ord->storeProduct)
                        {{ $ord->storeProduct->batch_number }}
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="">
                        @if($ord->storeProduct)
                        {{ $ord->storeProduct->expiration_date }}
                        @else
                        N/A
                        @endif
                    </td>
                </tr>
        @endforeach
        </tbody>
    </table>

    <div class="invoice-border"></div>

    <div class="mt-3 row">
        <div class="col-6">
            <div class="text-right" style="display: flex; gap:10px;">
                <strong>{{\App\CentralLogics\translate('Requested By ')}}:</strong>
                <p> __________________________ </p>
            </div>

            <div class="text-right" style="display: flex; gap:10px;">
                <strong>{{\App\CentralLogics\translate('Signature')}}:</strong>
                <p> __________________________ </p>
            </div>

        </div>

        <div class="col-6">
            <div class="text-right" style="display: flex; gap:10px;">
                <strong>{{\App\CentralLogics\translate('APPROVED BY ')}}:</strong>
                <p>  __________________________ </p>
            </div>

            <div class="text-right" style="display: flex; gap:10px;">
                <strong>{{\App\CentralLogics\translate('Signature')}}:</strong>
                <p> __________________________ </p>
            </div>
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
