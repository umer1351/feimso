@extends('feimso.layout')
@section('title', $listing['title'] ?? 'Listing')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row g-4 justify-content-center">
        <div class="col-lg-10 col-xl-8">

            {{-- Breadcrumb --}}
            <nav style="font-size:13px;color:var(--fm-muted);margin-bottom:20px">
                <a href="{{ route('feimso.marketplace') }}" style="color:var(--fm-muted);text-decoration:none">Marketplace</a>
                <span style="margin:0 6px">›</span>
                <span>{{ $listing['title'] ?? 'Listing' }}</span>
            </nav>

            <div class="row g-4">
                {{-- Images --}}
                <div class="col-md-6">
                    <div style="border-radius:12px;overflow:hidden;border:1px solid var(--fm-border);aspect-ratio:4/5;background:#f3f4f6;display:flex;align-items:center;justify-content:center;position:relative">
                        <img src="{{ \App\Helpers\FeimsoImg::url($listing['image_urls'][0] ?? null, $listing['listing_type'] ?? 'sale', $listing['id'] ?? $listing['title'] ?? 0) }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0" alt="">
                        <span class="fm-badge" style="position:absolute;top:12px;left:12px">
                            {{ ucfirst($listing['listing_type'] ?? 'For sale') }}
                        </span>
                    </div>
                </div>

                {{-- Details --}}
                <div class="col-md-6">
                    {{-- Seller --}}
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:16px">
                        <div style="width:38px;height:38px;border-radius:50%;background:#e5e7eb"></div>
                        <div>
                            <div style="font-size:13px;font-weight:600">{{ $listing['seller_username'] ?? 'seller' }}</div>
                            <div style="font-size:12px;color:var(--fm-muted)">{{ $listing['location'] ?? '' }}</div>
                        </div>
                    </div>

                    <div style="font-size:28px;font-weight:700;margin-bottom:8px">
                        {{ $listing['currency'] ?? 'AED' }} {{ number_format($listing['price'] ?? 0) }}
                        @if(($listing['listing_type']??'') === 'rental') <span style="font-size:15px;font-weight:400;color:var(--fm-muted)">per day</span>@endif
                    </div>
                    <h1 style="font-size:20px;font-weight:600;margin-bottom:8px">{{ $listing['title'] ?? '' }}</h1>

                    @if(!empty($listing['size']))
                    <div style="margin-bottom:6px;font-size:14px">
                        <span style="color:var(--fm-muted)">Size:</span> {{ $listing['size'] }}
                    </div>
                    @endif
                    @if(!empty($listing['condition']))
                    <div style="margin-bottom:6px;font-size:14px">
                        <span style="color:var(--fm-muted)">Condition:</span> {{ ucfirst($listing['condition']) }}
                    </div>
                    @endif
                    @if(!empty($listing['colour']))
                    <div style="margin-bottom:6px;font-size:14px">
                        <span style="color:var(--fm-muted)">Colour:</span> {{ $listing['colour'] }}
                    </div>
                    @endif
                    @if(!empty($listing['brand']))
                    <div style="margin-bottom:6px;font-size:14px">
                        <span style="color:var(--fm-muted)">Brand:</span> {{ $listing['brand'] }}
                    </div>
                    @endif

                    @if(!empty($listing['description']))
                    <div style="margin-top:16px;font-size:14px;line-height:1.65;color:#374151;padding-top:16px;border-top:1px solid var(--fm-border)">
                        {{ $listing['description'] }}
                    </div>
                    @endif

                    {{-- Rental specific --}}
                    @if(($listing['listing_type']??'') === 'rental' && !empty($listing['price_per_day']))
                    <div style="background:#f9fafb;border-radius:10px;padding:16px;margin-top:16px">
                        <div style="font-size:13px;font-weight:600;margin-bottom:10px">Rental details</div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:13px">
                            <div><span style="color:var(--fm-muted)">Price/day:</span> AED {{ $listing['price_per_day'] }}</div>
                            @if(!empty($listing['deposit']))<div><span style="color:var(--fm-muted)">Deposit:</span> AED {{ $listing['deposit'] }}</div>@endif
                            @if(!empty($listing['pickup_type']))<div><span style="color:var(--fm-muted)">Pickup:</span> {{ ucfirst($listing['pickup_type']) }}</div>@endif
                        </div>
                    </div>
                    @endif

                    {{-- Actions --}}
                    <div style="margin-top:24px;display:flex;gap:10px">
                        @auth
                        <button onclick="document.getElementById('booking-form').scrollIntoView({behavior:'smooth'})"
                                style="flex:1;padding:13px;background:#111827;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer">
                            {{ ($listing['listing_type']??'sale')==='rental' ? 'Book to rent' : (($listing['listing_type']??'')==='experience' ? 'Book experience' : 'Buy now') }}
                        </button>
                        <button style="width:48px;height:48px;border:1px solid var(--fm-border);border-radius:10px;background:#fff;cursor:pointer;display:flex;align-items:center;justify-content:center">
                            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </button>
                        @else
                        <a href="{{ route('feimso.login') }}" style="flex:1;padding:13px;background:#111827;color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:600;cursor:pointer;text-align:center;text-decoration:none">
                            Sign in to buy
                        </a>
                        @endauth
                    </div>

                    {{-- Booking form --}}
                    @auth
                    @if(!empty($listing['id']))
                    <form id="booking-form" method="POST" action="{{ route('feimso.booking.store') }}" style="margin-top:24px;padding:20px;background:#f9fafb;border-radius:10px">
                        @csrf
                        <input type="hidden" name="listing_id" value="{{ $listing['id'] }}">
                        <div style="font-size:13px;font-weight:600;margin-bottom:14px">Booking details</div>
                        @if(($listing['listing_type']??'sale') !== 'sale')
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <label style="font-size:12px;font-weight:500;display:block;margin-bottom:4px">Start date</label>
                                <input type="date" name="start_date" class="fm-input" style="font-size:13px" required>
                            </div>
                            <div class="col-6">
                                <label style="font-size:12px;font-weight:500;display:block;margin-bottom:4px">End date</label>
                                <input type="date" name="end_date" class="fm-input" style="font-size:13px" required>
                            </div>
                        </div>
                        @endif
                        <div class="mb-3">
                            <label style="font-size:12px;font-weight:500;display:block;margin-bottom:4px">Message to seller <small style="font-weight:400;color:var(--fm-muted)">(optional)</small></label>
                            <textarea name="buyer_notes" rows="2" class="fm-input" style="font-size:13px;resize:none" placeholder="Any questions or special requests?"></textarea>
                        </div>
                        @if($errors->any())<div class="alert alert-danger py-2 mb-2" style="font-size:12px">{{ $errors->first() }}</div>@endif
                        <button type="submit" style="width:100%;padding:11px;background:#111827;color:#fff;border:none;border-radius:8px;font-size:14px;font-weight:600;cursor:pointer">
                            Send booking request
                        </button>
                    </form>
                    @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
