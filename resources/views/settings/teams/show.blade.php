<x-layouts.app :title="$team->name">
    <section class="w-full max-w-md mx-auto space-y-6">
        @unlesshotwirenative
            <x-back-link :href="route('settings.teams.index')">{{ __('My teams') }}</x-back-link>
            <x-text.heading size="xl">{{ $team->name }}</x-text.heading>
        @endunlesshotwirenative

        <x-menu>
            <x-menu.link icon="arrow-right-end-on-rectangle" :href="route('settings.teams.switch.show', ['team' => $team, 'to_dashboard' => 1])">{{ __('Switch to this team') }}</x-menu.link>
            @can('update', $team)
            <x-menu.link icon="pencil" :href="route('settings.teams.edit', $team)">{{ __('Edit details') }}</x-menu.link>
            @endcan
            <x-menu.link icon="users" :href="route('settings.teams.members.index', $team)">{{ __('Members') }}</x-menu.link>
        </x-menu>
    </section>
</x-layouts.app>
