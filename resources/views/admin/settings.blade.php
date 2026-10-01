@extends('admin.layout')
@section('title', __('admin.settings') . ' · ' . __("admin.settings_tabs.$tab"))

@section('content')
<ul class="nav nav-tabs mb-3">
    @foreach($tabs as $key => $def)
        <li class="nav-item"><a class="nav-link {{ $key === $tab ? 'active' : '' }}" href="{{ route('admin.settings', $key) }}">{{ __("admin.settings_tabs.$key") }}</a></li>
    @endforeach
</ul>

@if($tab === 'payments')
    <div class="alert alert-warning small">{{ __('admin.help.payments_warning') }}</div>
@endif

<form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.update', $tab) }}">
    @csrf
    <div class="row">
        @foreach($tabs[$tab]['docs'] as $docId => $fields)
            @php($wideDoc = collect($fields)->contains(fn ($d) => in_array($d['type'], ['html', 'json'], true)))
            <div class="{{ $wideDoc ? 'col-12' : 'col-lg-6' }} mb-3">
                <div class="card h-100">
                    <div class="card-header bg-white"><strong>{{ __("admin.settings_docs.$docId") === "admin.settings_docs.$docId" ? \Illuminate\Support\Str::headline($docId) : __("admin.settings_docs.$docId") }}</strong></div>
                    <div class="card-body">
                        @foreach($fields as $field => $def)
                            @include('admin.partials.field', [
                                'input' => $docId . '__' . $field, 'field' => $field, 'def' => $def,
                                'value' => \App\Admin\Fields::formValue($field, $def, $docs[$docId]),
                                'opts' => $options[$docId][$field] ?? [],
                                'isSet' => ($docs[$docId][$field] ?? '') !== '',
                            ])
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="btn btn-primary"><i class="feather-save"></i> {{ __('admin.save') }}</button>
</form>
@endsection
