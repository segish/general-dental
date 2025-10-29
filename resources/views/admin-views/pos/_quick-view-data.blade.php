<div class="modal-header p-2">
    <h4 class="modal-title product-title"></h4>
    <button class="close call-when-done" type="button" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="media flex-wrap gap-3">
        <!-- Product gallery-->
        <div class="box-120 rounded border">
            @php
                $images = json_decode($product['image'], true);
                $imagePath = !empty($images) && isset($images[0]) ? $images[0] : 'default.jpg'; // Fallback image
            @endphp

            <img class="img-fit rounded" src="{{ asset('storage/app/public/product/' . $imagePath) }}"
                onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
                data-zoom="{{ asset('storage/app/public/product/' . $imagePath) }}" alt="Product image">
            <div class="cz-image-zoom-pane"></div>

        </div>

        <!-- Product details-->
        <div class="details media-body">
            <h5 class="product-name"><a href="#"
                    class="h3 mb-2 product-title">{{ Str::limit($product->name, 100) }}</a></h5>

            <div class="mb-2">
                <span class="h5 font-weight-normal text-primary" id="product-price">

                    {{ Helpers::set_symbol($product->price) }}
                </span>
                @php $currency = \App\Models\Currency::where(['currency_code' => Helpers::currency_code()])->first()->currency_symbol; @endphp

                {{ $currency }}
            </div>

            @if ($product->discount > 0)
                <div class="mb-0 text-dark">
                    <span>{{ translate('Discount') }} : </span>
                    <span
                        id="set-discount-amount">{{ Helpers::set_symbol(\App\CentralLogics\Helpers::discount_calculate($product, $product->price)) }}</span>
                </div>
            @endif
        </div>
    </div>
    <div class="row pt-4">
        <div class="col-12">
            <?php
            $cart = false;
            if (session()->has('cart')) {
                foreach (session()->get('cart') as $key => $cartItem) {
                    if (is_array($cartItem) && $cartItem['id'] == $product['id']) {
                        $cart = $cartItem;
                    }
                }
            }
            ?>

            <form id="add-to-cart-form" class="mb-2">
                @csrf
                <div class="form-group">
                    <label class="input-label"
                        for="exampleFormControlSelect1">{{ \App\CentralLogics\translate('Batch Number') }}<span
                            class="input-label-secondary">*</span></label>
                    <select name="batch" id="batch" class="form-control js-select2-custom"
                        onchange="fetchBatchPrice()">
                        @php
                            $batches = $product->pharmacyInventories->filter(function ($batch) {
                                return $batch->quantity > 0 && $batch->expiry_date > \Carbon\Carbon::now(); // Ensure batch hasn't expired
                            });
                        @endphp
                        @foreach ($batches as $batch)
                            <option value="{{ $batch['id'] }}" data-sale-price="{{ $batch['selling_price'] }}"
                                {{ $loop->first ? 'selected' : '' }}>
                                {{ $batch['batch_number'] }} ({{ $batch['quantity'] }})
                            </option>
                        @endforeach
                    </select>

                </div>
                <input type="hidden" name="id" value="{{ $product->id }}">
                {{-- @foreach (json_decode($product->choice_options) as $key => $choice)
                    <h3 class="mb-2 pt-4">{{ $choice->title }}</h3>
                    <div class="d-flex gap-3 flex-wrap">
                        @foreach ($choice->options as $key => $option)
                            <input class="btn-check" type="radio" id="{{ $choice->name }}-{{ $option }}"
                                name="{{ $choice->name }}" value="{{ $option }}"
                                @if ($key == 0) checked @endif autocomplete="off">
                            <label class="check-label rounded px-2 py-1 text-center lh-1.3 mb-0 choice-input"
                                for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                        @endforeach
                    </div>
                @endforeach --}}

                <!-- Quantity + Add to cart -->
                <div class="d-flex justify-content-between mt-4">
                    <h3 class="product-description-label mb-0 text-dark">
                        {{ \App\CentralLogics\translate('Quantity') }}:</h3>
                    <div class="product-quantity d-flex align-items-center">
                        <div class="product-quantity-group" id="quantity_div">
                            <button class="btn btn-number p-2 text-dark" type="button" data-type="minus"
                                data-field="quantity" disabled="disabled">
                                <i class="tio-remove font-weight-bold"></i>
                            </button>
                            <input type="text" name="quantity" id="quantity"
                                class="form-control input-number text-center cart-qty-field" placeholder="1"
                                value="1" min="0">
                            <button class="btn btn-number p-2 text-dark" type="button" data-type="plus"
                                data-field="quantity">
                                <i class="tio-add font-weight-bold"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="row no-gutters mt-3 text-dark" id="unit_price">
                    <div class="col-4 d-flex align-items-center">
                        <div class="product-description-label">{{ \App\CentralLogics\translate('Unit Price') }}:</div>
                    </div>
                    <div class="col-8 d-flex align-items-center">
                        <input type="number" name="unit_price" id="unit_price_input" value=""
                            class="form-control" disabled>
                        {{-- <input type="number" name="unit_price_displayed" id="unit_price_displyed" value=""
                            class="form-control" disabled> --}}
                    </div>
                </div>
                <div class="row no-gutters mt-3 text-dark" id="chosen_price_div">
                    <div class="col-4 mr-3">
                        <div class="product-description-label">{{ \App\CentralLogics\translate('Total Price') }}:</div>
                    </div>
                    <div class="col-7">
                        <div class="product-price">
                            <strong id="chosen_price"></strong> {{ \App\CentralLogics\Helpers::currency_symbol() }}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    <button class="btn btn-primary" onclick="addToCart()" type="button"
                        style="width:37%; height: 45px">
                        <i class="tio-shopping-cart"></i>
                        {{ \App\CentralLogics\translate('add') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function fetchBatchPrice() {
        var selectedBatch = $('#batch').find(':selected');
        var salePrice = selectedBatch.data('sale-price');
        $('#unit_price_input').val(salePrice);
        $('#unit_price_displyed').val(salePrice);
        $('#product-price').html(salePrice);

        updateTotalPrice();
    }

    function updateTotalPrice() {
        var selectedBatch = $('#batch').find(':selected');
        var salePrice = selectedBatch.data('sale-price');
        var unitPrice = parseFloat(salePrice);
        var quantity = parseFloat($('#quantity').val());
        var totalPrice = unitPrice * quantity;

        $('#chosen_price').html(totalPrice.toFixed(2));
    }

    $('#batch').on('change', fetchBatchPrice);

    $('#quantity').on('input', function() {
        var quantity = parseFloat($(this).val());
        updateTotalPrice();
        updateButtons();
    });

    // $('#unit_price_input').on('change', updateTotalPrice);

    $('.btn-number').on('click', function(e) {
        e.preventDefault();

        var fieldName = $(this).attr('data-field');
        var type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseFloat(input.val());

        if (!isNaN(currentVal)) {
            if (type === 'minus') {
                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
            } else if (type === 'plus') {
                input.val(currentVal + 1).change();
            }
            updateTotalPrice();
            updateButtons();
        } else {
            input.val(1);
            updateButtons();
        }
    });

    function updateButtons() {
        var quantity = parseFloat($('#quantity').val());
        if (quantity <= 1) {
            $(".btn-number[data-type='minus']").attr('disabled', true);
        } else {
            $(".btn-number[data-type='minus']").attr('disabled', false);
        }
    }

    $(document).ready(function() {
        fetchBatchPrice();
        updateButtons();
    });
</script>
