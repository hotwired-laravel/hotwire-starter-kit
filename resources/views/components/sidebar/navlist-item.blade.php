@props(['icon' => null, 'current' => false])
<li>
    <a {{ $attributes->merge(['class' => 'rounded-box ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        <span class="ml-1">{{ $slot }}</span>
    </a>
</li>
