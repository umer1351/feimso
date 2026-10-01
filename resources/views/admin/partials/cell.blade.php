@php
    // $kind: '', image, bool, datetime, status, ref:coll.label, path:a.b
    $v = str_starts_with($kind, 'path:') ? data_get($data, substr($kind, 5)) : ($data[$field] ?? null);
@endphp
@if($kind === 'image')
    @if(is_string($v) && $v !== '')<img src="{{ $v }}" class="thumb" alt="" loading="lazy">@else<span class="thumb d-inline-block"></span>@endif
@elseif($kind === 'bool')
    @if($v)<span class="badge badge-success">{{ __('admin.yes') }}</span>@else<span class="badge badge-light">{{ __('admin.no') }}</span>@endif
@elseif($kind === 'datetime')
    @if(is_array($v) && isset($v['s'])){{ \Carbon\Carbon::createFromTimestamp($v['s'])->format('Y-m-d H:i') }}@else<span class="text-muted">—</span>@endif
@elseif($kind === 'status')
    @include('admin.partials.status', ['status' => $v])
@elseif(str_starts_with($kind, 'ref:'))
    {{ $refs[substr($kind, 4)][$v] ?? ($v ?: '—') }}
@else
    {{ is_scalar($v) ? \Illuminate\Support\Str::limit((string) $v, 60) : ($v === null ? '—' : \Illuminate\Support\Str::limit(json_encode($v, JSON_UNESCAPED_UNICODE), 60)) }}
@endif
