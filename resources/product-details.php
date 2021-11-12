<?php layout('layouts.client.master') ?>
<?php section('title', '{{ $product_default["product_full_name"] }}') ?>
<?php section('content') ?>
    <h1>{{ $product_default['product_full_name'] }} - {{ $product_default['product_id'] }}</h1>
    @if($product_default['is_variant'] == 1)
        <h1>{{ $product_default['product_variant_price'] . ' đ' }}</h1>
        <form action="" method="GET" class="abc">
            @foreach($product_variant as $item)
                <label>
                    <input type="radio" name="color" value="{{ $item['product_variant_slug'] }}" {{ $product_default['product_variant_slug'] == $item['product_variant_slug'] ? "checked" : "" }} onchange="this.form.submit()">
                    {{ $item['product_variant_name'] }}
                </label>
            @endforeach
        </form>
    @else
        <h1>{{ $product_default['product_price'] . ' đ' }}</h1>
    @endif
<?php endsection() ?>
