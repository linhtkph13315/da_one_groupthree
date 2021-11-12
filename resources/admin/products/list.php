<?php layout('layouts.admin.master') ?>

<?php section('title', 'Danh sach san pham') ?>

<?php section('content') ?>
    <p>Product <a href="{{ url('admin/product/create') }}">Thêm</a></p>
    <ul>
        @foreach($products as $item)
        <li>
            {{ $item['product_name'] }}:
            @if($item['is_variant'] === 1)
            [<a href="{{ url('admin/product/variant?pid='.$item['product_id']) }}">Danh sach bien the</a>]
            [<a href="{{ url('admin/product/variant/create?pid='.$item['product_id']) }}">Them bien the moi</a>]
            [<a href="{{ url('admin/product/configuration?pid='.$item['product_id']) }}">Cap nhat cau hinh</a>]
            @endif
            [<a href="{{ url('admin/product/update/'.$item['product_id']) }}">Sửa</a>]
            [<a href="{{ url('admin/product/delete/'.$item['product_id']) }}">Xoá</a>]
        </li>
        @endforeach
    </ul>
<?php endsection() ?>