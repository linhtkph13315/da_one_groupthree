<?php layout('layouts.admin.master') ?>

<?php section('title', 'Variant list') ?>

<?php section('content') ?>
    <a href="{{ url('admin/product') }}">Danh sach san pham</a>
    <p>Variant list <a href="{{ url('admin/product/variant/create?pid='.requestInput('pid')) }}">Them bien the moi</a></h1>
    <ul>
        <li>
            Xanh
            [<a href="{{ url('admin/product/variant/update?pid='.requestInput('pid').'&vid=2') }}">Sửa</a>]
            [<a href="{{ url('admin/product/variant/delete?pid='.requestInput('pid').'&vid=2') }}">Xoá</a>]
        </li>
    </ul>
<?php endsection() ?>