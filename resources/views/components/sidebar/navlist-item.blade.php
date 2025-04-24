@props(['icon' => null, 'current' => false, 'as' => 'a'])
<li>
    @if ($as === 'a')
    <a {{ $attributes->merge(['class' => 'rounded-box ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        <span class="ml-1">{{ $slot }}</span>
    </a>
    @else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-box ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        <span class="ml-1">{{ $slot }}</span>
    </button>
    @endif
</li>
