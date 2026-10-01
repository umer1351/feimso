@extends('admin.layout')
@section('title', __("admin.resources.$name") . ' · ' . \Illuminate\Support\Str::limit((string) ($doc[$r['title']] ?? $id), 40))

@php
    $fmt = function ($v) {
        if (is_array($v) && ($v['__fs'] ?? null) === 'ts') {
            return \Carbon\Carbon::createFromTimestamp($v['s'])->format('Y-m-d H:i');
        }
        if (is_array($v) && ($v['__fs'] ?? null) === 'geo') {
            return $v['lat'] . ', ' . $v['lng'];
        }
        if (is_bool($v)) {
            return $v ? __('admin.yes') : __('admin.no');
        }
        return is_scalar($v) ? (string) $v : null;
    };
    $scalars = array_filter($doc, fn ($v) => $fmt($v) !== null);
    $nested = array_diff_key($doc, $scalars);
@endphp

@section('content')
<div class="row">
    <div class="col-xl-8">
        @if(!empty($doc['products']) && is_array($doc['products']))
            <div class="card mb-3">
                <div class="card-header bg-white"><strong>{{ __('admin.items') }}</strong></div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>{{ __('admin.fields.name') }}</th><th>{{ __('admin.fields.quantity') }}</th><th>{{ __('admin.fields.price') }}</th><th>{{ __('admin.fields.extras') }}</th></tr></thead>
                        <tbody>
                        @foreach($doc['products'] as $p)
                            <tr>
                                <td>{{ $p['name'] ?? '' }}</td>
                                <td>{{ $p['quantity'] ?? '' }}</td>
                                <td>{{ $p['discountPrice'] ?? $p['price'] ?? '' }}</td>
                                <td class="small text-muted">{{ is_array($p['extras'] ?? null) ? implode(', ', $p['extras']) : ($p['extras'] ?? '') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div class="card mb-3">
            <div class="card-header bg-white"><strong>{{ __('admin.details') }}</strong></div>
            <table class="table table-sm mb-0">
                <tr><th style="width:35%">ID</th><td><code>{{ $id }}</code></td></tr>
                @foreach($scalars as $k => $v)
                    <tr><th>{{ \App\Admin\Fields::label($k) }}</th><td class="text-break">
                        @if(is_string($v) && preg_match('#^https?://.+\.(png|jpe?g|gif|webp|svg)(\?.*)?$#i', $v))
                            <img src="{{ $v }}" class="thumb" alt=""> <a href="{{ $v }}" target="_blank" class="small">{{ \Illuminate\Support\Str::limit($v, 60) }}</a>
                        @elseif($k === 'status')
                            @include('admin.partials.status', ['status' => $v])
                        @else
                            {{ \Illuminate\Support\Str::limit($fmt($v), 300) }}
                        @endif
                    </td></tr>
                @endforeach
            </table>
        </div>

        @foreach($nested as $k => $v)
            <div class="card mb-3">
                <div class="card-header bg-white"><strong>{{ \App\Admin\Fields::label($k) }}</strong></div>
                <div class="card-body p-2"><pre class="json-view mb-0">{{ json_encode($v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre></div>
            </div>
        @endforeach
    </div>

    <div class="col-xl-4">
        @if($r['orders'])
            <div class="card mb-3">
                <div class="card-header bg-white"><strong>{{ __('admin.change_status') }}</strong></div>
                <div class="card-body">
                    <p>@include('admin.partials.status', ['status' => $doc['status'] ?? ''])</p>
                    <form method="post" action="{{ route('admin.resource.status', [$name, $id]) }}">
                        @csrf
                        <select name="status" class="form-control mb-2">
                            @foreach($r['fields']['status']['options'] ?? config('admin.order_statuses') as $s)
                                <option value="{{ $s }}" @selected(($doc['status'] ?? '') === $s)>{{ $s }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-block">{{ __('admin.update') }}</button>
                    </form>
                </div>
            </div>
        @endif
        <div class="card">
            <div class="card-body">
                @if($r['edit'] && $r['fields'])
                    <a class="btn btn-outline-primary btn-block" href="{{ route('admin.resource.edit', [$name, $id]) }}"><i class="feather-edit-2"></i> {{ __('admin.edit') }}</a>
                @endif
                <a class="btn btn-light btn-block" href="{{ route('admin.resource.index', $name) }}">{{ __('admin.back') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection
