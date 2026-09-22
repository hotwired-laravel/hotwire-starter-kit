@props([
    'digits' => 6,
    'name' => 'code',
    'autofocus' => false,
    'mode' => 'numeric',
])

@php
    $inputmode = match ($mode) {
        'numeric' => 'numeric',
        'alphanumeric' => 'text',
        default => '',
    };

    $pattern = match ($mode) {
        'numeric' => sprintf('[0-9]{%d}', $digits),
        'alphanumeric' => sprintf('[a-zA-Z0-9]{%d}', $digits),
        default => '',
    };
@endphp

<label class="otp">
    @for ($i = 0; $i < $digits; $i++)<span></span>@endfor
    <input
        type="text"
        {{ $attributes->except(['digits'])->merge([
            'autocomplete' => "one-time-code",
            'name' => $name,
            'inputmode' => $inputmode,
            'maxlength' => $digits,
            'pattern' => $pattern,
        ]) }}
    />
</label>
