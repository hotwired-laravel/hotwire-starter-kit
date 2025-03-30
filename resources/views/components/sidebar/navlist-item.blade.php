@props(['icon', 'current' => false])
<li>
    <a {{ $attributes->merge(['class' => 'rounded-box ' . ($current ? 'menu-active' : '')]) }}>
        <x-dynamic-component :component="'icons.'.$icon" />

        <span class="ml-1">{{ $slot }}</span>
    </a>
</li>
