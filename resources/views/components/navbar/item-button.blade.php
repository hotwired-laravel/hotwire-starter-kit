@props(['icon' => null, 'current' => false, 'type' => 'button'])

<button {{ $attributes->merge(['class' => 'btn btn-ghost', 'type' => $type]) }}>
    @if ($icon)
    <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
    @endif

    {{ $slot }}
</button>
