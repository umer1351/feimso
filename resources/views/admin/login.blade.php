@php($rtl = app()->getLocale() === 'ar')
<!doctype html>
<html lang="{{ app()->getLocale() }}" dir="{{ $rtl ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('admin.login') }} · {{ config('app.name') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/fav.png') }}">
    <link rel="stylesheet" href="{{ asset($rtl ? 'vendor/bootstrap/css/bootstrap-rtl.min.css' : 'vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/admin.css') }}">
</head>
<body class="admin d-flex align-items-center" style="min-height:100vh">
<div class="container" style="max-width:420px">
    <div class="text-center mb-4">
        <h3 class="mb-0">{{ config('app.name') }}</h3>
        <div class="text-muted">{{ __('admin.panel') }}</div>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <form method="post" action="{{ route('admin.login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">{{ __('admin.fields.email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="password">{{ __('admin.fields.password') }}</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" value="1">
                    <label class="form-check-label" for="remember">{{ __('admin.remember_me') }}</label>
                </div>
                <button class="btn btn-primary btn-block">{{ __('admin.login') }}</button>
            </form>
        </div>
    </div>
    <div class="text-center mt-3">
        <a href="{{ route('admin.locale', $rtl ? 'en' : 'ar') }}">{{ $rtl ? 'English' : 'العربية' }}</a>
    </div>
</div>
</body>
</html>
