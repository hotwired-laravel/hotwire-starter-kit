@props(['size' => 'lg'])

@php
$sizeClass = match ($size) {
    'lg' => 'size-6',
    'md' => 'size-4',
    'sm' => 'size-3',
    default => $size,
};
@endphp

<svg {{ $attributes->merge(['class' => $sizeClass]) }} xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
    {{ $slot }}
</svg>
