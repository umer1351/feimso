<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>New Password — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family:'Inter',sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin:0;background:#f9fafb;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px; }
.fm-card { background:#fff;border:1px solid var(--fm-border);border-radius:16px;padding:48px;max-width:440px;width:100%; }
.fm-logo { background:var(--fm-dark);color:#fff;font-weight:700;font-size:16px;padding:6px 14px;border-radius:4px;text-decoration:none;display:inline-block;margin-bottom:32px; }
.fm-title { font-size:22px;font-weight:700;margin-bottom:6px; }
.fm-sub { font-size:14px;color:var(--fm-muted);margin-bottom:28px;line-height:1.6; }
.fm-label { font-size:13px;font-weight:500;margin-bottom:5px;display:block; }
.fm-input { display:block;width:100%;padding:10px 14px;border:1px solid var(--fm-border);border-radius:8px;font-size:14px;outline:none;transition:border-color .15s; }
.fm-input:focus { border-color:var(--fm-dark); }
.fm-btn { display:block;width:100%;padding:12px;background:var(--fm-dark);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;margin-top:20px; }
.fm-btn:hover { opacity:.88; }
.invalid-feedback { display:block;font-size:12px;color:#dc2626;margin-top:4px; }
</style>
</head>
<body>
<div class="fm-card">
    <a href="{{ route('feimso.feed') }}" class="fm-logo">Feimso</a>
    <div class="fm-title">Set a new password</div>
    <div class="fm-sub">Your new password must be at least 8 characters long.</div>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
        <div class="mb-3">
            <label class="fm-label">New password</label>
            <input type="password" name="password" class="fm-input @error('password') is-invalid @enderror"
                   placeholder="8+ characters" required autocomplete="new-password">
            @error('password')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>
        <div class="mb-3">
            <label class="fm-label">Confirm new password</label>
            <input type="password" name="password_confirmation" class="fm-input"
                   placeholder="Repeat new password" required autocomplete="new-password">
        </div>
        <button type="submit" class="fm-btn">Update password</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
