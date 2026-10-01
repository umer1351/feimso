@php
    $s = (string) ($status ?? '');
    $cls = match (true) {
        str_contains($s, 'Completed') => 'success',
        str_contains($s, 'Rejected') || str_contains($s, 'Cancelled') => 'danger',
        str_contains($s, 'Placed') || str_contains($s, 'Pending') => 'warning',
        $s === '' => 'light',
        default => 'info',
    };
    $key = 'admin.status.' . \Illuminate\Support\Str::snake(str_replace(' ', '', $s));
    $label = __($key) === $key ? $s : __($key);
@endphp
<span class="badge badge-{{ $cls }} badge-status">{{ $label ?: '—' }}</span>
