{{-- All categories of the current section ("See all" next to Top Categories). Rebuilt — the original view was missing. --}}
@include('layouts.app')
@include('layouts.header')
<div class="st-cats-page pt-5 bg-white category-listing-page">
    <div class="container">
        <div class="d-flex align-items-center mb-3 page-title">
            <h3 class="font-weight-bold text-dark title">{{ trans('lang.categories') }}</h3>
        </div>
        <div class="row">
            @forelse($categories as $c)
                @php($url = $isEcommerce ? route('productlist', ['category', $c['id']]) : route('vendorsbycategory', $c['id']))
                <div class="col-6 col-md-2 pb-3 brand-list mb-3">
                    <div class="list-card position-relative">
                        <div class="list-card-image">
                            <a href="{{ $url }}"><img class="img-fluid item-img w-100" src="{{ $c['data']['photo'] ?? '' }}" alt="{{ $c['data']['title'] ?? '' }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('img/pro1.jpg') }}'"></a>
                        </div>
                        <div class="p-2 position-relative brand-title">
                            <div class="list-card-body">
                                <h6 class="mb-1"><a class="text-black" href="{{ $url }}">{{ $c['data']['title'] ?? '' }}</a></h6>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12"><p class="text-muted py-5 text-center">{{ trans('lang.no_results') }}</p></div>
            @endforelse
        </div>
    </div>
</div>
@include('layouts.footer')
@include('layouts.nav')
