<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Feimso') — Feimso</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
@if(app()->getLocale() === 'ar')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css">
@else
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
@endif
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.css">
<style>
:root {
    --fm-brand: #c9973a;
    --fm-dark:  #111827;
    --fm-muted: #6b7280;
    --fm-border:#e5e7eb;
    --fm-bg:    #f9fafb;
    --fm-card:  #fff;
}
* { font-family: 'Inter', sans-serif; }
body { background: var(--fm-bg); }

/* ── Navbar ─────────────────────────────────────────────────── */
.fm-nav {
    background: var(--fm-dark);
    padding: 0 24px;
    height: 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: sticky;
    top: 0;
    z-index: 100;
}
.fm-logo {
    background: #fff;
    color: var(--fm-dark);
    font-weight: 700;
    font-size: 14px;
    padding: 6px 14px;
    border-radius: 4px;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
}
.fm-nav-icons {
    display: flex;
    align-items: center;
    gap: 4px;
    color: #9ca3af;
    flex-shrink: 0;
}
.fm-nav-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: #9ca3af;
    text-decoration: none;
    transition: background .15s, color .15s;
}
.fm-nav-icon:hover { background: rgba(255,255,255,.08); color: #fff; }
.fm-search {
    flex: 1;
    max-width: 480px;
    margin: 0 8px;
}
.fm-search-input {
    width: 100%;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.12);
    border-radius: 24px;
    padding: 7px 16px 7px 40px;
    color: #e5e7eb;
    font-size: 13px;
    outline: none;
    transition: border-color .15s;
}
.fm-search-input::placeholder { color: #6b7280; }
.fm-search-input:focus { border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.1); }
.fm-search-wrap { position: relative; }
.fm-search-wrap .si {
    position: absolute;
    top: 50%;
    left: 12px;
    transform: translateY(-50%);
    color: #6b7280;
    width: 15px;
    height: 15px;
}
.fm-nav-add {
    width: 30px;
    height: 30px;
    background: rgba(255,255,255,.15);
    border-radius: 50%;
    border: none;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    text-decoration: none;
}
.fm-nav-add:hover { background: rgba(255,255,255,.25); color: #fff; }
.fm-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255,255,255,.2);
    background: #374151;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    font-size: 13px;
    text-decoration: none;
    flex-shrink: 0;
}
.fm-spacer { flex: 1; }

/* ── Tab bar ─────────────────────────────────────────────────── */
.fm-tabs {
    background: #fff;
    border-bottom: 1px solid var(--fm-border);
    display: flex;
    gap: 0;
    padding: 0 24px;
    overflow-x: auto;
}
.fm-tab {
    padding: 14px 20px;
    font-size: 14px;
    font-weight: 500;
    color: var(--fm-muted);
    text-decoration: none;
    border-bottom: 2px solid transparent;
    white-space: nowrap;
    transition: color .15s, border-color .15s;
}
.fm-tab:hover { color: var(--fm-dark); }
.fm-tab.active { color: var(--fm-dark); border-bottom-color: var(--fm-dark); }

/* ── Notification dropdown ───────────────────────────────────── */
.fm-notif-dropdown {
    position: absolute;
    top: 56px;
    right: 0;
    width: 360px;
    background: #fff;
    border: 1px solid var(--fm-border);
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,.12);
    z-index: 200;
    display: none;
}
.fm-notif-dropdown.show { display: block; }
.fm-notif-header { padding: 16px 20px 8px; font-weight: 600; font-size: 15px; }
.fm-notif-group { padding: 4px 0 8px; }
.fm-notif-group-label { padding: 4px 20px; font-size: 11px; font-weight: 600; color: var(--fm-muted); text-transform: uppercase; letter-spacing: .05em; }
.fm-notif-item { display: flex; align-items: center; gap: 12px; padding: 10px 20px; cursor: pointer; }
.fm-notif-item:hover { background: #f9fafb; }
.fm-notif-avatar { width: 38px; height: 38px; border-radius: 50%; background: #e5e7eb; flex-shrink: 0; }
.fm-notif-body { flex: 1; font-size: 13px; line-height: 1.4; }
.fm-notif-body strong { font-weight: 600; }
.fm-notif-time { font-size: 11px; color: var(--fm-muted); margin-top: 2px; }
.fm-notif-more { width: 28px; height: 28px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; border-radius: 50%; cursor: pointer; color: var(--fm-muted); }
.fm-notif-more:hover { background: #f3f4f6; }

/* ── Listing card ────────────────────────────────────────────── */
.fm-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
    border: 1px solid var(--fm-border);
    transition: box-shadow .15s;
}
.fm-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }
.fm-card-img {
    width: 100%;
    aspect-ratio: 4/5;
    object-fit: cover;
    background: #f3f4f6 url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='40' height='40' viewBox='0 0 40 40'%3E%3Cline x1='0' y1='0' x2='40' y2='40' stroke='%23d1d5db' stroke-width='1'/%3E%3Cline x1='40' y1='0' x2='0' y2='40' stroke='%23d1d5db' stroke-width='1'/%3E%3C/svg%3E") center/cover;
}
.fm-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #111827;
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 4px;
}
.fm-card-body { padding: 12px; }
.fm-card-price { font-size: 15px; font-weight: 600; }
.fm-card-title { font-size: 13px; color: var(--fm-dark); margin-top: 2px; }
.fm-card-sub { font-size: 12px; color: var(--fm-muted); margin-top: 2px; }
.fm-card-meta { display: flex; align-items: center; justify-content: space-between; margin-top: 6px; }
.fm-card-rating { font-size: 12px; color: var(--fm-muted); }
.fm-heart { width: 28px; height: 28px; display: flex; align-items: center; justify-content: center; border: none; background: none; cursor: pointer; border-radius: 50%; }
.fm-heart:hover { background: #fef2f2; }

/* ── Filter sidebar ──────────────────────────────────────────── */
.fm-filter {
    background: #fff;
    border: 1px solid var(--fm-border);
    border-radius: 12px;
    padding: 20px;
}
.fm-filter-title { font-size: 15px; font-weight: 600; margin-bottom: 16px; }
.fm-filter-section { border-top: 1px solid var(--fm-border); padding: 14px 0; }
.fm-filter-section-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    user-select: none;
}
.fm-filter-body { margin-top: 10px; }
.fm-filter-check { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; font-size: 13px; cursor: pointer; }
.fm-filter-check input { width: 16px; height: 16px; cursor: pointer; }
.fm-select {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 13px;
    background: #fff;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

/* ── Feed cards ──────────────────────────────────────────────── */
.fm-feed-card {
    background: #fff;
    border: 1px solid var(--fm-border);
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 16px;
}
.fm-feed-img {
    width: 100%;
    aspect-ratio: 16/9;
    object-fit: cover;
    background: #f3f4f6;
}
.fm-feed-body { padding: 16px; }
.fm-feed-user { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
.fm-feed-avatar { width: 36px; height: 36px; border-radius: 50%; background: #e5e7eb; }
.fm-feed-username { font-size: 13px; font-weight: 600; }
.fm-feed-location { font-size: 12px; color: var(--fm-muted); }
.fm-feed-actions { display: flex; align-items: center; gap: 16px; color: var(--fm-muted); font-size: 13px; }
.fm-feed-action { display: flex; align-items: center; gap: 5px; cursor: pointer; text-decoration: none; color: var(--fm-muted); }
.fm-feed-action:hover { color: var(--fm-dark); }

/* ── Profile sidebar ─────────────────────────────────────────── */
.fm-profile-side {
    background: #fff;
    border: 1px solid var(--fm-border);
    border-radius: 12px;
    padding: 24px;
    text-align: center;
}
.fm-profile-avatar {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    object-fit: cover;
    background: #e5e7eb;
    margin: 0 auto 12px;
}
.fm-profile-name { font-weight: 600; font-size: 16px; }
.fm-profile-handle { color: var(--fm-muted); font-size: 13px; }
.fm-profile-bio { font-size: 13px; color: #374151; margin-top: 10px; }
.fm-profile-btn {
    display: block;
    width: 100%;
    padding: 9px;
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    text-align: center;
    cursor: pointer;
    background: #fff;
    color: var(--fm-dark);
    text-decoration: none;
    margin-top: 10px;
    transition: background .15s;
}
.fm-profile-btn:hover { background: #f9fafb; }

/* ── Onboarding ──────────────────────────────────────────────── */
.fm-onboard {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
}
.fm-onboard-card {
    background: #fff;
    border: 1px solid var(--fm-border);
    border-radius: 16px;
    padding: 40px 48px;
    max-width: 560px;
    width: 100%;
}
.fm-step-bar { display: flex; gap: 4px; margin-bottom: 32px; }
.fm-step { flex: 1; height: 4px; border-radius: 2px; background: #e5e7eb; }
.fm-step.done { background: var(--fm-dark); }
.fm-step.active { background: var(--fm-dark); }
.fm-onboard-title { font-size: 22px; font-weight: 700; margin-bottom: 6px; }
.fm-onboard-sub { font-size: 14px; color: var(--fm-muted); margin-bottom: 28px; }
.fm-label { font-size: 13px; font-weight: 500; margin-bottom: 6px; display: block; }
.fm-input {
    display: block;
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    transition: border-color .15s;
}
.fm-input:focus { border-color: var(--fm-dark); }
.fm-btn {
    display: block;
    width: 100%;
    padding: 12px;
    background: var(--fm-dark);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: opacity .15s;
}
.fm-btn:hover { opacity: .88; color: #fff; }
.fm-btn-outline {
    display: block;
    width: 100%;
    padding: 12px;
    background: transparent;
    color: var(--fm-dark);
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-align: center;
    text-decoration: none;
    transition: background .15s;
}
.fm-btn-outline:hover { background: #f9fafb; color: var(--fm-dark); }
.fm-interest-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.fm-interest-chip {
    padding: 7px 14px;
    border: 1px solid var(--fm-border);
    border-radius: 20px;
    font-size: 13px;
    cursor: pointer;
    user-select: none;
    transition: background .15s, border-color .15s;
}
.fm-interest-chip.selected, .fm-interest-chip input:checked + span {
    background: var(--fm-dark);
    color: #fff;
    border-color: var(--fm-dark);
}
.fm-interest-chip input { display: none; }
.fm-photo-upload {
    width: 96px;
    height: 96px;
    border-radius: 50%;
    border: 2px dashed var(--fm-border);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    margin: 0 auto 16px;
    overflow: hidden;
    background: #f9fafb;
    position: relative;
}
.fm-photo-upload img { width: 100%; height: 100%; object-fit: cover; }
.fm-char-count { font-size: 12px; color: var(--fm-muted); text-align: right; margin-top: 4px; }

/* ── Auth pages ──────────────────────────────────────────────── */
.fm-auth-wrap { min-height: 100vh; display: flex; }
.fm-auth-hero {
    flex: 1;
    background: var(--fm-dark);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px;
    color: #fff;
}
.fm-auth-hero-logo {
    background: #fff;
    color: var(--fm-dark);
    font-weight: 700;
    font-size: 18px;
    padding: 8px 18px;
    border-radius: 6px;
    margin-bottom: 40px;
}
.fm-auth-hero h1 { font-size: 32px; font-weight: 700; line-height: 1.25; }
.fm-auth-hero p { font-size: 16px; color: #9ca3af; margin-top: 12px; }
.fm-auth-form {
    width: 480px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 48px;
    background: #fff;
    overflow-y: auto;
}
.fm-auth-title { font-size: 24px; font-weight: 700; margin-bottom: 6px; }
.fm-auth-sub { font-size: 14px; color: var(--fm-muted); margin-bottom: 28px; }
.fm-social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 11px;
    border: 1px solid var(--fm-border);
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    background: #fff;
    color: var(--fm-dark);
    text-decoration: none;
    transition: background .15s;
}
.fm-social-btn:hover { background: #f9fafb; color: var(--fm-dark); }
.fm-divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: var(--fm-muted); font-size: 12px; }
.fm-divider::before, .fm-divider::after { content: ''; flex: 1; height: 1px; background: var(--fm-border); }
.fm-link { color: var(--fm-dark); font-weight: 600; }

/* ── Utility ─────────────────────────────────────────────────── */
.fm-page { padding: 24px; }
.fm-section-title { font-size: 16px; font-weight: 700; margin-bottom: 16px; }
.fm-section-head { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.fm-see-all { font-size: 13px; color: var(--fm-muted); text-decoration: none; }
.fm-see-all:hover { color: var(--fm-dark); }
@media (max-width: 767px) {
    .fm-auth-hero { display: none; }
    .fm-auth-form { width: 100%; padding: 32px 24px; }
    .fm-page { padding: 16px; }
}
</style>
@stack('head')
</head>
<body>

{{-- ── Navbar ──────────────────────────────────────────────────── --}}
<nav class="fm-nav">
    <a href="{{ route('feimso.feed') }}" class="fm-logo">Feimso</a>

    <div class="fm-nav-icons">
        <a href="{{ route('feimso.feed') }}" class="fm-nav-icon" title="Home">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </a>
        <a href="{{ route('feimso.marketplace') }}" class="fm-nav-icon" title="Marketplace">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
        </a>
    </div>

    <div class="fm-search">
        <div class="fm-search-wrap">
            <svg class="si" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="search" class="fm-search-input" placeholder="Search Feimso..." id="fm-search-input">
        </div>
    </div>

    @auth
    <a href="{{ route('feimso.listing.create') }}" class="fm-nav-add" title="Create listing">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    </a>
    <a href="{{ route('feimso.marketplace') }}" class="fm-nav-icon" title="Cart">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
    </a>
    <a href="#" class="fm-nav-icon" title="Messages">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
    </a>
    <div style="position:relative">
        <button class="fm-nav-icon" title="Notifications" id="fm-notif-btn" style="border:none;cursor:pointer">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
        </button>
        <div class="fm-notif-dropdown" id="fm-notif-dropdown">
            <div class="fm-notif-header">Notifications</div>
            <div class="fm-notif-group">
                <div class="fm-notif-group-label">Today</div>
                <div class="fm-notif-item">
                    <div class="fm-notif-avatar"></div>
                    <div class="fm-notif-body"><strong>Mike Taylor</strong> commented on your lookbook post.<div class="fm-notif-time">20s</div></div>
                    <div class="fm-notif-more">⋯</div>
                </div>
                <div class="fm-notif-item">
                    <div class="fm-notif-avatar"></div>
                    <div class="fm-notif-body"><strong>Kirsty Whyte</strong> asked a question on <strong>Chanel Vintage Woollen Tweed Blazer</strong>.<div class="fm-notif-time">2h</div></div>
                    <div class="fm-notif-more">⋯</div>
                </div>
            </div>
            <div class="fm-notif-group">
                <div class="fm-notif-group-label">Earlier</div>
                <div class="fm-notif-item">
                    <div class="fm-notif-avatar"></div>
                    <div class="fm-notif-body"><strong>Marsha Permadhi</strong> liked your lookbook post.<div class="fm-notif-time">1d</div></div>
                    <div class="fm-notif-more">⋯</div>
                </div>
                <div class="fm-notif-item">
                    <div class="fm-notif-avatar"></div>
                    <div class="fm-notif-body"><strong>Hannah Tarbotton</strong> commented on <strong>Genuine Leather Black Mini Skirt</strong>.<div class="fm-notif-time">4d</div></div>
                    <div class="fm-notif-more">⋯</div>
                </div>
                <div class="fm-notif-item">
                    <div class="fm-notif-avatar"></div>
                    <div class="fm-notif-body"><strong>Ina Feimi</strong> liked your lookbook post.<div class="fm-notif-time">1w</div></div>
                    <div class="fm-notif-more">⋯</div>
                </div>
            </div>
        </div>
    </div>
    <a href="{{ route('feimso.profile') }}" class="fm-avatar" title="My profile">
        @if(auth()->user()->profilePictureURL)
            <img src="{{ auth()->user()->profilePictureURL }}" data-fm-fallback="avatar" alt="avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover">
        @else
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        @endif
    </a>
    @else
    <a href="{{ route('feimso.register') }}" class="fm-btn" style="width:auto;padding:8px 20px;font-size:13px">Sign up</a>
    <a href="{{ route('feimso.login') }}" class="fm-btn-outline" style="width:auto;padding:8px 20px;font-size:13px;border-color:rgba(255,255,255,.2);color:#fff">Sign in</a>
    @endauth
    <button class="fm-nav-icon" style="border:none;cursor:pointer">
        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
    </button>
</nav>

@yield('tabs')

<main>
@yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Notifications toggle
const notifBtn = document.getElementById('fm-notif-btn');
const notifDropdown = document.getElementById('fm-notif-dropdown');
if (notifBtn) {
    notifBtn.addEventListener('click', e => {
        e.stopPropagation();
        notifDropdown.classList.toggle('show');
    });
    document.addEventListener('click', () => notifDropdown.classList.remove('show'));
}
// Interest chips
document.querySelectorAll('.fm-interest-chip').forEach(chip => {
    chip.addEventListener('click', () => chip.classList.toggle('selected'));
});
</script>
@stack('scripts')
<script>
document.addEventListener('error',function(e){var i=e.target;if(!i||i.tagName!=='IMG'||i.dataset.fmDone)return;i.dataset.fmDone=1;
var t=i.dataset.fmFallback||'lookbook';i.src='{{ asset('img/feimso/photos') }}/fashion-'+(t==='avatar'?'08':'01')+'.jpg';},true);
</script>
</body>
</html>
