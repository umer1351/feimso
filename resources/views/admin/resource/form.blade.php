@extends('admin.layout')
@section('title', ($id ? __('admin.edit') : __('admin.add_new')) . ' · ' . __("admin.resources.$name"))

@php
    // Long fields go full width; everything else in two columns.
    $wide = ['textarea', 'html', 'json', 'images', 'polygon', 'multiselect'];
@endphp

@section('content')
<form method="post" enctype="multipart/form-data" action="{{ $id ? route('admin.resource.update', [$name, $id]) : route('admin.resource.store', $name) }}">
    @csrf
    @if($id) @method('PUT') @endif
    <div class="row">
        <div class="col-xl-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        @foreach($r['fields'] as $field => $def)
                            <div class="{{ in_array($def['type'], $wide, true) ? 'col-12' : 'col-md-6' }}">
                                @include('admin.partials.field', [
                                    'input' => $field, 'field' => $field, 'def' => $def,
                                    'value' => \App\Admin\Fields::formValue($field, $def, $doc),
                                    'opts' => $options[$field] ?? [],
                                ])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3">
            <div class="card mb-3">
                <div class="card-body">
                    <button class="btn btn-primary btn-block"><i class="feather-save"></i> {{ __('admin.save') }}</button>
                    <a class="btn btn-light btn-block" href="{{ route('admin.resource.index', $name) }}">{{ __('admin.back') }}</a>
                    @if($id)
                        <hr>
                        <div class="small text-muted text-break">ID: <code>{{ $id }}</code></div>
                        <a class="small" href="{{ route('admin.resource.show', [$name, $id]) }}">{{ __('admin.view_raw') }}</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@if($name === 'users' && $id)
    <div class="card" style="max-width:520px">
        <div class="card-header bg-white"><strong>{{ __('admin.wallet') }}</strong> — {{ __('admin.balance') }}: {{ number_format((float) ($doc['wallet_amount'] ?? 0), 2) }}</div>
        <div class="card-body">
            <form method="post" action="{{ route('admin.wallet.adjust', $id) }}" class="form-row">
                @csrf
                <div class="col-4"><input type="number" step="0.01" name="amount" class="form-control" placeholder="±0.00" required></div>
                <div class="col"><input type="text" name="note" class="form-control" placeholder="{{ __('admin.fields.note') }}"></div>
                <div class="col-auto"><button class="btn btn-outline-primary">{{ __('admin.apply') }}</button></div>
            </form>
            <div class="help mt-2">{{ __('admin.help.wallet_adjust_help') }}</div>
        </div>
    </div>
@endif
@endsection
