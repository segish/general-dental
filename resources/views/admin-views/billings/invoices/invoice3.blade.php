
<div class="row" id="printableArea" style="margin: auto;">
<div style="width:410px;">
    <div class="text-center pt-4 mb-3">
        <h2 style="line-height: 1">{{\App\Models\BusinessSetting::where(['key'=>'laboratory_center_name'])->first()->value}}</h2>
        <h5 style="font-size: 20px;font-weight: lighter;line-height: 1">
            {{\App\Models\BusinessSetting::where(['key'=>'address'])->first()->value}}
        </h5>
        <h5 style="font-size: 16px;font-weight: lighter;line-height: 1">
            {{\App\CentralLogics\translate('Phone')}}
            : {{\App\Models\BusinessSetting::where(['key'=>'phone'])->first()->value}}
        </h5>
    </div>

    <span>---------------------------------------------------------------------------------</span>
    <div class="row mt-3">
        <div class="col-6">
            <h5>{{\App\CentralLogics\translate('Reg No')}} : {{$billing->visit->patient['registration_no']}}</h5>
        </div>
        <div class="col-6">
            <h5 style="font-weight: lighter">
                {{date('d/M/Y h:i a',strtotime($billing['created_at']))}}
            </h5>
        </div>
        @if($billing->visit->patient)
            <div class="col-6">
                <h5>{{$billing ->visit->patient['full_name']}}</h5>

            </div>
            <div class="col-6">
                <h5>{{$billing->visit->patient['phone']}}</h5>

            </div>
        @endif
    </div>
    <h5 class="text-uppercase"></h5>
    <span>---------------------------------------------------------------------------------</span>

    <table class="table table-bordered mt-3 text-dark">
        <thead>
            <tr>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Qty')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Desc')}}</th>
                <th class="border-bottom-0">{{\App\CentralLogics\translate('Price')}}</th>
            </tr>
        </thead>

        <tbody>
            @php($sub_total = 0)
            @php($total_tax = 0)
            @php($totalAmount = 0)
        @foreach($billing->billingDetail as $detail)
                <tr>
                    <td class="">
                        {{$detail['quantity']}}
                    </td>
                    <td class="">
                        <div class="mb-1"> {{ Str::limit($detail->medicine_id?$detail->medicine['name']:$detail->service['service_name'], 200) }}</div>
                    </td>
                    <td>
                        @php($amount=($detail['unit_cost'])*$detail['quantity'])
                        {{ Helpers::set_symbol($amount) }}
                    </td>
                </tr>
                @php($sub_total+=$amount)
        @endforeach
        </tbody>

        @php($taxRate = \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0)
        @php($total_tax = $sub_total * ($taxRate / 100))
        @php($totalAmount = $sub_total + $total_tax - $billing->discount)

    </table>

    <div class="invoice-border"></div>
    <dl class="row text-dark mt-2">
        <dt class="col-6">{{\App\CentralLogics\translate('Subtotal')}}:</dt>
        <dd class="col-6 text-right">{{Helpers::set_symbol($sub_total)}}</dd>

        <dt class="col-6">{{\App\CentralLogics\translate('Tax')}} / {{\App\CentralLogics\translate('VAT')}}:</dt>
        <dd class="col-6 text-right">{{Helpers::set_symbol($total_tax) }}</dd>


        <dt class="col-6">{{\App\CentralLogics\translate(' Discount')}}:</dt>
        <dd class="col-6 text-right">
            - {{ Helpers::set_symbol($billing['discount']) }}
        </dd>


        <dt class="col-6 font-weight-bold">{{\App\CentralLogics\translate('Total')}}:</dt>
        <dd class="col-6 text-right font-weight-bold">{{ Helpers::set_symbol($totalAmount) }}</dd>
    </dl>
    <div class="invoice-border mt-5"></div>
    <h5 class="text-center mb-0 py-3">
        """{{\App\CentralLogics\translate('THANK YOU')}}"""
    </h5>
    <div class="invoice-border"></div>
</div>
</div>
