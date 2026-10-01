@extends('admin.layout')
@section('title', __("admin.resources.$name"))

@php
    $cols = [];
    foreach ($r['columns'] as $k => $v) {
        is_int($k) ? $cols[$v] = '' : $cols[$k] = $v;
    }
    $sort = request('sort', '_created:desc');
@endphp

@section('content')
<div class="card">
    <div class="card-body pb-2">
        <form class="form-row align-items-end" method="get">
            <div class="col-md-4 mb-2">
                <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ __('admin.search') }}">
            </div>
            @foreach($r['filters'] as $field => $def)
                <div class="col-md-3 col-lg-2 mb-2" style="min-width:170px">
                    <select name="f_{{ $field }}" class="form-control" onchange="this.form.submit()">
                        <option value="">{{ \App\Admin\Fields::label($field) }}: {{ __('admin.all') }}</option>
                        @foreach($filterOptions[$field] as $val => $label)
                            <option value="{{ $val }}" @selected((string) ($filters[$field] ?? '') === (string) $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endforeach
            <div class="col-auto mb-2">
                <button class="btn btn-secondary"><i class="feather-search"></i></button>
                @if(request()->hasAny(['q', 'sort']) || collect(request()->query())->keys()->contains(fn ($k) => str_starts_with($k, 'f_')))
                    <a class="btn btn-link" href="{{ route('admin.resource.index', $name) }}">{{ __('admin.reset') }}</a>
                @endif
            </div>
            @if($r['create'])
                <div class="col text-right mb-2" style="text-align:end!important">
                    <a class="btn btn-primary" href="{{ route('admin.resource.create', $name) }}"><i class="feather-plus"></i> {{ __('admin.add_new') }}</a>
                </div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
            <tr>
                @foreach($cols as $field => $kind)
                    @php($sortable = $kind !== 'image' && !str_starts_with($kind, 'path:'))
                    <th>
                        @if($sortable)
                            @php($next = $sort === "$field:asc" ? "$field:desc" : "$field:asc")
                            <a class="text-reset" href="{{ request()->fullUrlWithQuery(['sort' => $next, 'page' => null]) }}">{{ \App\Admin\Fields::label($field) }}
                                @if(str_starts_with($sort, "$field:"))<i class="feather-chevron-{{ str_ends_with($sort, 'desc') ? 'down' : 'up' }}"></i>@endif
                            </a>
                        @else
                            {{ \App\Admin\Fields::label($field) }}
                        @endif
                    </th>
                @endforeach
                <th class="text-right" style="text-align:end!important">{{ __('admin.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($docs as $doc)
                <tr>
                    @foreach($cols as $field => $kind)
                        <td>@include('admin.partials.cell', ['data' => $doc['data'], 'field' => $field, 'kind' => $kind])</td>
                    @endforeach
                    <td class="text-nowrap" style="text-align:end">
                        <a class="btn btn-sm btn-light" href="{{ route('admin.resource.show', [$name, $doc['id']]) }}" title="{{ __('admin.view') }}"><i class="feather-eye"></i></a>
                        @if($r['edit'] && $r['fields'])
                            <a class="btn btn-sm btn-light" href="{{ route('admin.resource.edit', [$name, $doc['id']]) }}" title="{{ __('admin.edit') }}"><i class="feather-edit-2"></i></a>
                        @endif
                        @if($r['delete'])
                            <form method="post" action="{{ route('admin.resource.destroy', [$name, $doc['id']]) }}" class="d-inline" onsubmit="return confirm(@json(__('admin.confirm_delete')))">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-light text-danger" title="{{ __('admin.delete') }}"><i class="feather-trash-2"></i></button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="{{ count($cols) + 1 }}" class="text-center text-muted py-5">{{ __('admin.no_records') }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($docs->hasPages())
        <div class="card-body pt-3 pb-0 d-flex justify-content-between align-items-center">
            <span class="text-muted">{{ __('admin.showing', ['from' => $docs->firstItem(), 'to' => $docs->lastItem(), 'total' => $docs->total()]) }}</span>
            {{ $docs->onEachSide(1)->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="card-footer bg-white text-muted">{{ __('admin.total', ['total' => $docs->total()]) }}</div>
    @endif
</div>
@endsection
