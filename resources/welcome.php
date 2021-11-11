<?php layout('layouts.client.master') ?>
<?php section('title', 'Trang chu') ?>
<?php section('content') ?>
    @if(!empty(session_get('message')))
        <p><strong>{{ session_get('message') }}</strong></p>
    @endif
    {! session_remove('message') !}
    
    <ul>
    @foreach($products as $item)
        <li>
            <a href="{{ url($item['cate_slug'].'/'.$item['brand_slug'].'-'.$item['slug']) }}">{{ $item['title'] }}</a>
        </li>
    @endforeach
    </ul>
<?php endsection() ?>


