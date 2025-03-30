@props(['icon' => null, 'current' => false, 'type' => 'button'])

<button {{ $attributes->merge(['class' => 'btn btn-ghost', 'type' => $type]) }}>
    @if ($icon)
    <x-dynamic-component :component="'icons.'.$icon" size="lg" />
    @endif

    {{ $slot }}
</button>
