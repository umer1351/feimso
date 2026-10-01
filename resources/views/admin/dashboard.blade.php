@extends('admin.layout')
@section('title', __('admin.dashboard'))

@section('content')
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
