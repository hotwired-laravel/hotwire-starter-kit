@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600']) }} data-turbo-termporary>
        {{ $status }}
    </div>
@endif
