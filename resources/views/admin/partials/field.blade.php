@php
    // $input: form input name, $field: doc field, $def: field definition, $value: form value, $opts: select options
    $type = $def['type'];
    $id = 'f_' . preg_replace('/[^A-Za-z0-9_]/', '_', $input);
    $req = !empty($def['required']);
    $help = isset($def['help']) ? __('admin.help.' . $def['help']) : null;
    $help = $help === 'admin.help.' . ($def['help'] ?? '') ? $def['help'] : $help;
    $old = old($input, is_array($value) ? null : $value);
@endphp

@if($type === 'bool')
    <div class="form-group">
        <input type="hidden" name="{{ $input }}" value="0">
        <div class="custom-control custom-switch">
            <input type="checkbox" class="custom-control-input" id="{{ $id }}" name="{{ $input }}" value="1" @checked(old($input, $value ? '1' : '0') === '1' || (old($input) === null && $value))>
            <label class="custom-control-label" for="{{ $id }}">{{ \App\Admin\Fields::label($field) }}</label>
        </div>
        @if($help)<div class="help">{{ $help }}</div>@endif
    </div>
@else
<div class="form-group">
    <label for="{{ $id }}">{{ \App\Admin\Fields::label($field) }} @if($req)<span class="text-danger">*</span>@endif</label>

    @switch($type)
        @case('textarea')
            <textarea id="{{ $id }}" name="{{ $input }}" rows="3" class="form-control">{{ $old }}</textarea>
            @break
        @case('html')
            <textarea id="{{ $id }}" name="{{ $input }}" rows="10" class="form-control code">{{ $old }}</textarea>
            @break
        @case('json')
            <textarea id="{{ $id }}" name="{{ $input }}" rows="8" class="form-control code" spellcheck="false">{{ $old }}</textarea>
            @break
        @case('images')
            <textarea id="{{ $id }}" name="{{ $input }}" rows="3" class="form-control code" placeholder="{{ __('admin.one_url_per_line') }}">{{ $old }}</textarea>
            <input type="file" name="{{ $input }}_files[]" accept="image/*" multiple class="form-control-file mt-2">
            @break
        @case('polygon')
            <textarea id="{{ $id }}" name="{{ $input }}" rows="6" class="form-control code" placeholder="25.20,55.27">{{ $old }}</textarea>
            @break
        @case('select')
            <select id="{{ $id }}" name="{{ $input }}" class="form-control" @required($req)>
                <option value="">—</option>
                @foreach($opts as $val => $label)
                    <option value="{{ $val }}" @selected((string) $old === (string) $val)>{{ $label }}</option>
                @endforeach
            </select>
            @break
        @case('multiselect')
            @php($selected = old($input, $value ?: []))
            <select id="{{ $id }}" name="{{ $input }}[]" class="form-control" multiple size="{{ min(8, max(3, count($opts))) }}">
                @foreach($opts as $val => $label)
                    <option value="{{ $val }}" @selected(in_array((string) $val, array_map('strval', (array) $selected), true))>{{ $label }}</option>
                @endforeach
            </select>
            <div class="help">{{ __('admin.multiselect_help') }}</div>
            @break
        @case('image')
            <div class="input-group">
                <input id="{{ $id }}" type="url" name="{{ $input }}" value="{{ $old }}" class="form-control code" placeholder="https://">
            </div>
            <input type="file" name="{{ $input }}_file" accept="image/*" class="form-control-file mt-2">
            @if($value)<img src="{{ $value }}" class="img-preview" alt="">@endif
            @break
        @case('latlng')
            <div class="form-row">
                <div class="col"><input type="text" name="{{ $input }}_lat" value="{{ old($input . '_lat', $value['lat'] ?? '') }}" class="form-control code" placeholder="{{ __('admin.latitude') }}" @required($req)></div>
                <div class="col"><input type="text" name="{{ $input }}_lng" value="{{ old($input . '_lng', $value['lng'] ?? '') }}" class="form-control code" placeholder="{{ __('admin.longitude') }}" @required($req)></div>
            </div>
            @break
        @case('datetime')
            <input id="{{ $id }}" type="datetime-local" name="{{ $input }}" value="{{ $old }}" class="form-control" @required($req)>
            @break
        @case('password')
            <input id="{{ $id }}" type="password" name="{{ $input }}" class="form-control" autocomplete="new-password">
            @break
        @case('secret')
            <input id="{{ $id }}" type="password" name="{{ $input }}" class="form-control code" autocomplete="off" placeholder="{{ !empty($isSet) ? '••••••••  (' . __('admin.secret_set') . ')' : __('admin.secret_not_set') }}">
            @if(!empty($isSet))
                <div class="custom-control custom-checkbox mt-1 secret-state">
                    <input type="checkbox" class="custom-control-input" id="{{ $id }}_clear" name="{{ $input }}_clear" value="1">
                    <label class="custom-control-label" for="{{ $id }}_clear">{{ __('admin.secret_clear') }}</label>
                </div>
            @endif
            @break
        @case('color')
            <input id="{{ $id }}" type="color" name="{{ $input }}" value="{{ $old ?: '#ff3b5c' }}" class="form-control" style="max-width:90px">
            @break
        @case('number')
        @case('money')
            <input id="{{ $id }}" type="number" step="any" name="{{ $input }}" value="{{ $old }}" class="form-control" @required($req)>
            @break
        @case('integer')
            <input id="{{ $id }}" type="number" step="1" name="{{ $input }}" value="{{ $old }}" class="form-control" @required($req)>
            @break
        @default
            <input id="{{ $id }}" type="{{ $type === 'email' ? 'email' : 'text' }}" name="{{ $input }}" value="{{ $old }}" class="form-control" @required($req)>
    @endswitch

    @if($help)<div class="help">{{ $help }}</div>@endif
    @error($input)<div class="text-danger small">{{ $message }}</div>@enderror
</div>
@endif
