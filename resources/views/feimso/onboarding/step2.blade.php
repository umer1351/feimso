<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Your Profile — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family:'Inter',sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin:0;background:#f9fafb;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:40px 16px; }
.fm-card { background:#fff;border:1px solid var(--fm-border);border-radius:16px;padding:44px 48px;max-width:520px;width:100%; }
.fm-logo { background:var(--fm-dark);color:#fff;font-weight:700;font-size:15px;padding:5px 13px;border-radius:4px;text-decoration:none;display:inline-block;margin-bottom:28px; }
.fm-step-bar { display:flex;gap:4px;margin-bottom:28px; }
.fm-step { flex:1;height:4px;border-radius:2px;background:#e5e7eb; }
.fm-step.done,.fm-step.active { background:var(--fm-dark); }
.fm-title { font-size:22px;font-weight:700;margin-bottom:4px; }
.fm-sub { font-size:14px;color:var(--fm-muted);margin-bottom:32px; }
.fm-label { font-size:13px;font-weight:500;margin-bottom:5px;display:block; }
.fm-input { display:block;width:100%;padding:10px 14px;border:1px solid var(--fm-border);border-radius:8px;font-size:14px;outline:none;transition:border-color .15s; }
.fm-input:focus { border-color:var(--fm-dark); }
.fm-photo-wrap { display:flex;flex-direction:column;align-items:center;margin-bottom:28px; }
.fm-photo-btn {
    width:96px;height:96px;border-radius:50%;
    border:2px dashed var(--fm-border);
    display:flex;align-items:center;justify-content:center;
    cursor:pointer;background:#f9fafb;overflow:hidden;position:relative;
}
.fm-photo-btn img { position:absolute;inset:0;width:100%;height:100%;object-fit:cover; }
.fm-photo-btn:hover { border-color:#9ca3af; }
.fm-photo-label { font-size:12px;color:var(--fm-muted);margin-top:8px; }
.fm-username-wrap { position:relative; }
.fm-username-status { position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:12px; }
.fm-char-count { font-size:12px;color:var(--fm-muted);text-align:right;margin-top:4px; }
.fm-btn { display:block;width:100%;padding:12px;background:var(--fm-dark);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;margin-top:24px;transition:opacity .15s; }
.fm-btn:hover { opacity:.88; }
.fm-skip { display:block;text-align:center;margin-top:14px;font-size:13px;color:var(--fm-muted);text-decoration:none; }
.fm-skip:hover { color:var(--fm-dark); }
.invalid-feedback { display:block;font-size:12px;color:#dc2626;margin-top:4px; }
</style>
</head>
<body>
<div class="fm-card">
    <a href="{{ route('feimso.feed') }}" class="fm-logo">Feimso</a>
    <div class="fm-step-bar">
        <div class="fm-step done"></div>
        <div class="fm-step active"></div>
    </div>
    <div class="fm-title">Your profile</div>
    <div class="fm-sub">Step 2 of 2 — Add a photo and username so others can find you</div>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('feimso.onboarding.step2.submit') }}" enctype="multipart/form-data">
        @csrf
        <div class="fm-photo-wrap">
            <label for="photo-input" class="fm-photo-btn" id="photo-preview">
                <svg width="28" height="28" fill="none" stroke="#9ca3af" stroke-width="2" viewBox="0 0 24 24"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </label>
            <input type="file" id="photo-input" name="profile_photo" accept="image/*" style="display:none">
            <div class="fm-photo-label">Upload profile photo</div>
        </div>

        <div class="mb-3">
            <label class="fm-label">Username</label>
            <div class="fm-username-wrap">
                <input type="text" name="username" id="username-input" class="fm-input @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" placeholder="@yourname" maxlength="30"
                       style="padding-right:100px">
                <span class="fm-username-status" id="username-status"></span>
            </div>
            @error('username')<span class="invalid-feedback">{{ $message }}</span>@enderror
        </div>

        <div class="mb-3">
            <label class="fm-label">Bio <span style="font-weight:400;color:var(--fm-muted)">(optional)</span></label>
            <textarea name="bio" id="bio-input" class="fm-input" rows="3"
                      maxlength="200" placeholder="Tell others a bit about yourself and your style…"
                      style="resize:vertical">{{ old('bio') }}</textarea>
            <div class="fm-char-count"><span id="bio-count">0</span>/200</div>
        </div>

        <button type="submit" class="fm-btn">Finish setup</button>
    </form>
    <a href="{{ route('feimso.feed') }}" class="fm-skip">Skip for now</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Photo preview
document.getElementById('photo-input').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        const preview = document.getElementById('photo-preview');
        let img = preview.querySelector('img');
        if (!img) { img = document.createElement('img'); preview.appendChild(img); }
        img.src = e.target.result;
        preview.querySelector('svg')?.remove();
    };
    reader.readAsDataURL(file);
});

// Bio char count
const bioInput = document.getElementById('bio-input');
const bioCount = document.getElementById('bio-count');
bioInput.addEventListener('input', () => bioCount.textContent = bioInput.value.length);

// Username availability check
let usernameTimer;
document.getElementById('username-input').addEventListener('input', function() {
    clearTimeout(usernameTimer);
    const status = document.getElementById('username-status');
    const val = this.value.trim();
    if (!val) { status.textContent = ''; return; }
    status.textContent = '…';
    status.style.color = '#9ca3af';
    usernameTimer = setTimeout(() => {
        fetch('/feimso/check-username?username=' + encodeURIComponent(val), {
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        })
        .then(r => r.json())
        .then(data => {
            if (data.available) {
                status.textContent = '✓ Available';
                status.style.color = '#16a34a';
            } else {
                status.textContent = '✗ Taken';
                status.style.color = '#dc2626';
            }
        })
        .catch(() => { status.textContent = ''; });
    }, 600);
});
</script>
</body>
</html>
