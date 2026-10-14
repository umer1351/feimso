@extends('admin.layout')
@section('title', __('admin.dashboard'))

@section('content')

{{-- Feimso 2.0 KPIs --}}
@if(!empty($feimso))
<div class="row mb-2">
    <div class="col-12"><h6 class="text-uppercase text-muted fw-semibold" style="font-size:11px;letter-spacing:.05em">Feimso 2.0</h6></div>
    @foreach([
        'total_users'       => ['label'=>'Registered Users',        'href'=>route('admin.resource.index','feimso_users'),     'icon'=>'users'],
        'pending_verif'     => ['label'=>'Pending Verifications',    'href'=>route('admin.resource.index','creator_verifications'),'icon'=>'shield'],
        'active_listings'   => ['label'=>'Active Listings',          'href'=>route('admin.resource.index','listings'),         'icon'=>'tag'],
        'open_disputes'     => ['label'=>'Open Disputes',            'href'=>route('admin.resource.index','disputes'),         'icon'=>'alert-triangle'],
        'bookings_today'    => ['label'=>'Bookings Today',           'href'=>route('admin.resource.index','bookings'),         'icon'=>'calendar'],
        'pipeline_approved' => ['label'=>'Approved Creators',        'href'=>route('admin.resource.index','creator_pipeline'), 'icon'=>'trending-up'],
    ] as $key => $meta)
        @if(isset($feimso[$key]))
        <div class="col-sm-6 col-xl-2 mb-3">
            <a href="{{ $meta['href'] }}" class="card stat text-reset text-decoration-none">
                <div class="n">{{ number_format($feimso[$key]) }}</div>
                <div class="l"><i class="feather-{{ $meta['icon'] }} me-1"></i>{{ $meta['label'] }}</div>
            </a>
        </div>
        @endif
    @endforeach
</div>
<hr class="mb-3">
@endif

{{-- Legacy stats --}}
<div class="row">
    @foreach(['customers' => 'users', 'vendors' => 'vendors', 'products' => 'vendor_products', 'orders' => 'vendor_orders'] as $key => $res)
        <div class="col-sm-6 col-xl-3 mb-3">
            <a href="{{ route('admin.resource.index', $res) }}" class="card stat text-reset text-decoration-none">
                <div class="n">{{ number_format($stats[$key]) }}</div>
                <div class="l">{{ __("admin.stats.$key") }}</div>
            </a>
        </div>
    @endforeach
</div>

<div class="row">
    <div class="col-lg-8 mb-3">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between">
                <strong>{{ __('admin.recent_orders') }}</strong>
                <a href="{{ route('admin.resource.index', 'vendor_orders') }}">{{ __('admin.see_all') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>#</th><th>{{ __('admin.fields.author') }}</th><th>{{ __('admin.fields.vendor') }}</th><th>{{ __('admin.fields.status') }}</th></tr></thead>
                    <tbody>
                    @forelse($recent as $o)
                        <tr>
                            <td><a href="{{ route('admin.resource.show', ['vendor_orders', $o['id']]) }}">{{ \Illuminate\Support\Str::limit($o['id'], 14) }}</a></td>
                            <td>{{ data_get($o, 'data.author.firstName') }} {{ data_get($o, 'data.author.lastName') }}</td>
                            <td>{{ data_get($o, 'data.vendor.title') }}</td>
                            <td>@include('admin.partials.status', ['status' => data_get($o, 'data.status')])</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted text-center py-4">{{ __('admin.no_records') }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-3">
        <div class="card">
            <div class="card-header bg-white"><strong>{{ __('admin.orders_by_status') }}</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($byStatus as $status => $n)
                    <li class="list-group-item d-flex justify-content-between">@include('admin.partials.status', ['status' => $status]) <span>{{ $n }}</span></li>
                @empty
                    <li class="list-group-item text-muted">{{ __('admin.no_records') }}</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
