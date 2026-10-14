@extends('feimso.layout')
@section('title', 'Home')

@section('tabs')
<div class="fm-tabs">
    <a href="{{ route('feimso.feed') }}" class="fm-tab {{ request()->routeIs('feimso.feed') && !request('tab') || request('tab')=='following' ? 'active' : '' }}">Following</a>
    <a href="{{ route('feimso.feed', ['tab'=>'faves']) }}" class="fm-tab {{ request('tab')=='faves' ? 'active' : '' }}">Feimso Faves</a>
</div>
@endsection

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">

            @if(!request('tab') || request('tab') === 'following')
            {{-- ── Following Feed ─────────────────────────── --}}

            @forelse($feedItems ?? $placeholderItems as $item)
                @if($item['type'] === 'lookbook')
                {{-- Lookbook post --}}
                <div class="fm-feed-card">
                    <div style="background:#f3f4f6;aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;position:relative">
                        <img src="{{ \App\Helpers\FeimsoImg::url($item['image'] ?? null, 'lookbook', $loop->index) }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0" alt="">
                    </div>
                    <div class="fm-feed-body">
                        <div class="fm-feed-actions">
                            <a href="#" class="fm-feed-action">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                {{ number_format($item['likes'] ?? 0) }}
                            </a>
                            <a href="#" class="fm-feed-action">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                                {{ number_format($item['comments'] ?? 0) }}
                            </a>
                            <a href="#" class="fm-feed-action">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                            </a>
                            <a href="#" class="fm-feed-action">
                                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @elseif($item['type'] === 'listing')
                {{-- Listing card in feed --}}
                <div class="fm-feed-card">
                    <div class="fm-feed-body" style="display:flex;gap:16px;padding:16px">
                        <div style="width:200px;flex-shrink:0">
                            <div style="background:#f3f4f6;border-radius:8px;aspect-ratio:3/4;position:relative;overflow:hidden;display:flex;align-items:center;justify-content:center">
                                <img src="{{ \App\Helpers\FeimsoImg::url($item['image'] ?? null, ($item['type'] ?? '')==='listing' ? ($item['listing_type']==='rental'?'rental':'sale') : 'lookbook', $item['id'] ?? $loop->index) }}" style="width:100%;height:100%;object-fit:cover" alt="">
                                <span class="fm-badge" style="position:absolute;top:8px;left:8px">{{ ucfirst(str_replace('_',' ',$item['listing_type'] ?? 'For rent')) }}</span>
                            </div>
                        </div>
                        <div style="flex:1">
                            <div class="fm-feed-user">
                                <div class="fm-feed-avatar"></div>
                                <div>
                                    <div class="fm-feed-username">{{ $item['seller_username'] ?? 'seller' }}</div>
                                    <div class="fm-feed-location">{{ $item['location'] ?? 'UAE' }}</div>
                                </div>
                            </div>
                            <div style="font-size:20px;font-weight:700;margin-bottom:4px">
                                {{ $item['currency'] ?? 'AED' }} {{ number_format($item['price'] ?? 0) }}
                                @if(($item['listing_type'] ?? '') === 'rental') <span style="font-size:13px;font-weight:400;color:var(--fm-muted)">p/day</span>@endif
                            </div>
                            <div style="font-size:14px;font-weight:600;margin-bottom:4px">{{ $item['title'] ?? '' }}</div>
                            <div style="font-size:13px;color:var(--fm-muted);margin-bottom:8px">{{ $item['size_label'] ?? '' }}</div>
                            @if(!empty($item['review']))
                            <div style="font-size:13px;color:#374151;line-height:1.5;margin-bottom:10px;border-top:1px solid var(--fm-border);padding-top:10px">
                                {{ Str::limit($item['review'], 140) }}
                                <div style="font-size:12px;color:var(--fm-muted);margin-top:4px">— {{ $item['reviewer'] ?? '' }}</div>
                            </div>
                            @endif
                            <div style="display:flex;align-items:center;gap:14px;font-size:13px;color:var(--fm-muted)">
                                <span>★ {{ $item['rating'] ?? '4.5' }} | {{ $item['booking_count'] ?? 0 }}</span>
                                <a href="#" class="fm-feed-action">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                                </a>
                                <a href="#" class="fm-feed-action">
                                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            @empty
            {{-- Empty / logged out state --}}
            <div class="text-center py-5">
                <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                <h5 style="font-weight:600;margin-bottom:8px">Follow some creators</h5>
                <p style="font-size:14px;color:var(--fm-muted);max-width:300px;margin:0 auto 20px">Your feed will fill up as you follow buyers, sellers and creators you love.</p>
                <a href="{{ route('feimso.marketplace') }}" style="display:inline-block;padding:10px 24px;background:#111827;color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none">Browse the marketplace</a>
            </div>
            @endforelse

            {{-- Suggested for you --}}
            @if(empty(request('tab')) || request('tab') === 'following')
            <div style="margin-top:8px">
                <div style="font-size:14px;font-weight:600;margin-bottom:14px">Suggested for you</div>
                <div style="display:flex;gap:12px;overflow-x:auto;padding-bottom:4px">
                    @for($i = 0; $i < 5; $i++)
                    <div style="flex-shrink:0;width:140px">
                        <div style="background:#f3f4f6;border-radius:10px;aspect-ratio:3/4;display:flex;align-items:center;justify-content:center;margin-bottom:8px">
                            <svg width="28" height="28" viewBox="0 0 40 40"><line x1="0" y1="0" x2="40" y2="40" stroke="#d1d5db" stroke-width="1"/><line x1="40" y1="0" x2="0" y2="40" stroke="#d1d5db" stroke-width="1"/></svg>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif
            @else
            {{-- ── Feimso Faves ─────────────────────────── --}}
            <div style="margin-bottom:28px">
                <div class="fm-section-head">
                    <div class="fm-section-title" style="margin-bottom:0">Featured looks</div>
                    <a href="#" class="fm-see-all">See all</a>
                </div>
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:12px">
                    @for($i=0;$i<4;$i++)
                    <div class="fm-card">
                        <div class="fm-card-img" style="aspect-ratio:4/5"></div>
                        <div class="fm-card-body">
                            <div class="fm-card-title">Featured Look {{ $i+1 }}</div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            <div style="margin-bottom:28px">
                <div class="fm-section-head">
                    <div class="fm-section-title" style="margin-bottom:0">Fresh faces</div>
                    <a href="#" class="fm-see-all">See all</a>
                </div>
                <div style="display:flex;gap:16px;overflow-x:auto;padding-bottom:4px">
                    @for($i=0;$i<6;$i++)
                    <div style="flex-shrink:0;text-align:center">
                        <div style="width:72px;height:72px;border-radius:50%;background:#f3f4f6;margin:0 auto 8px;display:flex;align-items:center;justify-content:center">
                            <svg width="24" height="24" fill="none" stroke="#d1d5db" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div style="font-size:12px;font-weight:500">creator_{{ $i+1 }}</div>
                    </div>
                    @endfor
                </div>
            </div>
            <div>
                <div class="fm-section-head">
                    <div class="fm-section-title" style="margin-bottom:0">Featured listings</div>
                    <a href="{{ route('feimso.marketplace') }}" class="fm-see-all">See all</a>
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                    @for($i=0;$i<6;$i++)
                    <div class="fm-card">
                        @php $badges=['For sale','For rent','Experience']; @endphp
                        <div style="position:relative">
                            <div class="fm-card-img"></div>
                            <span class="fm-badge">{{ $badges[$i % 3] }}</span>
                        </div>
                        <div class="fm-card-body">
                            <div class="fm-card-price">AED {{ rand(60,500) }}</div>
                            <div class="fm-card-title">Feimso Fave Item {{ $i+1 }}</div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Placeholder data for demo
const placeholderItems = [];
</script>
@endpush
