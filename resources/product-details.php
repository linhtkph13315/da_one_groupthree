<?php layout('layouts.client.master') ?>
<?php section('title', '{{ $product["title"] }}') ?>
<?php section('content') ?>
    <h1>{{ $product['title'] }}</h1>
<?php endsection() ?>