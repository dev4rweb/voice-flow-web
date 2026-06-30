<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin login · Voice Flow</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<main class="login-page">
    <div class="login-card">
        <h1>Voice Flow Admin</h1>
        <p class="muted">Sign in to manage downloads and future release notes.</p>

        <form method="post" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" required>
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" autocomplete="current-password" required>
            </div>

            <label class="field-checkbox">
                <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                Remember me
            </label>

            @if ($errors->any())
                <p class="error">{{ $errors->first() }}</p>
            @endif

            <div style="margin-top: 20px;">
                <button class="button-primary" type="submit">Sign in</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
