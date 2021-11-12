<?php layout('layouts.client.master') ?>
<?php section('title', '{{ $cate_title }}') ?>
<?php section('content') ?>
    <h1>{{ $cate_title }}</h1>
    @foreach($products as $item)
        <li>
            <a href="{{ url($item['cate_parent_slug'].'/'.$item['cate_child_slug'].'-'.$item['product_slug']) }}">{{ $item['product_full_name'] }}</a>
        </li>
    @endforeach
<?php endsection() ?>