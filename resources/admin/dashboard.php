<?php layout('layouts.admin.master') ?>

<?php section('title', 'Dashboard') ?>

<?php section('content') ?>
    <h1>Hello, 
        @auth 
            {{ auth['first_name'] }}
        @endauth
    </h1>
<?php endsection() ?>