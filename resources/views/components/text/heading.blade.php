@props(['size' => 'lg'])

@php
$sizeClasses = match ($size) {
    'lg' => 'text-lg',
    'xl' => 'text-xl',
    '2xl' => 'text-2xl',
    default => $size,
};
@endphp

<div {{ $attributes->merge(['class' => 'font-medium [:where(&)]:text-base-800 ' . $sizeClasses]) }}>{{ $slot }}</div>
