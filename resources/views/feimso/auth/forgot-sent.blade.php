<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Check Your Email — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family:'Inter',sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin:0;background:#f9fafb;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px; }
.fm-card { background:#fff;border:1px solid var(--fm-border);border-radius:16px;padding:48px;max-width:440px;width:100%;text-align:center; }
.fm-icon { width:64px;height:64px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px; }
.fm-title { font-size:22px;font-weight:700;margin-bottom:10px; }
.fm-sub { font-size:14px;color:var(--fm-muted);line-height:1.6;margin-bottom:28px; }
.fm-btn { display:inline-block;padding:12px 32px;background:var(--fm-dark);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none; }
.fm-btn:hover { opacity:.88;color:#fff; }
.fm-back { display:block;margin-top:16px;font-size:13px;color:var(--fm-muted);text-decoration:none; }
.fm-back:hover { color:var(--fm-dark); }
</style>
</head>
<body>
<div class="fm-card">
    <div class="fm-icon">
        <svg width="28" height="28" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
    </div>
    <div class="fm-title">Check your email</div>
    <div class="fm-sub">
        We've sent a password reset link to <strong>{{ session('reset_email', 'your email') }}</strong>.
        It may take a minute to arrive — also check your spam folder.
    </div>
    <a href="{{ route('feimso.login') }}" class="fm-btn">Back to sign in</a>
    <a href="{{ route('feimso.forgot') }}" class="fm-back">Didn't receive it? Send again</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
