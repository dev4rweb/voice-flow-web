<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('title', 'Admin') · Voice Flow</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
<div class="admin-shell">
    <aside class="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">Voice Flow Admin</a>
        <nav class="admin-nav" aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>Dashboard</a>
            <a href="{{ route('admin.downloads') }}" @class(['is-active' => request()->routeIs('admin.downloads')])>Downloads</a>
            <span class="is-disabled" title="Coming soon">Releases</span>
        </nav>
        <div class="admin-sidebar-footer">
            Signed in as {{ auth()->user()->email }}
        </div>
    </aside>
    <div class="admin-main">
        <div class="admin-topbar">
            <h1>@yield('heading')</h1>
            <form method="post" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit">Log out</button>
            </form>
        </div>
        @yield('content')
    </div>
</div>
</body>
</html>
