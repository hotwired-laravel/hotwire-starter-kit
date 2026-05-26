<x-layouts.app :title="$team->name">
    <section class="w-full max-w-md mx-auto space-y-6">
        @unlesshotwirenative
            <x-back-link :href="route('settings.teams.index')">{{ __('My teams') }}</x-back-link>
            <x-text.heading size="xl">{{ $team->name }}</x-text.heading>
        @endunlesshotwirenative

        <x-menu>
            <x-menu.button icon="arrow-right-end-on-rectangle" form="switch-to-team-{{ $team->id }}">{{ __('Switch to this team') }}</x-menu.button>
            @can('update', $team)
            <x-menu.link icon="pencil" :href="route('settings.teams.edit', $team)">{{ __('Edit details') }}</x-menu.link>
            @endcan
            <x-menu.link icon="users" :href="route('settings.teams.members.index', $team)">{{ __('Members') }}</x-menu.link>
        </x-menu>

        @can('delete', $team)
        <x-menu>
            <x-menu.link icon="trash" :href="route('settings.teams.delete', $team)">{{ __('Delete team') }}</x-menu.link>
        </x-menu>
        @endcan
    </section>

    <form action="{{ route('settings.teams.switch.update', $team) }}" method="post" id="switch-to-team-{{ $team->id }}">
        @csrf
        @method('PUT')
        <input type="hidden" name="to_dashboard" value="1">
    </form>
</x-layouts.app>
