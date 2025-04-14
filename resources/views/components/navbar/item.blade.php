@props(['icon' => null, 'current' => false])

<a @if ($current) data-current="data-current" @endif {{ $attributes->merge(['class' => 'btn btn-ghost data-current:btn-active!']) }}>
    @if ($icon)
    <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
    @endif

    {{ $slot }}
</a>
