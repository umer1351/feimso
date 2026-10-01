@php($rtl = app()->getLocale() === 'ar')
<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('admin.dashboard')) · {{ __('admin.panel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/fav.png') }}">
    <link rel="stylesheet" href="{{ asset($rtl ? 'vendor/bootstrap/css/bootstrap-rtl.min.css' : 'vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/icons/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/admin.css') }}">
</head>
<body class="admin">
<div class="admin-shell">
    <nav class="admin-side">
        <a class="brand" href="{{ route('admin.dashboard') }}">{{ config('app.name') }} <small class="text-muted">{{ __('admin.panel') }}</small></a>
        <a class="item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="feather-home"></i> {{ __('admin.dashboard') }}</a>
        @foreach(config('admin.groups') as $group => $names)
            <div class="group">{{ __("admin.groups.$group") }}</div>
            @foreach($names as $n)
                <a class="item {{ request()->route('name') === $n ? 'active' : '' }}" href="{{ route('admin.resource.index', $n) }}">
                    <i class="feather-{{ config("admin.resources.$n.icon", 'circle') }}"></i> {{ __("admin.resources.$n") }}
                </a>
            @endforeach
        @endforeach
        <div class="group">{{ __('admin.settings') }}</div>
        @foreach(config('admin_settings') as $tabKey => $tabDef)
            <a class="item {{ request()->routeIs('admin.settings') && request()->route('tab', 'general') === $tabKey ? 'active' : '' }}" href="{{ route('admin.settings', $tabKey) }}">
                <i class="feather-{{ $tabDef['icon'] }}"></i> {{ __("admin.settings_tabs.$tabKey") }}
            </a>
        @endforeach
        <div class="pb-4"></div>
    </nav>

    <div class="admin-main">
        <header class="admin-top">
            <div class="d-flex align-items-center" style="gap:10px">
                <button class="btn btn-sm btn-light side-toggle" type="button" onclick="document.body.classList.toggle('side-open')"><i class="feather-menu"></i></button>
                <h1>@yield('title', __('admin.dashboard'))</h1>
            </div>
            <div class="d-flex align-items-center" style="gap:8px">
                <a class="btn btn-sm btn-light" href="{{ url('/') }}" target="_blank"><i class="feather-external-link"></i> {{ __('admin.view_site') }}</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.locale', $rtl ? 'en' : 'ar') }}">{{ $rtl ? 'English' : 'العربية' }}</a>
                <span class="text-muted d-none d-md-inline">{{ auth()->user()->email }}</span>
                <form method="post" action="{{ route('admin.logout') }}" class="m-0">@csrf
                    <button class="btn btn-sm btn-outline-danger"><i class="feather-log-out"></i> {{ __('admin.logout') }}</button>
                </form>
            </div>
        </header>

        <main class="admin-content">
            @if(session('ok'))
                <div class="alert alert-success">{{ session('ok') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>{{ __('admin.fix_errors') }}</strong>
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset($rtl ? 'vendor/bootstrap/js/bootstrap-rtl.bundle.min.js' : 'vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
@stack('scripts')
</body>
</html>
