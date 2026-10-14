@extends('feimso.layout')
@section('title', ($profile['username'] ?? 'Profile'))

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row g-4">

        {{-- ── Left sidebar ──────────────────────────────── --}}
        <div class="col-lg-3 col-xl-2">
            <div class="fm-profile-side">
                @if(!empty($profile['profilePictureURL']))
                    <img src="{{ $profile['profilePictureURL'] }}" data-fm-fallback="avatar" alt="avatar" class="fm-profile-avatar">
                @else
                    <div class="fm-profile-avatar" style="display:flex;align-items:center;justify-content:center">
                        <svg width="40" height="40" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                @endif
                <div class="fm-profile-name">{{ $profile['firstName'] ?? '' }} {{ $profile['lastName'] ?? '' }}</div>
                @if(!empty($profile['username']))
                    <div class="fm-profile-handle">@{{ $profile['username'] }}</div>
                @endif
                @if(!empty($profile['location']))
                    <div style="font-size:12px;color:var(--fm-muted);margin-top:4px">📍 {{ $profile['location'] }}</div>
                @endif
                @if(!empty($profile['bio']))
                    <div class="fm-profile-bio">{{ $profile['bio'] }}</div>
                @endif

                @auth
                @if(auth()->id() == ($profile['id'] ?? null))
                <a href="{{ route('feimso.profile.edit') }}" class="fm-profile-btn" style="margin-top:16px">Edit profile</a>
                <a href="{{ route('feimso.listing.create') }}" class="fm-profile-btn" style="background:#111827;color:#fff;border-color:#111827">+ Create listing</a>
                @else
                <a href="#" class="fm-profile-btn" style="background:#111827;color:#fff;border-color:#111827;margin-top:16px">Follow</a>
                <a href="#" class="fm-profile-btn">Message</a>
                @endif
                @else
                <a href="{{ route('feimso.login') }}" class="fm-profile-btn" style="background:#111827;color:#fff;border-color:#111827;margin-top:16px">Sign in to follow</a>
                @endauth

                @if(!empty($profile['feimso_role']) && $profile['feimso_role'] !== 'buyer')
                <div style="margin-top:14px;padding-top:14px;border-top:1px solid var(--fm-border)">
                    <div style="font-size:11px;text-transform:uppercase;letter-spacing:.05em;color:var(--fm-muted);margin-bottom:8px">Seller stats</div>
                    <div style="display:flex;justify-content:space-around;text-align:center">
                        <div>
                            <div style="font-size:16px;font-weight:700">{{ $stats['listings'] ?? 0 }}</div>
                            <div style="font-size:11px;color:var(--fm-muted)">Listings</div>
                        </div>
                        <div>
                            <div style="font-size:16px;font-weight:700">{{ $stats['sold'] ?? 0 }}</div>
                            <div style="font-size:11px;color:var(--fm-muted)">Sold</div>
                        </div>
                        <div>
                            <div style="font-size:16px;font-weight:700">★ {{ $stats['rating'] ?? '—' }}</div>
                            <div style="font-size:11px;color:var(--fm-muted)">Rating</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Main content ────────────────────────────────── --}}
        <div class="col-lg-9 col-xl-10">
            {{-- Tab bar --}}
            <div style="border-bottom:1px solid var(--fm-border);display:flex;gap:0;margin-bottom:24px">
                <a href="{{ route('feimso.profile.show', $profile['id'] ?? auth()->id()) }}"
                   class="fm-tab {{ !request('tab') ? 'active' : '' }}">My Lookbook</a>
                <a href="{{ route('feimso.profile.show', $profile['id'] ?? auth()->id(), ['tab'=>'closet']) }}"
                   class="fm-tab {{ request('tab')==='closet' ? 'active' : '' }}">My Closet</a>
                <a href="{{ route('feimso.profile.show', $profile['id'] ?? auth()->id(), ['tab'=>'experiences']) }}"
                   class="fm-tab {{ request('tab')==='experiences' ? 'active' : '' }}">My Experiences</a>
            </div>

            @if(!request('tab'))
            {{-- Lookbook --}}
            @if(!empty($lookbookPosts))
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px">
                @foreach($lookbookPosts as $post)
                <div style="border-radius:10px;overflow:hidden;background:#f3f4f6;aspect-ratio:1;cursor:pointer;position:relative;display:flex;align-items:center;justify-content:center">
                    <img src="{{ \App\Helpers\FeimsoImg::url($post['image'] ?? null, 'lookbook', $post['id'] ?? $loop->index) }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0" alt="">
                    <div style="position:absolute;bottom:0;left:0;right:0;background:linear-gradient(transparent,rgba(0,0,0,.5));padding:12px 10px 8px;color:#fff;font-size:12px">
                        ♥ {{ $post['likes'] ?? 0 }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            {{-- Empty lookbook --}}
            <div style="text-align:center;padding:60px 20px;border:2px dashed var(--fm-border);border-radius:12px">
                <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                <h6 style="font-weight:600;margin-bottom:8px">No lookbook posts yet</h6>
                <p style="font-size:13px;color:var(--fm-muted)">Share your style by adding lookbook photos.</p>
            </div>
            @endif

            @elseif(request('tab') === 'closet')
            {{-- Closet (active listings) --}}
            @if(!empty($closetListings))
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
                @foreach($closetListings as $listing)
                <a href="{{ route('feimso.listing.show', $listing['id']) }}" class="fm-card" style="text-decoration:none;color:inherit;display:block">
                    <div style="position:relative">
                        <div class="fm-card-img"></div>
                        <span class="fm-badge">{{ ucfirst($listing['listing_type'] ?? 'For sale') }}</span>
                    </div>
                    <div class="fm-card-body">
                        <div class="fm-card-price">AED {{ number_format($listing['price'] ?? 0) }}</div>
                        <div class="fm-card-title">{{ $listing['title'] ?? '' }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:60px 20px;border:2px dashed var(--fm-border);border-radius:12px">
                <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block"><path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.57a1 1 0 00.99.84H5v10a2 2 0 002 2h10a2 2 0 002-2V10h1.15a1 1 0 00.99-.84l.58-3.57a2 2 0 00-1.34-2.23z"/></svg>
                <h6 style="font-weight:600;margin-bottom:8px">Closet is empty</h6>
                <p style="font-size:13px;color:var(--fm-muted);margin-bottom:16px">List an item to sell or rent and it will appear here.</p>
                @auth
                @if(auth()->id() == ($profile['id'] ?? null))
                <a href="{{ route('feimso.listing.create') }}" style="display:inline-block;padding:10px 24px;background:#111827;color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none">Create a listing</a>
                @endif
                @endauth
            </div>
            @endif

            @else
            {{-- Experiences --}}
            @if(!empty($experienceListings))
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:16px">
                @foreach($experienceListings as $listing)
                <a href="{{ route('feimso.listing.show', $listing['id']) }}" class="fm-card" style="text-decoration:none;color:inherit;display:block">
                    <div style="position:relative">
                        <div class="fm-card-img"></div>
                        <span class="fm-badge">Experience</span>
                    </div>
                    <div class="fm-card-body">
                        <div class="fm-card-price">AED {{ number_format($listing['price'] ?? 0) }}
                            @if(!empty($listing['duration_label']))<span style="font-size:12px;font-weight:400;color:var(--fm-muted)"> / {{ $listing['duration_label'] }}</span>@endif
                        </div>
                        <div class="fm-card-title">{{ $listing['title'] ?? '' }}</div>
                    </div>
                </a>
                @endforeach
            </div>
            @else
            <div style="text-align:center;padding:60px 20px;border:2px dashed var(--fm-border);border-radius:12px">
                <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                <h6 style="font-weight:600;margin-bottom:8px">No experiences listed</h6>
                <p style="font-size:13px;color:var(--fm-muted)">Experiences you offer will appear here.</p>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
@endsection
