@props(['id' => 'team-switcher'])

<button {{ $attributes->merge([
    'class' => 'btn btn-ghost w-full flex items-center justify-between gap-3 py-0 px-3 group',
    'popovertarget' => 'team-switcher',
    'style' => 'anchor-name:--anchor-' . $id,
]) }}>
    <x-dynamic-component component="heroicon-o-user-group" class="size-6" aria-hidden="true" />
    <span class="flex-1 text-left truncate">{{ auth()->user()->currentTeam?->name }}</span>
    <x-dynamic-component component="heroicon-o-chevron-up-down" class="size-4 opacity-50 transition group-hover:opacity-100" aria-hidden="true" />
</button>

<ul
    class="dropdown menu w-52 rounded-box bg-base-100 shadow-sm"
    popover
    id="{{ $id }}"
    style="position-anchor:--anchor-{{ $id }}"
>
    <li class="menu-title text-xs">{{ __('All teams') }}</li>
    @foreach (auth()->user()->teams as $team)
        <li class="w-full">
            <a href="{{ route('settings.teams.switch.show', $team) }}" class="w-full flex items-center justify-between">
                <x-dynamic-component component="heroicon-o-user-group" class="size-5" aria-hidden="true" />
                <span class="flex-1 truncate">{{ $team->name }}</span>
                <x-dynamic-component component="heroicon-o-chevron-right" class="size-4" aria-hidden="true" />
            </a>
        </li>
    @endforeach
    <li></li>
    <li>
        <a href="{{ route('settings.teams.index') }}" class="flex items-center justify-between">
            <x-dynamic-component component="heroicon-o-cog-6-tooth" class="size-5" aria-hidden="true" />
            <span class="flex-1 truncate">{{ __('My teams') }}</span>
        </a>
    </li>
</ul>
