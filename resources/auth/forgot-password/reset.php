<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset password</title>
    <link rel="stylesheet" href="<?= asset('css/styles.css') ?>">
</head>
<body>
<form action="" method="post">
    <h1>Reset password</h1>

    @if(!empty(session_get('message')))
        <p><strong>{{ session_get('message') }}</strong></p>
    @endif
    {! session_remove('message') !}

    <p>
        <label for="email">Email</label>
        <input type="text" id="email" value="{{ $email }}" disabled/>
    </p>
    <p>
        <label for="password">New password</label>
        <input type="password" name="password" id="password" value="{{ old('password') }}"/>
        <span class="errors">{{ $errors['password'][0] ?? '' }}</span>
    </p>
    <p>
        <label for="confirm_password">Confirm password</label>
        <input type="password" name="confirm_password" id="confirm_password" value="{{ old('confirm_password') }}"/>
        <span class="errors">{{ $errors['confirm_password'][0] ?? '' }}</span>
    </p>
    <p>
        <button type="submit">Reset</button>
    </p>
</form>
</body>
</html>