<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= empty(register('title')) ? APP_NAME : register('title') ?></title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
    <?= register('styles'); ?>
</head>
<body>

<h1>Header</h1>
<ul>
    <li><a href="<?= route() ?>">Trang chu</a></li>
    <li><a href="<?= route('danh-muc-dien-thoai') ?>">Dien thoai</a></li>
    <li><a href="<?= route('danh-muc-tablet') ?>">Tablet</a></li>
    <li>
        @auth 
            Hello, {{ auth['first_name'] }} <a href="<?= route('account.logout') ?>">Logout</a>
        @else
            <a href="<?= route('account.login') ?>">Login</a>
        @endauth
    </li>
</ul>
<hr>

<?= register('content'); ?>

<hr>
<h1>Footer</h1>

<?= register('scripts'); ?>
</body>
</html>