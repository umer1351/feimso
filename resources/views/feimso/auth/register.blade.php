<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Sign Up — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family: 'Inter', sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin: 0; }
.fm-auth-wrap { min-height: 100vh; display: flex; }
.fm-auth-hero {
    flex: 1;
    background: var(--fm-dark);
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 60px 56px;
    color: #fff;
}
.fm-auth-hero-logo {
    background: #fff;
    color: var(--fm-dark);
    font-weight: 700;
    font-size: 18px;
    padding: 8px 18px;
    border-radius: 6px;
    display: inline-block;
    margin-bottom: 48px;
    text-decoration: none;
}
.fm-auth-hero h1 { font-size: 36px; font-weight: 700; line-height: 1.2; }
.fm-auth-hero p { font-size: 16px; color: #9ca3af; margin-top: 14px; max-width: 360px; line-height: 1.6; }
.fm-auth-form {
    width: 520px;
    flex-shrink: 0;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px 52px;
    overflow-y: auto;
}
.fm-title { font-size: 24px; font-weight: 700; margin-bottom: 6px; }
.fm-sub { font-size: 14px; color: var(--fm-muted); margin-bottom: 28px; }
.fm-label { font-size: 13px; font-weight: 500; margin-bottom: 5px; display: block; }
.fm-input {
    display: block; width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border-color .15s;
}
.fm-input:focus { border-color: var(--fm-dark); }
.fm-input.is-invalid { border-color: #dc2626; }
.fm-btn {
    display: block; width: 100%; padding: 12px;
    background: var(--fm-dark); color: #fff;
    border: none; border-radius: 8px;
    font-size: 14px; font-weight: 600;
    cursor: pointer; transition: opacity .15s;
}
.fm-btn:hover { opacity: .88; }
.fm-social-btn {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    width: 100%; padding: 11px;
    border: 1px solid var(--fm-border); border-radius: 8px;
    font-size: 14px; font-weight: 500;
    cursor: pointer; background: #fff; color: var(--fm-dark);
    text-decoration: none; transition: background .15s;
}
.fm-social-btn:hover { background: #f9fafb; color: var(--fm-dark); }
.fm-divider { display: flex; align-items: center; gap: 12px; margin: 18px 0; color: var(--fm-muted); font-size: 12px; }
.fm-divider::before, .fm-divider::after { content:''; flex:1; height:1px; background: var(--fm-border); }
.fm-link { color: var(--fm-dark); font-weight: 600; }
.invalid-feedback { display: block; font-size: 12px; color: #dc2626; margin-top: 4px; }
@media (max-width: 767px) {
    .fm-auth-hero { display: none; }
    .fm-auth-form { width: 100%; padding: 32px 24px; }
}
</style>
</head>
<body>
<div class="fm-auth-wrap">
    {{-- Hero --}}
    <div class="fm-auth-hero">
        <a href="{{ route('feimso.feed') }}" class="fm-auth-hero-logo">Feimso</a>
        <h1>UAE's Premier Fashion Marketplace</h1>
        <p>Buy, sell, rent and experience fashion — from luxury preloved to emerging designers.</p>
    </div>

    {{-- Form --}}
    <div class="fm-auth-form">
        <div class="fm-title">Create your account</div>
        <div class="fm-sub">Already have an account? <a href="{{ route('feimso.login') }}" class="fm-link">Sign in</a></div>

        @if($errors->any())
            <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('feimso.register.submit') }}" id="register-form">
            @csrf
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">First name</label>
                    <input type="text" name="firstName" class="fm-input @error('firstName') is-invalid @enderror"
                           value="{{ old('firstName') }}" placeholder="Jane" required>
                    @error('firstName')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-6">
                    <label class="fm-label">Last name</label>
                    <input type="text" name="lastName" class="fm-input @error('lastName') is-invalid @enderror"
                           value="{{ old('lastName') }}" placeholder="Smith" required>
                    @error('lastName')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
            <div class="mb-3">
                <label class="fm-label">Email address</label>
                <input type="email" name="email" class="fm-input @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="jane@email.com" required autocomplete="email">
                @error('email')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">Password</label>
                    <input type="password" name="password" class="fm-input @error('password') is-invalid @enderror"
                           placeholder="8+ characters" required autocomplete="new-password">
                    @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
                <div class="col-6">
                    <label class="fm-label">Confirm password</label>
                    <input type="password" name="password_confirmation" class="fm-input"
                           placeholder="Repeat password" required autocomplete="new-password">
                </div>
            </div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="fm-label">Date of birth</label>
                    <input type="date" name="dob" class="fm-input" value="{{ old('dob') }}">
                </div>
                <div class="col-6">
                    <label class="fm-label">Gender</label>
                    <select name="gender" class="fm-input">
                        <option value="">Select…</option>
                        <option value="female" {{ old('gender')=='female'?'selected':'' }}>Female</option>
                        <option value="male" {{ old('gender')=='male'?'selected':'' }}>Male</option>
                        <option value="non_binary" {{ old('gender')=='non_binary'?'selected':'' }}>Non-binary</option>
                        <option value="prefer_not" {{ old('gender')=='prefer_not'?'selected':'' }}>Prefer not to say</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="fm-label">I am a…</label>
                <select name="feimso_role" class="fm-input @error('feimso_role') is-invalid @enderror">
                    <option value="buyer" {{ old('feimso_role','buyer')=='buyer'?'selected':'' }}>Buyer / Shopper</option>
                    <option value="individual_seller" {{ old('feimso_role')=='individual_seller'?'selected':'' }}>Individual Seller</option>
                    <option value="licensed_creator" {{ old('feimso_role')=='licensed_creator'?'selected':'' }}>Licensed Creator</option>
                    <option value="business" {{ old('feimso_role')=='business'?'selected':'' }}>Business / Brand</option>
                    <option value="advertising_creator" {{ old('feimso_role')=='advertising_creator'?'selected':'' }}>Advertising Creator</option>
                </select>
                @error('feimso_role')<span class="invalid-feedback">{{ $message }}</span>@enderror
            </div>
            @if(config('feimso.invite_required', false))
            <div class="mb-3">
                <label class="fm-label">Invite code <small class="text-muted">(if you have one)</small></label>
                <input type="text" name="invite_code" class="fm-input" value="{{ old('invite_code', request('invite')) }}" placeholder="FEIMSO-XXXX">
            </div>
            @endif
            <div class="mb-4">
                <label style="display:flex;align-items:flex-start;gap:8px;font-size:13px;cursor:pointer">
                    <input type="checkbox" name="agree_terms" required style="margin-top:3px">
                    <span>I agree to the <a href="{{ route('terms') }}" class="fm-link" target="_blank">Terms of Service</a> and <a href="{{ route('privacy') }}" class="fm-link" target="_blank">Privacy Policy</a></span>
                </label>
            </div>
            <button type="submit" class="fm-btn mb-4">Create account</button>
        </form>

        <div class="fm-divider">or sign up with</div>
        <div class="d-flex flex-column gap-2">
            <a href="#" class="fm-social-btn">
                <svg width="18" height="18" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                Continue with Google
            </a>
            <a href="#" class="fm-social-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                Continue with Facebook
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
