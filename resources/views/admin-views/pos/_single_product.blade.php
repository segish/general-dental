<div class="pos-product-item card" onclick="quickView('{{ $product->id }}')">
    <div class="pos-product-item_thumb">
        <?php
        $image = isset($product['image']) ? json_decode($product['image'], true) : null;
        $imageSrc = isset($image[0]) ? asset('public/storage/product/' . $image[0]) : asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg');
        ?>

        <img src="{{ $imageSrc }}"
            onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/160x160/img2.jpg') }}'"
            class="img-fit rounded">

    </div>

    <div class="pos-product-item_content clickable">
        <div class="pos-product-item_title">
            {{ Str::limit($product['name'], 15) }}
        </div>
        <div class="pos-product-item_price">
            {{ Helpers::set_symbol(
                $product->pharmacyInventories->first()->selling_price,
                //- \App\CentralLogics\Helpers::discount_calculate($product, $product->pharmacyInventory->first()->selling_price),
            ) }}

            {{-- @if ($product->discount > 0)
                <strike class="fs-12 text-muted">
                    {{ Helpers::set_symbol($product['price']) }}
                </strike>
            @endif --}}
        </div>
    </div>
</div>
