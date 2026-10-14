<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Personal Information — Feimso</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
<style>
* { font-family:'Inter',sans-serif; }
:root { --fm-dark:#111827; --fm-muted:#6b7280; --fm-border:#e5e7eb; }
body { margin:0;background:#f9fafb;display:flex;align-items:center;justify-content:center;min-height:100vh;padding:40px 16px; }
.fm-card { background:#fff;border:1px solid var(--fm-border);border-radius:16px;padding:44px 48px;max-width:560px;width:100%; }
.fm-logo { background:var(--fm-dark);color:#fff;font-weight:700;font-size:15px;padding:5px 13px;border-radius:4px;text-decoration:none;display:inline-block;margin-bottom:28px; }
.fm-step-bar { display:flex;gap:4px;margin-bottom:28px; }
.fm-step { flex:1;height:4px;border-radius:2px;background:#e5e7eb; }
.fm-step.active { background:var(--fm-dark); }
.fm-title { font-size:22px;font-weight:700;margin-bottom:4px; }
.fm-sub { font-size:14px;color:var(--fm-muted);margin-bottom:28px; }
.fm-label { font-size:13px;font-weight:500;margin-bottom:5px;display:block; }
.fm-input { display:block;width:100%;padding:10px 14px;border:1px solid var(--fm-border);border-radius:8px;font-size:14px;outline:none;transition:border-color .15s; }
.fm-input:focus { border-color:var(--fm-dark); }
.fm-select { display:block;width:100%;padding:10px 14px;border:1px solid var(--fm-border);border-radius:8px;font-size:14px;outline:none;background:#fff;cursor:pointer; }
.fm-btn { display:block;width:100%;padding:12px;background:var(--fm-dark);color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer;transition:opacity .15s; }
.fm-btn:hover { opacity:.88; }
.fm-interest-grid { display:flex;flex-wrap:wrap;gap:8px; }
.fm-chip { padding:7px 14px;border:1px solid var(--fm-border);border-radius:20px;font-size:13px;cursor:pointer;user-select:none;transition:background .15s,border-color .15s; }
.fm-chip:hover { border-color:#9ca3af; }
.fm-chip.selected,.fm-chip input:checked ~ span { background:var(--fm-dark);color:#fff;border-color:var(--fm-dark); }
.fm-chip input { display:none; }
.invalid-feedback { display:block;font-size:12px;color:#dc2626;margin-top:4px; }
</style>
</head>
<body>
<div class="fm-card">
    <a href="{{ route('feimso.feed') }}" class="fm-logo">Feimso</a>
    <div class="fm-step-bar">
        <div class="fm-step active"></div>
        <div class="fm-step"></div>
    </div>
    <div class="fm-title">Personal Information</div>
    <div class="fm-sub">Step 1 of 2 — Tell us a bit about yourself</div>

    @if($errors->any())
        <div class="alert alert-danger py-2 mb-3" style="font-size:13px">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('feimso.onboarding.step1.submit') }}">
        @csrf
        <div class="row g-3 mb-3">
            <div class="col-6">
                <label class="fm-label">First name</label>
                <input type="text" name="firstName" class="fm-input"
                       value="{{ old('firstName', auth()->user()->firstName ?? '') }}" required placeholder="Jane">
            </div>
            <div class="col-6">
                <label class="fm-label">Last name</label>
                <input type="text" name="lastName" class="fm-input"
                       value="{{ old('lastName', auth()->user()->lastName ?? '') }}" required placeholder="Smith">
            </div>
        </div>
        <div class="mb-3">
            <label class="fm-label">Address</label>
            <input type="text" name="address" id="address-input" class="fm-input"
                   value="{{ old('address') }}" placeholder="Start typing your address…" autocomplete="off">
        </div>
        <div class="mb-3">
            <label class="fm-label">Phone number</label>
            <input type="tel" name="phone" class="fm-input"
                   value="{{ old('phone', auth()->user()->phoneNumber ?? '') }}" placeholder="+971 5X XXX XXXX">
        </div>
        <div class="mb-3">
            <label class="fm-label">Clothing size</label>
            <select name="clothing_size" class="fm-select">
                <option value="">Select your size…</option>
                @foreach(['XS','S','M','L','XL','XXL','Plus Size','Petite','Tall'] as $sz)
                    <option value="{{ $sz }}" {{ old('clothing_size')==$sz?'selected':'' }}>{{ $sz }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="fm-label">Interests</label>
            <div class="fm-interest-grid" id="interests-grid">
                @foreach(['Womenswear','Menswear','Childrenswear','Experiences','Accessories','Shoes','Bags','Luxury','Vintage','Activewear','Streetwear'] as $interest)
                    <label class="fm-chip @if(in_array($interest, (array)old('interests',[]))) selected @endif">
                        <input type="checkbox" name="interests[]" value="{{ $interest }}"
                               {{ in_array($interest,(array)old('interests',[])) ? 'checked' : '' }}>
                        <span>{{ $interest }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="fm-btn">Continue →</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.fm-chip').forEach(chip => {
    chip.addEventListener('click', function() {
        const input = this.querySelector('input');
        this.classList.toggle('selected', input.checked);
    });
});
</script>
</body>
</html>
