@extends('feimso.layout')
@section('title', 'Marketplace')

@section('content')
<div class="container-fluid py-4 px-4">
    <h1 style="font-size:26px;font-weight:700;margin-bottom:20px">Marketplace</h1>
    <div class="row g-4">

        {{-- ── Filter Sidebar ──────────────────────────────── --}}
        <div class="col-lg-3 col-xl-2">
            <form method="GET" action="{{ route('feimso.marketplace') }}" id="filter-form">
                <div class="fm-filter">
                    <div class="fm-filter-title">Filters</div>

                    {{-- Sort by --}}
                    <div class="fm-filter-section" style="border-top:none;padding-top:0">
                        <div class="fm-filter-section-head">Sort by</div>
                        <div class="fm-filter-body">
                            <select name="sort" class="fm-select" onchange="document.getElementById('filter-form').submit()">
                                <option value="relevant" {{ request('sort','relevant')=='relevant'?'selected':'' }}>Most relevant</option>
                                <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>Newest first</option>
                                <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Price: Low to High</option>
                                <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Price: High to Low</option>
                            </select>
                        </div>
                    </div>

                    {{-- Listing type --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="listing_type">
                            Listing type
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_listing_type"><polyline points="18 15 12 9 6 15"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_listing_type">
                            @foreach([''=>'All','sale'=>'For sale','rental'=>'For rent','service'=>'Service','experience'=>'Experiences'] as $val=>$label)
                            <label class="fm-filter-check">
                                <input type="radio" name="listing_type" value="{{ $val }}"
                                       {{ request('listing_type',$val===''?'':null)===$val?'checked':'' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                {{ $label }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Category --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="category">
                            Category
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_category"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_category" style="display:none">
                            @foreach(['Dresses','Tops','Bottoms','Outerwear','Shoes','Bags','Accessories','Activewear','Swimwear','Formal'] as $cat)
                            <label class="fm-filter-check">
                                <input type="checkbox" name="categories[]" value="{{ $cat }}"
                                       {{ in_array($cat,(array)request('categories',[])) ? 'checked':'' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                {{ $cat }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Gender --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="gender">
                            Gender
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_gender"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_gender" style="display:none">
                            @foreach(['Womenswear','Menswear','Childrenswear','Unisex'] as $g)
                            <label class="fm-filter-check">
                                <input type="checkbox" name="gender[]" value="{{ $g }}"
                                       {{ in_array($g,(array)request('gender',[])) ? 'checked':'' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                {{ $g }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Size --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="size">
                            Size
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_size"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_size" style="display:none">
                            <div style="display:flex;flex-wrap:wrap;gap:6px">
                                @foreach(['XS','S','M','L','XL','XXL','6','8','10','12','14','16'] as $sz)
                                <label style="display:flex;align-items:center;justify-content:center;width:38px;height:32px;border:1px solid var(--fm-border);border-radius:6px;font-size:12px;cursor:pointer;{{ in_array($sz,(array)request('sizes',[])) ? 'background:#111827;color:#fff;border-color:#111827;':'' }}">
                                    <input type="checkbox" name="sizes[]" value="{{ $sz }}" style="display:none"
                                           {{ in_array($sz,(array)request('sizes',[])) ? 'checked':'' }}
                                           onchange="document.getElementById('filter-form').submit()">
                                    {{ $sz }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Colour --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="colour">
                            Colour
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_colour"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_colour" style="display:none">
                            @foreach(['Black','White','Beige','Brown','Red','Blue','Green','Pink','Gold','Silver'] as $c)
                            <label class="fm-filter-check">
                                <input type="checkbox" name="colours[]" value="{{ $c }}"
                                       {{ in_array($c,(array)request('colours',[])) ? 'checked':'' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                {{ $c }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Fabric/material --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="fabric">
                            Fabric / material
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_fabric"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_fabric" style="display:none">
                            @foreach(['Cotton','Linen','Silk','Wool','Cashmere','Denim','Leather','Synthetic','Knit'] as $f)
                            <label class="fm-filter-check">
                                <input type="checkbox" name="fabrics[]" value="{{ $f }}"
                                       {{ in_array($f,(array)request('fabrics',[])) ? 'checked':'' }}
                                       onchange="document.getElementById('filter-form').submit()">
                                {{ $f }}
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="fm-filter-section">
                        <div class="fm-filter-section-head" data-toggle="price">
                            Price
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" class="fm-chevron" id="chevron_price"><polyline points="6 9 12 15 18 9"/></svg>
                        </div>
                        <div class="fm-filter-body" id="body_price" style="display:none">
                            <div style="display:flex;gap:8px;align-items:center">
                                <input type="number" name="min_price" placeholder="Min"
                                       value="{{ request('min_price') }}"
                                       class="fm-input" style="padding:8px 10px;font-size:13px">
                                <span style="color:var(--fm-muted)">—</span>
                                <input type="number" name="max_price" placeholder="Max"
                                       value="{{ request('max_price') }}"
                                       class="fm-input" style="padding:8px 10px;font-size:13px">
                            </div>
                            <button type="submit" style="margin-top:10px;padding:7px 14px;background:#111827;color:#fff;border:none;border-radius:6px;font-size:12px;cursor:pointer">Apply</button>
                        </div>
                    </div>

                    @if(request()->hasAny(['listing_type','categories','gender','sizes','colours','fabrics','min_price','max_price','search']))
                    <div style="padding-top:12px">
                        <a href="{{ route('feimso.marketplace') }}" style="font-size:13px;color:#dc2626;text-decoration:none">✕ Clear all filters</a>
                    </div>
                    @endif
                </div>
            </form>
        </div>

        {{-- ── Product Grid ────────────────────────────────── --}}
        <div class="col-lg-9 col-xl-10">
            {{-- Search bar --}}
            <form method="GET" action="{{ route('feimso.marketplace') }}" class="mb-4" style="display:flex;gap:8px">
                @foreach(request()->except('search','page') as $key=>$val)
                    @if(is_array($val))
                        @foreach($val as $v)<input type="hidden" name="{{ $key }}[]" value="{{ $v }}">@endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endif
                @endforeach
                <input type="search" name="search" class="fm-input" style="max-width:340px"
                       value="{{ request('search') }}" placeholder="Search listings…">
                <button type="submit" style="padding:10px 20px;background:#111827;color:#fff;border:none;border-radius:8px;font-size:14px;cursor:pointer">Search</button>
            </form>

            @if($listings->count())
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px">
                @foreach($listings as $listing)
                <a href="{{ route('feimso.listing.show', $listing['id']) }}" class="fm-card" style="text-decoration:none;color:inherit;display:block">
                    <div style="position:relative">
                        <div class="fm-card-img">
                            <img src="{{ \App\Helpers\FeimsoImg::url($listing['image_urls'][0] ?? null, $listing['listing_type'] ?? 'sale', $listing['id'] ?? $loop->index) }}" style="width:100%;height:100%;object-fit:cover;position:absolute;inset:0" alt="">
                        </div>
                        <span class="fm-badge">{{ ucfirst($listing['listing_type'] ?? 'For sale') }}</span>
                        <button class="fm-heart" style="position:absolute;top:8px;right:8px;background:rgba(255,255,255,.85)" onclick="event.preventDefault();this.classList.toggle('hearted')">
                            <svg width="16" height="16" fill="none" stroke="#111827" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                        </button>
                    </div>
                    <div class="fm-card-body">
                        <div class="fm-card-meta" style="align-items:flex-start;margin-top:0">
                            <div class="fm-card-price">
                                {{ $listing['currency'] ?? 'AED' }} {{ number_format($listing['price'] ?? 0) }}
                                @if(($listing['listing_type']??'') === 'rental')<span style="font-size:11px;font-weight:400;color:var(--fm-muted)"> p/day</span>@endif
                            </div>
                        </div>
                        <div class="fm-card-title">{{ $listing['title'] ?? '' }}</div>
                        @if(!empty($listing['size']))
                        <div class="fm-card-sub">Size {{ $listing['size'] }}</div>
                        @endif
                        @if(!empty($listing['seller_username']))
                        <div class="fm-card-sub">{{ $listing['seller_username'] }}</div>
                        @endif
                        @if(!empty($listing['location']))
                        <div class="fm-card-sub">{{ $listing['location'] }}</div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            <div class="mt-4">{{ $listings->withQueryString()->links() }}</div>
            @else
            <div class="text-center py-5">
                <svg width="48" height="48" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 16px;display:block"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
                <h5 style="font-weight:600;margin-bottom:8px">No listings found</h5>
                <p style="font-size:14px;color:var(--fm-muted)">Try adjusting your filters or search term.</p>
                <a href="{{ route('feimso.marketplace') }}" style="display:inline-block;padding:10px 24px;background:#111827;color:#fff;border-radius:8px;font-size:14px;font-weight:600;text-decoration:none">Clear filters</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Collapsible filter sections
document.querySelectorAll('[data-toggle]').forEach(head => {
    head.addEventListener('click', () => {
        const key = head.dataset.toggle;
        const body = document.getElementById('body_' + key);
        const chevron = document.getElementById('chevron_' + key);
        const open = body.style.display !== 'none';
        body.style.display = open ? 'none' : 'block';
        chevron.querySelector('polyline').setAttribute('points', open ? '6 9 12 15 18 9' : '18 15 12 9 6 15');
    });
});
</script>
@endpush
