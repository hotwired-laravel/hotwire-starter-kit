@props(['icon', 'current' => false])
<li>
    <a {{ $attributes->merge(['class' => 'rounded-box ' . ($current ? 'menu-active' : '')]) }}>
        <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />

        <span class="ml-1">{{ $slot }}</span>
    </a>
</li>
