<?php layout('layouts.admin.master') ?>

<?php section('title', 'Danh sach san pham') ?>

<?php section('content') ?>
    <p>Product <a href="{{ url('admin/product/create') }}">Thêm</a></p>
    <ul>
        @foreach($products as $item)
        <li>
            {{ $item['title'] }}:
            [<a href="{{ url('admin/product/update/'.$item['product_id']) }}">Sửa</a>]
            [<a href="{{ url('admin/product/delete/'.$item['product_id']) }}">Xoá</a>]
        </li>
        @endforeach
    </ul>
<?php endsection() ?>