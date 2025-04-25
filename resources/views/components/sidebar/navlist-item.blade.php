@props(['icon' => null, 'current' => false, 'as' => 'a'])
<li>
    @if ($as === 'a')
    <a {{ $attributes->merge(['class' => 'rounded-box [&_svg]:first:mr-1 ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        {{ $slot }}
    </a>
    @else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-box [&_svg]:first:mr-1 ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        {{ $slot }}
    </button>
    @endif
</li>
