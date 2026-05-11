<x-layouts.app :title="__('My teams')">
    <section class="w-full max-w-md mx-auto space-y-6">
        @unlesshotwirenative
            <x-back-link :href="route('settings')">{{ __('Profile & Settings') }}</x-back-link>
            <x-text.heading size="xl">{{ __('My teams') }}</x-text.heading>
        @endunlesshotwirenative

        <x-menu>
            @foreach ($teams as $team)
            <x-menu.link icon="user-group" :href="route('settings.teams.show', $team)">{{ $team->name }} @if ($team->is_personal)<span class="badge badge-ghost text-xs">personal</span>@endif <span class="badge badge-ghost lowercase">{{ $team->membership->role->label() }}</span></x-menu.link>
            @endforeach
            <x-menu.link icon="plus" :href="route('settings.teams.create')">{{ __('Create new team') }}</x-menu.link>
        </x-menu>
    </section>
</x-layouts.app>
