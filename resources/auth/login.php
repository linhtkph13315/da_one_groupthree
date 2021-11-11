<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body>
    <form action="" method="post">
        <h1>Login</h1>

        @if(!empty(session_get('message')))
            <p><strong>{{ session_get('message') }}</strong></p>
        @endif
        {! session_remove('message') !}

        @if(isset($message))
            <p><strong>{{ $message }}</strong></p>
        @endif

        <p>
            <label for="email">Username</label>
            <input type="text" name="email" id="email" value="{{ old('email') }}"/>
            <span class="errors">{{ $errors['email'][0] ?? '' }}</span>
        </p>
        <p>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="{{ old('password') }}"/>
            <span class="errors">{{ $errors['password'][0] ?? '' }}</span>
        </p>
        <p>
            <a href="<?= route('account.forgot-password') ?>">Forgot password?</a>
        </p>
        <p>
            <button type="submit">Login</button>
        </p>
        <p>
            Do not have an account? <a href="<?= route('account.register') ?>">Register</a> now.
        </p>
    </form>
</body>
</html>