<?php layout('layouts.client.master') ?>
<?php section('title', 'Trang chu') ?>
<?php section('content') ?>
    <ul>
    @foreach($products as $item)
        <li>
            <a href="{{ url($item['cate_slug'].'/'.$item['brand_slug'].'-'.$item['slug']) }}">{{ $item['title'] }}</a>
        </li>
    @endforeach
    </ul>
<?php endsection() ?>


