<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body>
<form action="" method="post">
    <h1>Register</h1>

    @if(!empty(session_get('message')))
        <p><strong>{{ session_get('message') }}</strong></p>
    @endif
    {! session_remove('message') !}

    <p>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="{{ old('email') }}"/>
        <span class="errors">{{ $errors['email'][0] ?? '' }}</span>
    </p>
    <p>
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="{{ old('name') }}"/>
        <span class="errors">{{ $errors['name'][0] ?? '' }}</span>
    </p>
    <p>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="{{ old('password') }}"/>
        <span class="errors">{{ $errors['password'][0] ?? '' }}</span>
    </p>
    <p>
        <label for="confirm_password">Confirm password</label>
        <input type="password" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}"/>
        <span class="errors">{{ $errors['confirm_password'][0] ?? '' }}</span>
    </p>
    <p>
        <button type="submit">Register</button>
    </p>
    <p>
        <a href="<?= route('account.login') ?>">Login</a> now!
    </p>
</form>
</body>
</html>