@props(['icon' => null, 'current' => false, 'as' => 'a'])
<li class="group-has-[:checked]/sidebar:lg:tooltip group-has-[:checked]/sidebar:lg:tooltip-right" data-tip="{{ strip_tags(trim($slot->toHtml())) }}">
    @if ($as === 'a')
    <a {{ $attributes->merge(['class' => 'rounded-box [&_svg]:first:mr-0 ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        <span class="ml-1 group-has-[:checked]/sidebar:lg:hidden whitespace-nowrap">{{ $slot }}</span>
    </a>
    @else
    <button {{ $attributes->merge(['type' => 'button', 'class' => 'rounded-box [&_svg]:first:mr-0 ' . ($current ? 'menu-active' : '')]) }}>
        @if ($iconSection ?? false)
            {{ $iconSection }}
        @else
            <x-dynamic-component :component="'heroicon-o-'.$icon" class="size-6" />
        @endif

        <span class="ml-1 group-has-[:checked]/sidebar:lg:hidden whitespace-nowrap">{{ $slot }}</span>
    </button>
    @endif
</li>
