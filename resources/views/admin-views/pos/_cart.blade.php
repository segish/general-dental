<div class="table-responsive border-primary-light pos-cart-table rounded">
    <table class="table table-align-middle mb-0">
        <thead class="bg-primary-light text-dark">
            <tr>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('item') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('batch') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('qty') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('unit_price') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('discount') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('tax') }}</th>
                <th class="border-bottom-0">{{ \App\CentralLogics\translate('total_price') }}</th>
                <th class="border-bottom-0 text-center">{{ \App\CentralLogics\translate('delete') }}</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $subtotal = 0;
            $discount = 0;
            $discount_type = 'amount';
            $discount_on_product = 0;
            $total_tax = 0;
            $total_tax_on_product = 0;
            ?>
            @if (session()->has('cart') && count(session()->get('cart')) > 0)
                <?php
                $cart = session()->get('cart');
                if (isset($cart['discount'])) {
                    $discount = $cart['discount'];
                    $discount_type = $cart['discount_type'];
                }
                ?>
                @foreach (session()->get('cart') as $key => $cartItem)
                    @if (is_array($cartItem))
                        <?php
                        $product_subtotal = $cartItem['price'] * $cartItem['quantity'];
                        $discount_on_product += $cartItem['discount'] * $cartItem['quantity'];
                        $subtotal += $product_subtotal;
                        //tax calculation
                        $product = \App\Models\Product::find($cartItem['id']);

                        // $with_tax = session()->get('withTax') ?? 0;
                        // if ($with_tax == 1) {
                            $tax_on_product = \App\CentralLogics\Helpers::tax_calculate2($product, $cartItem['price']) * $cartItem['quantity'];
                            $total_tax_on_product += \App\CentralLogics\Helpers::tax_calculate2($product, $cartItem['price']) * $cartItem['quantity'];
                        // } else {
                        //     $total_tax_on_product = 0;
                        // }

                        $withHolding = $cartItem['withHoldingTax'] ?? 0;
                        if ($withHolding == 1) {
                            $total_tax += \App\CentralLogics\Helpers::tax_calculate($product, $cartItem['price']) * $cartItem['quantity'];
                        } else {
                            $total_tax = 0;
                        }

                        ?>
                        <tr>
                            <td class="media gap-2 align-items-center">
                                <div class="avatar-sm rounded border">
                                    @php
                                        $images = json_decode($cartItem['image'], true);
                                        $imagePath = !empty($images) && isset($images[0]) ? $images[0] : 'default.jpg'; // Fallback image
                                    @endphp

                                    <img class="img-fit rounded"
                                        src="{{ asset('storage/app/public/product/' . $imagePath) }}"
                                        onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
                                        alt="{{ $cartItem['name'] }} image">

                                </div>
                                <div class="media-body">
                                    <h5 class="mb-0">{{ Str::limit($cartItem['name'], 10) }}</h5>
                                </div>
                            </td>
                            <td>
                                @if ($cartItem['batch'])
                                    @php($batch = \App\Models\PharmacyInventory::find($cartItem['batch']))
                                @endif
                                <div class="fs-12">
                                    @if (isset($batch))
                                        {{ $batch->batch_number }}
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </td>
                            <td>
                                <input type="number" data-key="{{ $key }}" class="form-control qty"
                                    value="{{ $cartItem['quantity'] }}" min="0"
                                    onchange="updateQuantity(event)">
                            </td>
                            <td>
                                <div class="fs-12">
                                    {{ Helpers::set_symbol($cartItem['price']) }}
                                </div>
                            </td>
                            <td>
                                <div class="fs-12">
                                    {{ Helpers::set_symbol($cartItem['discount'] * $cartItem['quantity']) }}
                                </div>
                            </td>
                            <td>
                                <div class="fs-12">
                                    {{ Helpers::set_symbol($tax_on_product) }}
                                </div>
                            </td>

                            <td>
                                <div class="fs-12">
                                    {{ Helpers::set_symbol($product_subtotal - ($cartItem['discount'] * $cartItem['quantity']) + $tax_on_product) }}
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="javascript:removeFromCart({{ $key }})"
                                    class="btn btn-sm btn-outline-danger"> <i class="tio-delete-outlined"></i></a>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        </tbody>
    </table>
</div>

<?php
$total = $subtotal;

$session_subtotal = $subtotal;
$session_total = $subtotal + $total_tax_on_product + $total_tax - $discount_on_product;
\Session::put('subtotal', $session_subtotal);
\Session::put('total', $session_total);

$discount_amount = $discount_type == 'percent' && $discount > 0 ? ($total * $discount) / 100 : $discount;
$discount_amount += $discount_on_product;
$total -= $discount_amount;

$extra_discount = session()->get('cart')['extra_discount'] ?? 0;
$extra_discount_type = session()->get('cart')['extra_discount_type'] ?? 'amount';
if ($extra_discount_type == 'percent' && $extra_discount > 0) {
    $extra_discount = ($subtotal * $extra_discount) / 100;
}
if ($extra_discount) {
    $total -= $extra_discount;
}
?>

<div class="box p-3">
    <dl class="row">
        <dt class="col-6">{{ \App\CentralLogics\translate('sub_total') }} :</dt>
        <dd class="col-6 text-right">{{ Helpers::set_symbol($subtotal) }}</dd>


        <dt class="col-6">{{ \App\CentralLogics\translate('product') }}
            {{ \App\CentralLogics\translate('discount') }}:
        </dt>
        <dd class="col-6 text-right"> - {{ Helpers::set_symbol(round($discount_amount, 2)) }}</dd>

        <?php
        if (isset($cart['total_tax'])) {
            $tax_value_percent = $cart['total_tax'];
        } else {
            $tax = \App\Models\BusinessSetting::where('key', 'tax')->first()->value;
            $tax_value_percent = 0;
        }
        ?>
        <dt class="col-6">{{ \App\CentralLogics\translate('tax') }}</dt>
        <dd class="col-6 text-right">
            {{-- <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-tax"><i
                    class="tio-edit"></i>
            </button> --}}
            +{{ Helpers::set_symbol(round($total_tax_on_product, 2)) }}
        </dd>
        {{-- <dt class="col-6">{{ \App\CentralLogics\translate('extra') }} {{ \App\CentralLogics\translate('discount') }}:
        </dt>
        <dd class="col-6 text-right">
            <button class="btn btn-sm" type="button" data-toggle="modal" data-target="#add-discount"><i
                    class="tio-edit"></i>
            </button> - {{ Helpers::set_symbol($extra_discount) }}
        </dd> --}}
        <dt class="col-6 font-weight-bold fs-16 border-top pt-2">{{ \App\CentralLogics\translate('total') }} :</dt>
        <dd class="col-6 text-right font-weight-bold fs-16 border-top pt-2">
            {{ Helpers::set_symbol(round($total + $total_tax + $total_tax_on_product, 2)) }}</dd>
    </dl>

    <form action="{{ route('admin.pos.order') }}" id='order_place' method="post">
        @csrf
        <div class="form-group">
            <div>
                <label for="fs_no" class="input-label">FS Number</label>
                <input type="text" id="fs_no" name="fs_no" class="form-control">
            </div>
        </div>

        <div class="my-4">
            <div class="text-dark d-flex mb-2">Paid By:</div>
            <ul class="list-unstyled option-buttons">
                <li>
                    <input type="radio" onclick="changePaymentType('cash')" id="cash" value="cash"
                        name="type" hidden="" checked="">
                    <label for="cash" class="btn border px-4 mb-0">Cash</label>
                </li>
                <li>
                    <input type="radio" onclick="changePaymentType('bank_transfer')" value="bank_transfer" id="bank_transfer"
                        name="type" hidden="">
                    <label for="bank_transfer" class="btn border px-4 mb-0">bank transfer</label>
                </li>
                <li>
                    <input type="radio" onclick="changePaymentType('wallet')" value="wallet" id="wallet"
                        name="type" hidden="">
                    <label for="wallet" class="btn border px-4 mb-0">wallet</label>
                </li>
            </ul>
        </div>

        <div id="amount_recieved" class="my-4">
            <label for="amount_recieved" class="form-label">Amount Recieved:</label>
            <input type="text" value="0" id="amount_recieved" name="amount_recieved" class="form-control">
        </div>

        <div class="row g-2">
            <div class="col-sm-6">
                <a href="#" class="btn btn-danger btn-block" onclick="emptyCart()"><i
                        class="fa fa-times-circle"></i> {{ \App\CentralLogics\translate('Cancel_Order') }} </a>
            </div>
            <div class="col-sm-6">
                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-shopping-bag"></i>
                    {{ \App\CentralLogics\translate('Place_Order') }} </button>
            </div>
        </div>
    </form>


</div>

<!-- Add Discount -->
<div class="modal fade" id="add-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ \App\CentralLogics\translate('update_discount') }}</h4><br>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="d-flex align-items-center justify-content-center">
                <small class="bg-soft-danger text-danger p-2 badge text-center">
                    {{ \App\CentralLogics\translate('this discount will be applied to the total amount and will not be applied to TAX') }}
                </small>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pos.discount') }}" method="post" class="row">
                    @csrf
                    <div class="form-group col-sm-6">
                        <label for="">{{ \App\CentralLogics\translate('discount') }}</label>
                        <input type="number" value="{{ session()->get('cart')['extra_discount'] ?? 0 }}"
                            class="form-control" name="discount">
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="">{{ \App\CentralLogics\translate('type') }}</label>
                        <select name="type" class="form-control">
                            <option value="amount" {{ $extra_discount_type == 'amount' ? 'selected' : '' }}>
                                {{ \App\CentralLogics\translate('amount') }}({{ \App\CentralLogics\Helpers::currency_symbol() }})
                            </option>
                            <option value="percent" {{ $extra_discount_type == 'percent' ? 'selected' : '' }}>
                                {{ \App\CentralLogics\translate('percent') }}(%)</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end col-sm-12">
                        <button class="btn btn-primary"
                            type="submit">{{ \App\CentralLogics\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Coupon Discount -->
<div class="modal fade" id="add-coupon-discount" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ \App\CentralLogics\translate('Coupon_discount') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pos.discount') }}" method="post" class="row">
                    @csrf
                    <div class="form-group col-12">
                        <label for="">{{ \App\CentralLogics\translate('Coupon_code') }}</label>
                        <input type="number" placeholder="{{ \App\CentralLogics\translate('SULTAN200') }}"
                            class="form-control">
                    </div>
                    <div class="d-flex justify-content-end col-12">
                        <button class="btn btn-primary"
                            type="submit">{{ \App\CentralLogics\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CentralLogics\translate('update_tax') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.pos.with_tax') }}" method="POST" class="row">
                    @csrf

                    <?php
                    $taxTotal = session()->get('withTax') ?? 0;
                    ?>
                    <div class="form-group">
                        <div class="col-12">
                            <input type="radio" id="withTax" class="form-" name="with_tax" min="0"
                                value="1" {{ $taxTotal == 1 ? 'checked' : '' }}>
                            <label for="withTax">{{ \App\CentralLogics\translate('With Tax') }}</label>
                        </div>
                        <div class="col-12">
                            <input type="radio" id="withoutTax" class="form-" name="with_tax" min="0"
                                value="0" {{ $taxTotal == 0 ? 'checked' : '' }}>
                            <label for="withoutTax">{{ \App\CentralLogics\translate('Without Tax') }}</label>
                        </div>

                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary"
                            type="submit">{{ \App\CentralLogics\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="add-withholding-tax" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ \App\CentralLogics\translate('update_withholding_tax') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="{{ route('admin.pos.with_holding_tax') }}" method="POST" class="row">

                    @csrf

                    <?php

                    if (isset($cart['withHoldingTax'])) {
                        $withHoldingTotal = $cart['withHoldingTax'];
                    } else {
                        $withHoldingTotal = 0;
                    }
                    ?>
                    <div class="form-group">
                        <div class="col-12">

                            <input type="radio" id="withWithHoldingTax" class="form-" name="withHoldingTax"
                                min="0" value="1" {{ $withHoldingTotal == 1 ? 'checked' : '' }}>
                            <label
                                for="withWithHoldingTax">{{ \App\CentralLogics\translate('With WithHolding Tax ') }}
                            </label>
                        </div>
                        <div class="col-12">
                            <input type="radio" id="withoutWithHoldingTax" class="form-" name="withHoldingTax"
                                min="0" value="0" {{ $withHoldingTotal == 0 ? 'checked' : '' }}>
                            <label
                                for="withoutWithHoldingTax">{{ \App\CentralLogics\translate('Without WithHolding Tax') }}
                            </label>
                        </div>

                    </div>

                    <div class="form-group col-sm-12">
                        <button class="btn btn-sm btn-primary"
                            type="submit">{{ \App\CentralLogics\translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <div class="modal fade" id="paymentModal" tabindex="-1"> --}}
{{--    <div class="modal-dialog"> --}}
{{--        <div class="modal-content"> --}}
{{--            <div class="modal-header"> --}}
{{--                <h5 class="modal-title">{{\App\CentralLogics\translate('payment')}}</h5> --}}
{{--                <button type="button" class="close" data-dismiss="modal" aria-label="Close"> --}}
{{--                    <span aria-hidden="true">&times;</span> --}}
{{--                </button> --}}
{{--            </div> --}}
{{--            <div class="modal-body"> --}}
{{--                <form action="{{route('admin.pos.order')}}" id='order_place' method="post" class="row"> --}}
{{--                    @csrf --}}
{{--                    <div class="form-group col-12"> --}}
{{--                        <label class="input-label" for="">{{\App\CentralLogics\translate('amount')}} ({{\App\CentralLogics\Helpers::currency_symbol()}} --}}
{{--                            )</label> --}}
{{--                        <input type="number" class="form-control" name="amount" min="0" step="0.01" --}}
{{--                            value="{{round($total+$total_tax, 2)}}" disabled> --}}
{{--                    </div> --}}
{{--                    <div class="form-group col-12"> --}}
{{--                        <label class="input-label" for="">{{\App\CentralLogics\translate('type')}}</label> --}}
{{--                        <select name="type" class="form-control"> --}}
{{--                            <option value="cash">{{\App\CentralLogics\translate('cash')}}</option> --}}
{{--                            <option value="card">{{\App\CentralLogics\translate('card')}}</option> --}}
{{--                        </select> --}}
{{--                    </div> --}}
{{--                    <div class="form-group col-12"> --}}
{{--                        <button class="btn btn-sm btn-primary" --}}
{{--                                type="submit">{{\App\CentralLogics\translate('submit')}}</button> --}}
{{--                    </div> --}}
{{--                </form> --}}
{{--            </div> --}}
{{--        </div> --}}
{{--    </div> --}}
{{-- </div> --}}
