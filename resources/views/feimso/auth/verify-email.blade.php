<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>You're all set! — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family:'Inter',sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin:0;background:#f9fafb;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:24px; }
.fm-card { background:#fff;border:1px solid var(--fm-border);border-radius:16px;padding:56px 48px;max-width:440px;width:100%;text-align:center; }
.fm-logo { background:var(--fm-dark);color:#fff;font-weight:700;font-size:16px;padding:6px 14px;border-radius:4px;text-decoration:none;display:inline-block;margin-bottom:32px; }
.fm-icon { width:72px;height:72px;background:#f0fdf4;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px; }
.fm-title { font-size:26px;font-weight:700;margin-bottom:10px; }
.fm-sub { font-size:14px;color:var(--fm-muted);line-height:1.6;margin-bottom:32px; }
.fm-btn { display:inline-block;padding:13px 36px;background:var(--fm-dark);color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none; }
.fm-btn:hover { opacity:.88;color:#fff; }
</style>
</head>
<body>
<div class="fm-card">
    <a href="{{ route('feimso.feed') }}" class="fm-logo">Feimso</a>
    <div class="fm-icon">
        <svg width="32" height="32" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    </div>
    <div class="fm-title">You're all set!</div>
    <div class="fm-sub">Your email has been verified and your Feimso account is ready. Let's complete your profile so buyers and sellers know who you are.</div>
    <a href="{{ route('feimso.onboarding.step1') }}" class="fm-btn">Complete your profile →</a>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
